<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Application;

class AddactionController extends Controller
{
    public function indexAction()
    {
        //    default action0

    }
    public function addactionAction()
    {
        session_start();
        if (!isset($_SESSION['assign'])) {
            $_SESSION['assign'] = [];
        }

        $data = array(
            "role" => ($this->request->getPost("roles")),
            "controller" => ($this->request->getPost("component")),
            "action" => ($this->request->getPost("action"))

        );

        echo "<pre>";
        array_push($_SESSION['assign'], $data);
        $this->response->redirect('/index/');
    }
}
