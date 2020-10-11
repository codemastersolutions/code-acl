<?php

namespace CodeMaster\CodeAcl\Contracts;

interface Log {
    /**
     * @method static void error(string $message, array $context = [])
     * @method static void info(string $message, array $context = [])
     * @method static void success($level, string $message, array $context = [])
    */

    /**
     * @param string $message
     * @param array $context
     */
    public static function error(string $message, array $context = []);

    /**
     * @param string $message
     * @param array $context
     */
    public static function info(string $message, array $context = []);

    /**
     * @param string $message
     * @param array $context
     */
    public static function success(string $message, array $context = []);
}
