<?php

namespace CodeMaster\CodeAcl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodeMaster\CodeAcl\Skeleton\SkeletonClass
 */
class CodeAcl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'code-acl';
    }
}
