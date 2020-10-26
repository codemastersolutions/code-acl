<?php

namespace CodeMaster\CodeAcl\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use CodeMaster\CodeAcl\Exceptions\UnauthorizedException;

class SystemMiddleware
{
    public function handle($request, Closure $next, $system)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $systems = is_array($system)
            ? $system
            : explode('|', $system);

        if (! Auth::user()->hasAnySystem($systems)) {
            throw UnauthorizedException::forSystems($systems);
        }

        return $next($request);
    }
}
