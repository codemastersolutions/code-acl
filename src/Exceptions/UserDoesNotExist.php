<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class UserDoesNotExist extends InvalidArgumentException
{
    /**
     *
     * @param string|int $userId
     */
    public static function withId($userId)
    {
        return new static("There is no user with id `{$userId}`.");
    }

    /**
     *
     * @param string $userName
     */
    public static function withName($userName)
    {
        return new static("There is no user with name `{$userName}`.");
    }
}
