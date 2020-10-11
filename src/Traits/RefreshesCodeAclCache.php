<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcel\CodeAclRegister;

trait RefreshesCodeAclCache
{
    public static function bootRefreshesCodeAcelCache()
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
