<?php

namespace App\Services;

use App\Models\User;

class AuthUserService {

    protected int $id;
	protected array $user;

    public function __construct(int $id) {
        $this->id = $id;
		$this->user = User::find($this->id)->toArray();
    }

    public function authUser():array {
        return $this->user;
    }

    public function getUserHierarchyLevel() {
        // return $this->user['user_hierarchy_level']; 
    }

}