<?php

use App\Forms\RegisterForm;
use App\Forms\LoginForm;

class UserController extends ControllerBase
{
    protected $loginForm;
    protected $usersModel;

    public function initialize()
    {
        $this->loginForm = new LoginForm();
        $this->usersModel = new Users();
    }

    public function loginAction()
    {

        $this->tag->setTitle('Phalcon :: Login');
        $this->view->pick('index/index');
        $this->view->form = new LoginForm();
    }

    public function loginSubmitAction()
    {

        if (!$this->request->isPost()) {
            return $this->response->redirect('user/login');
        }

        if (!$this->security->checkToken()) {
            $this->flashSession->error("Invalid Token");
            return $this->response->redirect('user/login');
        }
        $this->loginForm->bind($_POST, $this->usersModel);

        if (!$this->loginForm->isValid()) {
            foreach ($this->loginForm->getMessages() as $message) {
                $this->flashSession->error($message);
                $this->dispatcher->forward([
                    'controller' => $this->router->getControllerName(),
                    'action'     => 'login',
                ]);
                return;
            }
        }


        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = Users::findFirst([
            'email = :email:',
            'bind' => [
                'email' => $email,
            ]
        ]);


        if ($user) {
            if ($this->security->checkHash($password, $user->password))
            {
                $this->session->set('AUTH_ID', $user->id);
                $this->session->set('AUTH_PARENT_ID', $user->parent_id);
                $this->session->set('AUTH_ROLE_ID', $user->role_id);
                $this->session->set('AUTH_IS_CURATOR', $user->is_curator);
                $this->session->set('AUTH_FIRST_NAME', $user->first_name);
                $this->session->set('AUTH_EMAIL', $user->email);
                $this->session->set('IS_LOGIN', 1);
                return $this->response->redirect('user/profile');
            }
        } else {
            $this->security->hash(rand());
        }

        $this->flashSession->error("Не удаеться найти такого пользователя");
        return $this->response->redirect('user/login');
    }


    public function saveAction()
    {
        $form = new RegisterForm();
        $form->bind($_POST, $this->usersModel);

        if (!$form->isValid()) {
            foreach ($form->getMessages() as $message) {
                $this->flashSession->error($message);
                $this->dispatcher->forward([
                    'controller' => $this->router->getControllerName(),
                    'action'     => 'register',
                ]);
                return;
            }
        }

        $first_name = $this->request->getPost("first_name");
        $last_name = $this->request->getPost("last_name");
        $email = $this->request->getPost("email");
        $password = $this->request->getPost("password");
        $key_curator = $this->request->getPost("key_curator");


        if($key_curator == 'admin') {
            $user = new Users();

            $user->role_id = 2;
            $user->is_curator = 1;
            $user->parent_id = 0;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user->key_curator = $key_curator;
            $user->password = $this->security->hash($password);
            $user->created_at = date('Y-m-d H:i:s');

            if ($user->save()) {
                $this->flashSession->success('Спасибо за регистрацию');
                return $this->response->redirect('/');
            }
        }
        else{
            $user = Users::findFirst(
                [
                    'conditions' => 'key_curator = "'.$key_curator.'" and email ="'. $email.'" and first_name IS NULL',
                ]
            );
            if(is_object($user)) {

                $user->role_id = 2;
                $user->is_curator = 0;
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->key_curator = $key_curator;
                $user->password = $this->security->hash($password);
                $user->created_at = date('Y-m-d H:i:s');
                $user->save();

                if ($user->save()) {
                    $this->flashSession->success('Спасибо за регистрацию');
                    return $this->response->redirect('/');
                }
            }else{
                $this->flashSession->error('Неверный ключ куратора или email');
                return $this->response->redirect('/user/register');
            }
        }

    }


    public function editInfoAction()
    {
       $user = Users::findFirst($this->session->get('AUTH_ID'));
       $user->first_name = $this->request->getPost("first_name");
       $user->last_name = $this->request->getPost("last_name");
       $user->save();
       $this->response->redirect('/user/profile');
    }

    public function profileAction()
    {
        $this->authorized();
    }

    public function logoutAction()
    {
        $this->session->destroy();
        return $this->response->redirect('user/login');
    }

    public function registerAction()
    {
        $this->tag->setTitle('Phalcon :: Register');
        $this->view->form = new RegisterForm();
    }

}