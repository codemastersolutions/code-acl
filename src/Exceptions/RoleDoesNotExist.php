<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class RoleDoesNotExist extends InvalidArgumentException
{
    /**
     *
     * @param string|int $roleId
     */
    public static function withId($roleId)
    {
        return new static("There is no role with id `{$roleId}`.");
    }

    /**
     *
     * @param string $roleName
     */
    public static function withName($roleName)
    {
        return new static("There is no role with name `{$roleName}`.");
    }

    /**
     *
     * @param string $roleSlug
     */
    public static function withSlug($roleSlug)
    {
        return new static("There is no role with slug `{$roleSlug}`.");
    }
}
