<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserCook {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if ($request->user()->type === "EC" || $request->user()->type === "EM") {
            return $next($request);
        }
        return abort("403", "You're not allowed to access this page.");
    }
}
