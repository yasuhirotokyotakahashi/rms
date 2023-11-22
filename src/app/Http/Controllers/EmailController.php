<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    //
    public function sendNotification(Request $request)
    {
        $user = User::find($request->input('user_id')); // メールを送信するユーザーを取得
        if (!$user) {
            return redirect()->back()->with('error', 'ユーザーが見つかりません');
        }

        $email = $user->email; // ユーザーのメールアドレスを取得


        // お知らせメールを送信
        Mail::to($email)->send(new ConfirmationMail);

        return redirect()->back()->with('success', 'お知らせメールを送信しました');
    }
}
