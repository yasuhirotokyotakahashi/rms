<?php

namespace App\Http\Middleware;

use App\Models\UserShopRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopRepresentativeMiddleware
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


        $user = Auth::user();

        // ユーザーに関連付けられた店舗と役割を取得
        $userShopRoles = UserShopRole::where('user_id', $user->id)->get();

        // リクエストから店舗IDを取得
        $shopId = $request->route('shop'); // または、URLパラメータやセッションから取得

        foreach ($userShopRoles as $userShopRole) {
            // $userShopRole->shop で店舗にアクセス
            // $userShopRole->role で役割にアクセス
            if ($userShopRole->role->name == 'Representative') {
                return $next($request);
            }
        }
        // アクセス者が代表者権を持たない場合は、エラーを返すかリダイレクトする
        return abort(403, 'アクセス権がありません');
    }
}
