<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{

    public function showReservationForm()
    {
        return view('reservation.create');
    }

    public function showReservationList()
    {
        return view('reservation.index');
    }


    public function store(ReservationRequest $request)
    {
        // バリデーションは ReservationRequest クラスで自動的に実行される

        if ($request->validated()) {
            // バリデーションに成功した場合の処理
            $reservation_date = $request->input('reservation_date');
            $reservation_time = $request->input('reservation_time');
            $party_size = $request->input('party_size');
            $shop_id = $request->input('shop_id');
            $user_id = auth()->id(); // ログインユーザーのIDを取得

            // 予約情報を新しいモデルインスタンスにセット
            $reservation = new Reservation();
            $reservation->date = $reservation_date;
            $reservation->time = $reservation_time;
            $reservation->guests = $party_size;
            $reservation->shop_id = $shop_id;
            $reservation->user_id = $user_id; // ログインユーザーのIDをセット

            // データベースに保存
            $reservation->save();

            return redirect()->route('reservation.thanks');
        } else {
            // バリデーションに失敗した場合、エラーメッセージを表示する
            return redirect()->back()->withInput()->withErrors($request->errors());
        }
    }

    public function thanks()
    {
        return view('reservation.thanks');
    }

    public function edit($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        return view('reservation.edit', ['reservation' => $reservation]);
    }

    public function update(Request $request, $reservationId)
    {
        // バリデーションを追加することをおすすめします

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->guests = $request->input('guests');
        $reservation->save();

        // 更新後のリダイレクトなどの処理を追加

        return redirect('/mypage')->with('success', '予約が変更されました。');
    }
    public function destroy($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->delete();

        // 削除後のリダイレクトなどの処理を追加

        return redirect('/mypage')->with('success', '予約が削除されました。');
    }
}
