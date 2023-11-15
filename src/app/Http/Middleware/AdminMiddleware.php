<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserShopRole;

class AdminMiddleware
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
        // return $next($request);
        $user = Auth::user();

        // ユーザーに関連付けられた店舗と役割を取得
        $userShopRoles = UserShopRole::where('user_id', $user->id)->get();
        foreach ($userShopRoles as $userShopRole) {
            // $userShopRole->shop で店舗にアクセス
            // $userShopRole->role で役割にアクセス
            if ($userShopRole->role->name == 'Admin') {
                return $next($request);
            } else {
                abort(404);
            }
        }
    }
}
