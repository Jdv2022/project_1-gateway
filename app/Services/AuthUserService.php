<?php

namespace App\Services;

class AuthUserService {

    protected int $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function authUser():array {
        return $this->user;
    }

    public function getUserHierarchyLevel() {
        // return $this->user['user_hierarchy_level']; 
    }

}