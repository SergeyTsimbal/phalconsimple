<?php

namespace App\Acl;

use Phalcon\Acl\Adapter\Memory as AclList;


class Acl {

    public function getAcl(){
        $acl = new AclList();
        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        $acl->addRole('Guest');
        $acl->addRole('Agent');

        $acl->addResource('private', ['view', 'add']);
        $acl->addResource('public', ['read']);

        $acl->allow('Agent', 'private', ['view', 'add']);
        $acl->allow('Agent', 'public', 'read');
        $acl->allow('Guest', 'public', 'read');
        return $acl;
    }
    public function allowed($role, $resource, $access){
        $acl = $this->getAcl();
        $res = $acl->isAllowed($role, $resource, $access);
        return $res;

    }

}



