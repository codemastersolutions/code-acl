<?php

namespace CodeMaster\CodeAcl\Models;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Exceptions\UserDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;
use CodeMaster\CodeAcl\Traits\HasModules;
use CodeMaster\CodeAcl\Traits\HasRoles;
use CodeMaster\CodeAcl\Traits\HasSystems;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthorizableContract, AuthenticatableContract, UserContract
{
    use Authorizable, Authenticatable, HasRoles, HasModules, HasSystems;

    /** @var \Illuminate\Database\Eloquent\Model */
    private static $model;
    /** @var string|null */
    private static $modelClass;

    /**
     * Model constructor
     *
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$modelClass = config('code-acl.defaults.user');

        if (empty(self::$modelClass)) {
            throw UserModelNotFound::config('config/code-acl.php');
        }

        self::$model = app(self::$modelClass);

        $this->dispatchesEvents = config('code-acl.defaults.user_events');
        $this->setTable(self::$model->getTable());
        $this->setKeyName(self::$model->getKeyName());
        $this->setKeyType(self::$model->getKeyType());
        $this->setIncrementing(self::$model->getIncrementing());
        $this->setConnection(self::$model->getConnectionName());
        $this->timestamps = self::$model->usesTimestamps();

        parent::__construct($attributes);
    }

    /**
     * @inheritDoc
     */
    public static function findById($id): self
    {
        $system = self::find($id);

        if (! $system) {
            throw UserDoesNotExist::withId($id);
        }

        return $system;
    }

    /**
     * @inheritDoc
     */
    public static function findByName(string $name): self
    {
        $system = self::whereName($name)->first();

        if (! $system) {
            throw UserDoesNotExist::withName($name);
        }

        return $system;
    }
}
