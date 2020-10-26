<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class SystemDoesNotExist extends InvalidArgumentException
{
    /**
     *
     * @param string|int $systemId
     */
    public static function withId($systemId)
    {
        return new static("There is no system with id `{$systemId}`.");
    }

    /**
     *
     * @param string $systemName
     */
    public static function withName($systemName)
    {
        return new static("There is no system with name `{$systemName}`.");
    }

    /**
     *
     * @param string $systemSlug
     */
    public static function withSlug($systemSlug)
    {
        return new static("There is no system with slug `{$systemSlug}`.");
    }
}
