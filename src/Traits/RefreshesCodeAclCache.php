<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\CodeAclRegister;

trait RefreshesCodeAclCache
{
    public static function bootRefreshesCodeAclCache()
    {
        static::saved(function () {
            app(CodeAclRegister::class)->forgetCachedPermissions();
        });

        static::updated(function () {
            app(CodeAclRegister::class)->forgetCachedPermissions();
        });

        static::deleted(function () {
            app(CodeAclRegister::class)->forgetCachedPermissions();
        });
    }
}
