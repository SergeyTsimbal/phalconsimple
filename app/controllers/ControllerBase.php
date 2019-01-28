<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public function authorized()
    {
        if (!$this->isLoggedIn()) {
            return $this->response->redirect('user/login');
        }

    }
    public function isLoggedIn()
    {

        if ($this->session->has('AUTH_ID') AND $this->session->has('AUTH_EMAIL')) {
            $this->view->user = Users::findFirst($this->session->get('AUTH_ID'));
            return true;
        }
        return false;
    }
}
