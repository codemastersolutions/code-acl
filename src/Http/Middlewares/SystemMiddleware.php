<?php

namespace CodeMaster\CodeAcl\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use CodeMaster\CodeAcl\Exceptions\UnauthorizedException;

class SystemMiddleware
{
    public function handle($request, Closure $next, $system, $guard = null)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $systems = is_array($system)
            ? $system
            : explode('|', $system);

        if (! Auth::user()->hasAnySystem($systems)) {
            throw UnauthorizedException::forSystem($systems);
        }

        return $next($request);
    }
}
