<?php

namespace App\Services;

class AuthUserService {

    protected array $user = [];

    public function __construct(array $user) {
        $this->user = $user;
    }

    public function authUser():array {
        return $this->user;
    }

    public function getUserHierarchyLevel():int {
        return $this->user['user_hierarchy_level']; 
    }

}