<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
// ☆ログイン中のユーザーの role が 1・2・3のどれか（講師）になっているか確認↓設定↓
        if (in_array(auth()->user()->role, [1, 2, 3])) {

        return $next($request);
    }
    abort(403);
}
}
