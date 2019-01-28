<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        if ($this->session->has('IS_LOGIN'))
        {
            return $this->response->redirect('user/profile');

        }else {
            return $this->dispatcher->forward(array(
                "controller" => "User",
                "action" => "login"
            ));
        }
    }
    public function deniedAction()
    {

    }

}

