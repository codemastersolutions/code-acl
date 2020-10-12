<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;

trait SetUpPivot
{
    /** @var array|null */
    private static $modelData;
    /** @var string|null */
    private static $conn;
    /** @var string|null */
    private static $customTableName;

    public function setUp()
    {
        if (empty(self::$modelData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$conn = config('laravel-acl.defaults.connection');

        if (! self::$conn) {
            self::$conn = $this->getConnectionName();
        }

        self::$customTableName = self::$modelData['table'];

        if (! self::$customTableName) {
            self::$customTableName = $this->getTable();
        }

        $this->setTable(self::$customTableName);
        $this->setConnection(self::$conn);
    }
}
