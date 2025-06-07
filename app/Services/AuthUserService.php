<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Log;

class AuthUserService {

    protected int $id;
    protected string $user_timezone;
	protected array $user;

    public function __construct(int $id, string $user_timezone) {
        $this->id = $id;
        $this->user_timezone = $user_timezone;
		$key = 'user_' . $id;
		$this->user = json_decode(Redis::get($key), true);
    }

    public function authUser():array {
        return $this->user;
    }


	public function getUserTimeZone():string {
        return $this->user_timezone;
    }

    public function getUserHierarchyLevel() {
        // return $this->user['user_hierarchy_level']; 
    }

}