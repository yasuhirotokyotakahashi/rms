<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailTestController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\RepresentativeReservationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/thanks', function () {
    // ここに /home にアクセスされた際の処理を追加
    return view('thanks'); // 例: home.blade.php テンプレートを表示する
})->middleware(['auth'])->name('thanks');
Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop_id}', [ShopController::class, 'show'])->name('shops.show'); // 店舗詳細
Route::post('/shops/search', [ShopController::class, 'search'])->name('shops.search');


Route::middleware(['auth'])->group(function () {
    // 以下に `auth` ミドルウェアを適用したいルートを追加



    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index'); // 予約一覧
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store'); // 予約を作成
    Route::get('/done', [ReservationController::class, 'thanks'])->name('reservation.thanks'); // 予約完了画面
    Route::get('/reservations/{reservationId}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservationId}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservationId}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::post('/favorites/{shop}', [FavoritesController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{shop}', [FavoritesController::class, 'destroy'])->name('favorites.destroy');

    // レビュー投稿フォームを表示
    Route::get('/shops/{shop_id}/review', [ReviewController::class, 'showReviewForm'])->name('reviews.showForm');

    // レビューを投稿
    Route::post('/shops/{shop_id}/review', [ReviewController::class, 'store'])->name('reviews.submit');

    // レビューを表示
    Route::get('/shops/{shop_id}/reviews', [ReviewController::class, 'showReviews'])->name('reviews.show');
});

Route::get('/mypage', [UserController::class, 'index']);

Route::get('/create', [ShopController::class, 'create'])->name('shops.create');
Route::post('/create', [ShopController::class, 'store'])->name('shops.store');
// Route::get('/shops/{shop_id}/edit', [ShopController::class, 'edit'])->name('shops.edit');
Route::post('/shops/{shop_id}', [ShopController::class, 'update'])->name('shops.update');
// Route::get('/shop/{shopId}/reservations', [ReservationController::class, 'showShopReservations'])
//     ->name('shop.reservations');

Route::middleware(['admin.middleware'])->group(function () {
    Route::get('/assign-shop-role', [AdminController::class, 'showAssignShopRoleForm'])->name('showAssignShopRoleForm');
    Route::post('/assign-shop-representative', [AdminController::class, 'assignShopRepresentative'])->name('assignShopRepresentative');

    Route::post('/unassign-role', [AdminController::class, 'unassignRoleFromUser'])->name('unassignRoleFromUser');
});

Route::middleware(['shop.representative'])->group(function () {
});

Route::get('/create2', [RepresentativeController::class, 'showShopReservations'])->name('shops.create2');
Route::get('/reservations/{id}', [RepresentativeController::class, 'show'])->name('reservations.show');
Route::put('/update-shop/{shopId}', [RepresentativeController::class, 'updateShop'])
    ->name('update-shop');




// Route::group(['middleware' => ['auth', 'shop.representative']], function () {
//     // ここに店舗関連のルートを定義
// });

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/send-notification', [EmailController::class, 'sendNotification'])->name('sendNotification');


Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/create3', [PaymentController::class, 'create'])->name('create');
    Route::post('/store3', [PaymentController::class, 'store'])->name('store');
});

Route::get('/test', [ReservationController::class, 'test'])->name('test'); // 予約一覧
