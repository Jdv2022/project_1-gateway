<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\AuthUserService;
use Carbon\Carbon;

class __SystemBaseModel extends Model {

    protected AuthUserService $authUserService;
    public $timestamps = false;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }
    /* Set Default Attributes */
    protected static function boot() {
        parent::boot();

        // static::creating(function ($model) {
        //     $model->setCreatedAttributes();
        // });

        // static::updating(function ($model) {
        //     $model->setUpdatedAttributes();
        // });
    }

    private function getAuthUser():array {
        return app(AuthUserService::class)->getUser();
    }

    private function setCreatedAttributes():void {
        $user = $this->getAuthUser();
        $now = Carbon::now();

        if($this->hasColumn('created_at')) $this->created_at = $now;
        if($this->hasColumn('created_at_timezone')) $this->created_at_timezone = '+8:00';
        if($this->hasColumn('created_by_user_id')) $this->created_by_user_id = $user['id'];
        if($this->hasColumn('created_by_username')) $this->created_by_username = $user['created_by_username'];
        if($this->hasColumn('created_by_user_type')) $this->created_by_user_type = $user['created_by_user_type'];
    }

    private function setUpdatedAttributes():void {
        $user = $this->getAuthUser();
        $now = Carbon::now();

        if($this->hasColumn('updated_at')) $this->updated_at = $now;
        if($this->hasColumn('updated_at_timezone')) $this->updated_at_timezone = '+8:00';
        if($this->hasColumn('updated_by_user_id')) $this->updated_by_user_id = $user['id'];
        if($this->hasColumn('updated_by_username')) $this->updated_by_username = $user['updated_by_username'];
        if($this->hasColumn('updated_by_user_type')) $this->updated_by_user_type = $user['updated_by_user_type'];
    }

    private function hasColumn(string $column):bool {
        return in_array(
            $column, 
            $this->getConnection()
                ->getSchemaBuilder()
                ->getColumnListing($this->getTable())
        );
    }
}
