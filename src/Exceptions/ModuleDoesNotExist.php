<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class ModuleDoesNotExist extends InvalidArgumentException
{
    /**
     *
     * @param string|int $moduleId
     */
    public static function withId($moduleId)
    {
        return new static("There is no module with id `{$moduleId}`.");
    }

    /**
     *
     * @param string $moduleName
     */
    public static function withName($moduleName)
    {
        return new static("There is no module with name `{$moduleName}`.");
    }

    /**
     *
     * @param string $moduleSlug
     */
    public static function withSlug($moduleSlug)
    {
        return new static("There is no module with slug `{$moduleSlug}`.");
    }
}
