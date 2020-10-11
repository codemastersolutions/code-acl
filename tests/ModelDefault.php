<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Traits\SetUpModel;
use Illuminate\Database\Eloquent\Model;

class ModelDefault extends Model
{
    use SetUpModel;

    public function __construct($attributes = [])
    {
        self::$modelData = config('code-acl.models.permission');

        parent::__construct($attributes);
    }
}
