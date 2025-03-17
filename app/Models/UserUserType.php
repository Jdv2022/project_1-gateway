<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUserType extends Model {
    
    protected $table = 'user_user_type';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function userType() {
        return $this->belongsTo(UserType::class);
    }

}
