<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class PermissionDoesNotExist extends InvalidArgumentException
{
    /**
     *
     * @param string|int $permissionId
     */
    public static function withId($permissionId)
    {
        return new static("There is no permission with id `{$permissionId}`.");
    }

    /**
     *
     * @param string $permissionName
     */
    public static function withName($permissionName)
    {
        return new static("There is no permission with name `{$permissionName}`.");
    }

    /**
     *
     * @param string $permissionSlug
     */
    public static function withSlug($permissionSlug)
    {
        return new static("There is no permission with slug `{$permissionSlug}`.");
    }
}
