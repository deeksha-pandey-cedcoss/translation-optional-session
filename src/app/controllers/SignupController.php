<?php

use Phalcon\Mvc\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class SignupController extends Controller
{

    public function IndexAction()
    {
        // default action
    }

    public function registerAction()
    {
        $user = new Users();

        $data = array(
            "name" => $this->escaper->escapeHtml($this->request->getPost("name")),
            "email" => $this->escaper->escapeHtml($this->request->getPost("email")),
            "password" => $this->escaper->escapeHtml($this->request->getPost("password")),
            'role' => $this->request->getPost('role')
        );


        $user->assign(
            $data,
            [
                'name',
                'email',
                'password',
                'role',

            ]
        );
        $success = $user->save();

        $key = '123456789';

        $payload = [
            'role' => $data['role'],
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        // print_r($jwt);die;
        $this->response->redirect('/product/index?bearer=' . $jwt);

        $this->view->success = $success;
        if ($success) {
            $this->view->message = "Register succesfully";
        } else {
            $this->view->message = "Not Register due to following reason: <br>" . implode("<br>", $user->getMessages());
        }
    }
}
