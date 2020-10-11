<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Ramsey\Uuid\Uuid;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authorizable, Authenticatable, HasRoles;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'email'];

    public function convertToModels(Collection $permissions)
    {
        $permissions = $this->convertToPermissionModels($permissions);

        return $permissions;
    }

    public static function boot()
    {
        parent::boot();
        self::creating(/**
         * @param $model
         */
            function ($model) {
                $model->id = (string)Uuid::uuid4();
            });
    }
}
