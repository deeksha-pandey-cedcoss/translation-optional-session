<?php

use Phalcon\Mvc\Controller;

// defalut controller view
class AbcController extends Controller
{
    public function indexAction()
    {
        //    default action

    }
    public function handlerAction()
    {
        //    default action

    }
    public function assets1Action()
    {

        $str = "";
        foreach ($this->session->get('action') as $key => $value) {
            if ($key == $_POST['data']) {
                foreach ($value as $key1 => $value1) {
                    $str .= "<option value=" . $value1 . ">" . $value1 . "</option>";
                }
            }
        }
        return $str;
    }
}
