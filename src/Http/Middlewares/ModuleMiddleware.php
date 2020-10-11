<?php

namespace CodeMaster\CodeAcl\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use CodeMaster\CodeAcl\Exceptions\UnauthorizedException;

class ModuleMiddleware
{
    public function handle($request, Closure $next, $module, $guard = null)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $modules = is_array($module)
            ? $module
            : explode('|', $module);

        if (! Auth::user()->hasAnyModule($modules)) {
            throw UnauthorizedException::forModules($modules);
        }

        return $next($request);
    }
}
