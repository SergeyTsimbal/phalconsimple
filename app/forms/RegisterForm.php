<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;

class RegisterForm extends Form
{
    private static $required = 'Заполните это поле';

    public function initialize()
    {

        $first_name = new Text('first_name', [
            "class" => "form-control",
            "required" => true,
            "placeholder" => "Имя"
        ]);

        $first_name->addValidator(
            new PresenceOf(['message' => self::$required])
        );

        $last_name = new Text('last_name', [
            "class" => "form-control",
             "required" => true,
            "placeholder" => "Фамилия"
        ]);

        $last_name->addValidator(
            new PresenceOf(['message' => self::$required])
        );

        $email = new Text('email', [
            "class" => "form-control",
            "required" => true,
            "placeholder" => self::$required
        ]);

        $email->addValidators([
            new PresenceOf(['message' => self::$required]),
            new Email(['message' => 'Введите валидный email']),
        ]);


        $key_curator = new Text('key_curator', [
            "class" => "form-control",
            "required" => true,
            "placeholder" => "Ключ от куратора"
        ]);

        $key_curator->addValidator(
            new PresenceOf(['message' => self::$required])
        );


        $password = new Password('password', [
            "class" => "form-control",
            "required" => true,
            "placeholder" => "Your Password"
        ]);
        $password->addValidators([
            new PresenceOf(['message' => self::$required]),
            new StringLength(['min' => 6, 'message' => 'Пароль должен состоять минимум из 6 символов']),
            new Confirmation(['with' => 'password_confirm', 'message' => 'Пароли не совпадают']),
        ]);

        $passwordNewConfirm = new Password('password_confirm', [
            "class" => "form-control",
            "required" => true,
            "placeholder" => "Повторите пароль"
        ]);
        $passwordNewConfirm->addValidators([
            new PresenceOf(['message' => self::$required]),
        ]);

        $submit = new Submit('submit', [
            "value" => "Зарегистрироваться",
            "class" => "btn btn-primary",
        ]);
        $this->add($first_name);
        $this->add($last_name);
        $this->add($email);
        $this->add($password);
        $this->add($passwordNewConfirm);
        $this->add($key_curator);
        $this->add($submit);
    }
}