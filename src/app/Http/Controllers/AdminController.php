<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvImportRequest;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\UserShopRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function showAssignRoleForm()
    {
        // ユーザーと役割のデータを取得
        $users = User::all(); // ユーザーの一覧を取得
        $roles = Role::all(); // 役割の一覧を取得
        $shops = Shop::all();

        $allReservations = Reservation::with('shop')->get();

        // 店舗代表者の役割のIDを取得
        $representativeRole = Role::where('name', 'Representative')->first();
        $representativeRoleId = $representativeRole->id;

        // 全てのユーザーとそれに割り当てられた役割情報を取得
        $allUsers = UserShopRole::with('user', 'role')->get();

        return view('assign-role', compact('allReservations', 'allUsers', 'users', 'roles', 'shops', 'representativeRoleId'));
    }


    public function assignRole(Request $request)
    {
        $userId = $request->input('userId');
        $shopId = $request->input('shopId');
        $roleId = $request->input('roleId');

        // ユーザーを User モデルから取得
        $user = User::find($userId);

        // 店舗を Shop モデルから取得
        $shop = Shop::find($shopId);

        // 役割を Role モデルから取得
        $role = Role::find($roleId);

        if ($user && $shop && $role) {
            // UserShopRole モデルを使用して関連付けを作成
            $userShopRole = new UserShopRole();
            $userShopRole->user_id = $userId;
            $userShopRole->shop_id = $shopId;
            $userShopRole->role_id = $roleId;
            $userShopRole->save();

            $message = $user->name . ' に' . $shop->name  . 'の店舗代表者' . ' 役割が割り当てられました';

            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'ユーザー、店舗、または役割が見つかりません');
        }
    }

    public function unassignRoleFromUser(Request $request)
    {
        $userId = $request->input('userId');
        $roleId = $request->input('roleId');

        // ユーザーを User モデルから取得
        $user = User::find($userId);

        // 役割を Role モデルから取得
        $role = Role::find($roleId);

        if ($user && $role) {
            // User モデルと Role モデルの関連付けを削除
            $user->roles()->detach($role->id);

            $message = $user->name . ' の ' . '役職が取り消されました';

            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'ユーザーまたは役職が見つかりません');
        }
    }

    public function index()
    {
        return view('admin.index');
    }

    public function csv()
    {
        return view('csv.index');
    }

    public function run(CsvImportRequest $request)
    {
        try {
            // CSVの処理
            $shops = $request->processCsv();

            // アップロードしたCSVファイル内での重複チェック
            if ($shops->duplicates()->count() > 0) {
                throw new Exception("Error: 重複する行があります。");
            }

            // 既存データとの重複チェック
            $duplicateItem = DB::table('shops')->whereIn('id', $shops->pluck('id'))->first();
            if ($duplicateItem) {
                throw new Exception("Error: idの重複: " . $duplicateItem->id);
            }

            // バリデーションを実行
            $validator = Validator::make($shops->toArray(), [
                '*.name' => 'required|string|max:50',
                '*.address_id' => 'required|in:東京都,大阪府,福岡県',
                '*.genre_id' => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                '*.description' => 'required|string|max:400',
                '*.image_path' => [
                    'required',
                    'url',
                    function ($attribute, $value, $fail) {
                        $validExtensions = ['png', 'jpeg'];
                        $extension = pathinfo(parse_url($value, PHP_URL_PATH), PATHINFO_EXTENSION);
                        if (!in_array(strtolower($extension), $validExtensions)) {
                            $fail('URL末尾の画像拡張子が .png, .jpeg のいずれかである必要があります。');
                        }
                    },
                ],
            ], [
                '*.name.required' => '店舗名は必須です。',
                '*.name.max' => '店舗名は50文字以内で入力してください。',
                '*.address_id.required' => '所在地は必須です。',
                '*.address_id.in' => '所在地は東京都、大阪府、または福岡県の中から指定してください',
                '*.genre_id.required' => 'ジャンルは必須です。',
                '*.genre_id.in' => 'ジャンルは寿司、焼肉、イタリアン、居酒屋またはラーメンの中から指定してください',
                '*.description.required' => '説明文は必須です。',
                '*.description.string' => '説明文は文字列で入力してください。',
                '*.description.max' => '説明文は400文字以内で入力してください。',
                '*.image_path.required' => '画像パスは必須です。',
                '*.image_path.url' => '画像パスは有効なURLである必要があります。',
            ]);

            // バリデーションエラーがある場合
            if ($validator->fails()) {
                // エラーメッセージを取得
                $errors = $validator->errors()->toArray();

                // インデックスを追加してエラーメッセージを再構築
                $indexedErrors = [];
                foreach ($errors as $key => $errorMessages) {
                    list($rowIndex, $field) = explode('.', $key); // '1.name' のようなキーからインデックスとフィールド名を取得
                    foreach ($errorMessages as $errorMessage) {
                        // エラーメッセージに行番号とフィールド名を追加
                        $indexedErrors[] =  ($rowIndex + 1) . "行目のデータでエラー箇所が見つかりました。" . "$errorMessage";
                    }
                }

                // エラーメッセージをセッションに保存して入力フォームに戻る
                return redirect()->back()->withErrors($indexedErrors)->withInput();
            }

            // 都道府県名と対応する数値のマッピング
            $prefectureMap = [
                '東京都' => 1,
                '大阪府' => 2,
                '福岡県' => 3,
            ];

            // ジャンル名と対応する整数値のマッピング
            $genreMap = [
                '寿司' => 1,
                '焼肉' => 2,
                '居酒屋' => 3,
                'イタリアン' => 4,
                'ラーメン' => 5,
            ];

            // $shops コレクションを変換
            $shops = $shops->map(function ($shop) use ($prefectureMap, $genreMap) {
                // 都道府県名を数値IDに変換
                $prefectureName = $shop['address_id'];
                if (!array_key_exists($prefectureName, $prefectureMap)) {
                    throw new \Exception('無効な都道府県名です。');
                }
                $shop['address_id'] = $prefectureMap[$prefectureName];

                // ジャンル名を数値IDに変換
                $genreName = $shop['genre_id'];
                if (!array_key_exists($genreName, $genreMap)) {
                    throw new \Exception('無効なジャンルです。');
                }
                $shop['genre_id'] = $genreMap[$genreName];

                return $shop;
            });

            // データベースに挿入
            DB::table('shops')->insert($shops->toArray());

            // 成功時のリダイレクト
            return redirect()->route('csv.csv')->with('success', '店舗が登録されました');
        } catch (Exception $e) {
            // エラーメッセージをセッションに保存して入力フォームに戻る
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
