<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Email;

class LoginForm extends Form
{
    private static $required = 'Заполните все поля';

    public function initialize()
    {
        /**
         * Email Address
         */
        $email = new Text('email', [
            "class" => "form-control",
            // "required" => true,
            "placeholder" => "Email"
        ]);
        // form email field validation
        $email->addValidators([
            new PresenceOf(['message' => self::$required]),
            new Email(['message' => 'Введите валидный email']),
        ]);
        /**
         * Password
         */
        $password = new Password('password', [
            "class" => "form-control",
            // "required" => true,
            "placeholder" => "Password"
        ]);

        $password->addValidators([
            new PresenceOf(['message' => self::$required]),
            new StringLength(['min' => 5, 'message' => 'Пароль должен состоять минимум из 6 символов']),
        ]);
        /**
         * Submit Button
         */
        $submit = new Submit('submit', [
            "value" => "Войти",
            "class" => "btn btn-primary",
        ]);
        $this->add($email);
        $this->add($password);
        $this->add($submit);
    }
}