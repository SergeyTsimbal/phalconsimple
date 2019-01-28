<?php

use App\Mail\Mail;
use App\Acl\Acl;


class AgentController extends ControllerBase
{

    protected $id;
    protected $is_curator;
    protected $acl;
    protected $user;

    public function initialize()
    {
        $this->authorized();
        $this->id = $this->session->get('AUTH_ID');
        $this->is_curator = $this->session->get('AUTH_IS_CURATOR');
        $this->acl = new Acl();
        $this->user = Users::findFirst($this->id);
    }

    public function addAction()
    {
        $check = $this->acl->allowed($this->user->roles->name, 'private', 'add');
        if(!$check) $this->response->redirect('/index/denied');
    }

    public function sendInvitationAction()
    {
        $email = $this->request->getPost("email");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flashSession->error("E-mail адрес введен не верно");
            return $this->response->redirect('/agent/add');
        }

        $text = 'Ссылка на регистрацию на сайте http://phalcon2/user/register<br/>Ключ куратора ';
        $key_curator = uniqid();
        $parent_id = $this->id;

        $mail = new Mail();
        $result = $mail->send($email, $text . $key_curator);

        if($result){
            $user = new Users();
            $user->parent_id = $parent_id;
            $user->role_id = 1;
            $user->email = $email;
            $user->key_curator = $key_curator;
            $user->created_at = date('Y-m-d H:i:s');
            $user->save();

            $this->flashSession->success('Приглашение отправлено');
            return $this->response->redirect('/agent/view');
        }


    }
    public function viewAction(){
        $this->view->parent_id = $this->id;
        $this->view->agents = Users::find(['parent_id = "'.$this->id.'"']);
        $check = $this->acl->allowed($this->user->roles->name, 'private', 'view');
        if(!$check) $this->response->redirect('/index/denied');
    }

}