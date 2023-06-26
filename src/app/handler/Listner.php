<?php

use Phalcon\Di\Injectable;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Application;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Listner extends Injectable
{
    public function beforeHandleRequestProduct()
    {
        $di = $this->getDI();
        $connection = $di->get('db');
        $product = $this->db->fetchAll("SELECT * FROM settings WHERE `id`=1", \Phalcon\Db\Enum::FETCH_ASSOC);
        if ($product[0]['title'] == "tag1") {
            $_POST['pname'] = $_POST['pname'] . "-" . $_POST['ptags'];
        }
        if ($_POST['pprice'] == "" || $_POST['pprice'] == 0) {
            $_POST['pprice'] = $product[0]['price'];
        }
        if ($_POST['pstock'] == "" || $_POST['pstock'] == 0) {
            $_POST['pstock'] = $product[0]['stock'];
        }
    }
    public function beforeHandleRequestOrder()
    {
        $di = $this->getDI();
        $connection = $di->get('db');
        $product = $this->db->fetchAll("SELECT * FROM settings WHERE `id`=1", \Phalcon\Db\Enum::FETCH_ASSOC);

        if ($_POST['zipcode'] == "") {
            $_POST['zipcode'] = $product[0]['zipcode'];
        }
    }
    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dispatcher)
    {
        $bearer = $app->request->get("bearer");
        if (!empty($bearer)) {
            
            $key = "123456789";
            $decoded = JWT::decode($bearer, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            session_start();
            $acl = new Memory();
            if (isset($_SESSION['assign'])) {
                foreach ($_SESSION['assign'] as $key => $value) {
                    foreach ($value as $vkey => $vvalue) {
                        $acl->addRole($value['role']);
                    }
                }
            }

            $acl->addRole('guest');
            $acl->addRole('user');
            $acl->addRole('admin');
            $acl->addRole('manager');
            $acl->addComponent(
                'index',
                [
                    'index',

                ]
            );
            $acl->addComponent(
                'order',
                [
                    'index',
                    'add',
                    'show'
                ]
            );
            $acl->addComponent(
                'Product',
                [
                    'index',
                    'add',
                    'view'

                ]
            );
            $acl->addComponent(
                'setting',
                [
                    'index',
                    'add'

                ]
            );

            $acl->allow("admin", '*', '*');
            $acl->allow("user", '*', '*');
            $acl->deny("guest", "*", "*");


            $action = "index";
            $controller = "index";
            $role = $decoded_array['role'];


            if (!empty($dispatcher->getActionName())) {
                $action =  $dispatcher->getActionName();
            }
            if (!empty($dispatcher->getControllerName())) {
                $controller =  $dispatcher->getControllerName();
            }
            if (isset($_SESSION['assign'])) {
                foreach ($_SESSION['assign'] as $key => $value) {
                    foreach ($value as $vkey => $vvalue) {
                        $acl->allow($value['role'], $value['controller'], $value['action']);
                    }
                }
                foreach ($_SESSION['assign'] as $key => $value) {
                    foreach ($value as $vkey => $vvalue) {
                        if (true === $acl->isAllowed($value['role'], $value['controller'], $value['action'])) {
                            echo 'Access granted!';
                        } else {
                            echo $this->locale->_('access-denied');
                            $this->response->redirect('/index/');
                        }
                    }
                }
            }
        } else {
            echo $this->locale->_('Not recieved Token');
            die;
        }
    }
}
