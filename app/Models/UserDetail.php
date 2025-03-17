<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends __SystemBaseModel {
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
