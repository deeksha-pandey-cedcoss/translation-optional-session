<?php

use Phalcon\Mvc\Controller;
// defalut controller view
class RoleController extends Controller
{
    public function indexAction()
    {
        //    default action

    }
    public function addrolesAction()
    {
        $data = array(
            "role" => $this->escaper->escapeHtml($this->request->getPost("role"))


        );
        $data1 = array(
            "component" => $this->escaper->escapeHtml($this->request->getPost("component")),
        );

        $controllers = array("Index Controller", "Product Controller", "Order Controller", "Setting Controller");

        $action = array(
            "Index" => array("index"),
            "Product" => array("index", "view", "add"),
            "Order" => array("index", "add", "show"),
            "Setting" => array("index", "add"),
        );

        $this->session->set("action", $action);
        $this->session->set("list", $controllers);
        $this->session->set('roles', $data);
        $this->session->set('lists', $data1);
    }
}
