<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use Ramsey\Uuid\Uuid;

trait SetUpModel
{
    /** @var array|null */
    private static $modelData;
    /** @var string|null */
    private static $conn;
    /** @var string|null */
    private static $customKeyName;
    /** @var string|null */
    private static $customTableName;

    public function setUp()
    {
        if (empty(self::$modelData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$conn = config('code-acl.defaults.connection');

        if (! self::$conn) {
            self::$conn = $this->getConnectionName();
        }

        self::$customKeyName = self::$modelData['primary_key']['name'];

        if (! self::$customKeyName) {
            self::$customKeyName = $this->getKeyName();
        }

        self::$customTableName = self::$modelData['table'];

        if (! self::$customTableName) {
            self::$customTableName = $this->getTable();
        }

        $this->setTable(self::$customTableName);
        $this->setKeyName(self::$customKeyName);
        $this->setConnection(self::$conn);

        if (self::$modelData['primary_key']['type'] === 'uuid') {
            $this->setKeyType('string');
            $this->setIncrementing(false);
        }
    }

    public static function boot()
    {
        parent::boot();
        self::creating(/**
         * @param $model
         */
            function ($model) {
                if (self::$modelData['primary_key']['type'] === 'uuid') {
                    $model->{self::$customKeyName} = (string)Uuid::uuid4();
                }
            }
        );
    }
}
