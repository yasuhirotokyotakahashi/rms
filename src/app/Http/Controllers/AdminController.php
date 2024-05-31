<?php

namespace App\Http\Controllers;

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

    public function run(Request $request)
    {
        $shop = new Shop();
        // CSVファイルが存在するかの確認
        if ($request->hasFile('csvFile')) {
            //拡張子がCSVであるかの確認
            if ($request->csvFile->getClientOriginalExtension() !== "csv") {
                throw new Exception('不適切な拡張子です。');
            }
            //ファイルの保存
            $newCsvFileName = $request->csvFile->getClientOriginalName();
            $request->csvFile->storeAs('public/csv', $newCsvFileName);
        } else {
            throw new Exception('CSVファイルの取得に失敗しました。');
        }
        //保存したCSVファイルの取得
        $csv = Storage::disk('local')->get("public/csv/{$newCsvFileName}");
        // OS間やファイルで違う改行コードをexplode統一
        $csv = str_replace(array("\r\n", "\r"), "\n", $csv);
        // $csvを元に行単位のコレクション作成。explodeで改行ごとに分解
        $uploadedData = collect(explode("\n", $csv));

        // テーブルとCSVファイルのヘッダーの比較
        $header = collect($shop->csvHeader());
        $uploadedHeader = collect(explode(",", $uploadedData->shift()));
        if ($header->count() !== $uploadedHeader->count()) {
            throw new Exception('Error:ヘッダーが一致しませんわよ');
        }

        // 連想配列のコレクションを作成
        //combine 一方の配列をキー、もう一方を値として一つの配列生成。haederをキーとして、一つ一つの$oneRecordと組み合わせて、連想配列のコレクション作成
        try {
            $shops = $uploadedData->map(fn ($oneRecord) => $header->combine(collect(explode(",", $oneRecord))));
        } catch (Exception $e) {
            throw new Exception('Error:ヘッダーが一致しません');
        }

        // アップロードしたCSVファイル内での重複チェック
        if ($shops->duplicates()->count() > 0) {
            throw new Exception("Error:重複する行があります:");
        }

        // 既存データとの重複チェック.pluckでDBに挿入したい$itemsのidのみ抽出
        $duplicateItem = DB::table('shops')->whereIn('id', $shops->pluck('id'));
        if ($duplicateItem->count() > 0) {
            throw new Exception("Error:idの重複:" . $duplicateItem->shift()->id);
        }

        // 都道府県名と対応する数値のマッピングを定義します
        $prefectureMap = [
            '東京都' => 1,
            '大阪府' => 2,
            '福岡県' => 3,
            // 他の都道府県に対するマッピングも同様に定義します
        ];

        // $shops コレクションを変換します
        $shops = $shops->map(function ($shop) use ($prefectureMap) {
            // 都道府県名がマッピングに含まれているか確認します
            $prefectureName = $shop['address_id'];
            if (!array_key_exists($prefectureName, $prefectureMap)) {
                // 都道府県名がマッピングに含まれていない場合はエラーメッセージを返します
                throw new \Exception('無効な都道府県名です。');
            }

            // マッピングされた都道府県IDを取得します
            $prefectureId = $prefectureMap[$prefectureName];

            // 新しいキー 'address_id' を追加し、数値に変換した都道府県IDを代入します
            $shop['address_id'] = $prefectureId;

            // 変換したレコードを返します
            return $shop;
        });

        // ジャンル名と対応する整数値のマッピングを定義します
        $genreMap = [
            '寿司' => 1,
            '焼肉' => 2,
            '居酒屋' => 3,
            'イタリアン' => 4,
            'ラーメン' => 5,
            // 他のジャンルに対するマッピングも同様に定義します
        ];

        // $shops コレクションを変換します
        $shops = $shops->map(function ($shop) use ($genreMap) {
            // ジャンル名を整数値に変換します
            $genreName = $shop['genre_id']; // 仮にジャンル名のキーが 'genre_id' とする
            if (!array_key_exists($genreName, $genreMap)) {
                // 都道府県名がマッピングに含まれていない場合はエラーメッセージを返します
                throw new \Exception('無効なジャンルです。');
            }
            $genreId = $genreMap[$genreName];

            // 新しいキー 'genre_id' を追加し、整数値に変換したジャンルIDを代入します
            $shop['genre_id'] = $genreId;

            // 変換したレコードを返します
            return $shop;
        });

        // バリデーションを実行
        $validator = Validator::make($shops->toArray(), [
            '*.name' => 'required|string|max:50',
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
            '*.description.required' => '説明文は必須です。',
            '*.description.string' => '説明文は文字列で入力してください。',
            '*.description.max' => '説明文は400文字以内で入力してください。',
            '*.image_path.required' => '画像パスは必須です。',
            '*.image_path.url' => '画像パスは有効なURLである必要があります。',
        ]);

        // バリデーションエラーがある場合
        if ($validator->fails()) {
            // エラーメッセージをセッションに保存して入力フォームに戻る
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // バリデーションが成功した場合はデータベースへの挿入を行う
        DB::table('shops')->insert($shops->toArray());

        // 成功時のリダイレクト
        return redirect()->route('csv.csv')->with('success', '店舗が登録されました');
    }
}
