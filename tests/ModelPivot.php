<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Traits\SetUpPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ModelPivot extends Pivot
{
    use SetUpPivot;

    public function __construct($attributes = [])
    {
        self::$modelData = config('code-acl.models.permission');

        parent::__construct($attributes);
    }
}
