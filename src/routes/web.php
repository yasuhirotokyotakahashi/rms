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

    return view('thanks');
})->middleware(['auth'])->name('thanks');
Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop_id}', [ShopController::class, 'show'])->name('shops.show'); // 店舗詳細
Route::post('/shops/search', [ShopController::class, 'search'])->name('shops.search');


Route::middleware(['auth'])->group(function () {

    Route::get('/mypage', [UserController::class, 'index']);
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index'); // 予約一覧
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store'); // 予約を作成
    Route::get('/done', [ReservationController::class, 'thanks'])->name('reservation.thanks'); // 予約完了画面
    Route::get('/reservations/{reservationId}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservationId}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservationId}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    //お気に入り機能
    Route::post('/favorites/{shop}', [FavoritesController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{shop}', [FavoritesController::class, 'destroy'])->name('favorites.destroy');
    // レビュー機能
    Route::get('/shops/{shop_id}/review', [ReviewController::class, 'showReviewForm'])->name('reviews.showForm');
    Route::post('/shops/{shop_id}/review', [ReviewController::class, 'store'])->name('reviews.submit');
    Route::get('/shops/{shop_id}/reviews', [ReviewController::class, 'showReviews'])->name('reviews.show');
});


Route::middleware(['admin.middleware'])->group(function () {
    Route::get('/assign-role', [AdminController::class, 'showAssignRoleForm'])->name('showAssignShopRoleForm');
    Route::post('/assign-role', [AdminController::class, 'assignRole'])->name('assignRole');
    Route::post('/unassign-role', [AdminController::class, 'unassignRoleFromUser'])->name('unassignRoleFromUser');
    Route::get('/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('/create', [ShopController::class, 'store'])->name('shops.store');
});

Route::middleware(['shop.representative'])->group(function () {
    Route::get('/representative', [RepresentativeController::class, 'showShopInfo'])->name('representative.index');
    Route::get('/representative/edit', [RepresentativeController::class, 'editShopInfo'])->name('representative.edit');
    Route::get('/reservations/{id}', [RepresentativeController::class, 'show'])->name('reservations.show');
    Route::put('/update-shop/{shopId}', [RepresentativeController::class, 'updateShopInfo'])->name('update-shop');
    Route::post('/shops/{shop_id}', [ShopController::class, 'update'])->name('shops.update');
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('/send-notification', [EmailController::class, 'sendNotification'])->name('sendNotification');

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout', [PaymentController::class, 'showCheckoutForm'])->name('checkout.form');
    Route::post('/checkout', [PaymentController::class, 'processCheckout'])->name('checkout.process');
});
