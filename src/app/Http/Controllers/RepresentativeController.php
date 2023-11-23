<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Shop;
use App\Models\UserShopRole;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RepresentativeController extends Controller
{
    //

    public function showShopInfo()
    {
        $shops = Shop::all();

        // ログイン中の代表者のユーザーIDを取得
        $userId = auth()->user()->id;

        // 代表者に関連付けられた店舗IDを取得
        $shopIds = UserShopRole::where('user_id', $userId)->pluck('shop_id')->toArray();

        // 代表者に関連付けられた店舗の予約情報を取得
        $reservations = Reservation::whereIn('shop_id', $shopIds)->get();

        // Check if reservations exist
        if ($reservations->isEmpty()) {
            return view('admin.shops.create', compact('shops'))->with('error', 'No reservations found.');
        }

        return view('admin.shops.index', compact('shops', 'reservations'));
    }
    public function editShopInfo()
    {
        $shops = Shop::all();

        // ログイン中の代表者のユーザーIDを取得
        $userId = auth()->user()->id;

        // 代表者に関連付けられた店舗IDを取得
        $shopIds = UserShopRole::where('user_id', $userId)->pluck('shop_id')->toArray();

        // 代表者に関連付けられた店舗の予約情報を取得
        $reservations = Reservation::whereIn('shop_id', $shopIds)->get();

        // Check if reservations exist
        if ($reservations->isEmpty()) {
            return view('admin.shops.create', compact('shops'))->with('error', 'No reservations found.');
        }

        return view('admin.shops.create', compact('shops', 'reservations'));
    }

    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return back()->with('error', '予約が見つかりません。');
        }
        // 予約詳細ページのURLを生成
        $reservationUrl = route('reservations.show', ['id' => $id]);

        // QRコードを生成
        $qrCode = QrCode::size(300)->generate($reservationUrl);

        return view('admin.shops.show', compact('reservation', 'qrCode', 'id'));
    }

    public function updateShopInfo(Request $request, $shopId)
    {
        // 店舗情報を特定し、リクエストからのデータで更新
        $shop = Shop::find($shopId);

        if (!$shop) {
            return back()->with('error', '店舗が見つかりません。');
        }

        $shop->name = $request->input('name');
        $shop->description = $request->input('description');

        // 画像のアップロードを処理
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->storeAs('images', $image->getClientOriginalName(), 'public');
            $shop->image_path = $imagePath;
        }

        $shop->save();

        return back()->with('success', '店舗情報が更新されました。');
    }
}
