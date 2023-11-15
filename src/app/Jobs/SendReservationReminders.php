<?php

namespace App\Jobs;

use App\Mail\ReservationReminder;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // 現在の日時を取得し、当日の0時0分0秒に設定
        $today = Carbon::now()->startOfDay();

        // 当日の予約を取得
        $reservations = Reservation::whereDate('date', $today)->get();

        // 予約があればメールを送信
        if ($reservations->isNotEmpty()) {
            // メールを送信する処理を実装する

            foreach ($reservations as $reservation) {
                Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
            }
        }
    }
}
