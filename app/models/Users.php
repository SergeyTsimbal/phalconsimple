<?php

use Phalcon\Mvc\Model;

class Users extends Model
{

    public function initialize()
    {
        $this->belongsTo("role_id", "Roles", "id");
    }

    public $id;
    public $role_id;
    public $parent_id;
    public $is_curator;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $key_curator;
    public $created_at;
}