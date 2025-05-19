<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Log;

class AuthUserService {

    protected int $id;
	protected array $user;

    public function __construct(int $id) {
        $this->id = $id;
		$key = 'user_' . $id;
		$this->user = json_decode(Redis::get($key), true);
    }

    public function authUser():array {
        return $this->user;
    }

    public function getUserHierarchyLevel() {
        // return $this->user['user_hierarchy_level']; 
    }

}