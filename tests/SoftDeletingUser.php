<?php

namespace CodeMaster\CodeAcl\Test;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class SoftDeletingUser extends User
{
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'email'];

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
