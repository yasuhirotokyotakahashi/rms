<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{


    public function create()
    {
        return view('payment.create');
    }

    public function store(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.stripe_secret_key'));

        try {
            \Stripe\Charge::create([
                'source' => $request->stripeToken,
                'amount' => 1000,
                'currency' => 'jpy',
            ]);
        } catch (Exception $e) {
            // エラーが発生した場合、エラー情報をdd()で確認

            return back()->with('flash_alert', '決済に失敗しました！(' . $e->getMessage() . ')');
        }
        return back()->with('status', '決済が完了しました！');
    }
}
