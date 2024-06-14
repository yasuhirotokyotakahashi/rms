<?php

namespace App\Http\Requests;

use App\Models\Shop;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

class CsvImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'csvFile' => 'required|file|mimes:csv,txt|max:2048', // CSVファイルのバリデーション
        ];
    }

    public function messages()
    {
        return [
            'csvFile.required' => 'CSVファイルの取得に失敗しました。',
            'csvFile.mimes' => '不適切な拡張子です。',
            'csvFile.max' => 'ファイルサイズが大きすぎます。',
        ];
    }

    public function processCsv()
    {
        // CSVファイルを保存
        $newCsvFileName = $this->csvFile->getClientOriginalName();
        $csvFilePath = $this->csvFile->storeAs('public/csv', $newCsvFileName);

        // 保存したCSVファイルの取得
        $csv = Storage::disk('local')->get($csvFilePath);
        $csv = str_replace(array("\r\n", "\r"), "\n", $csv);
        $csvLines = explode("\n", $csv);

        // テーブルとCSVファイルのヘッダーの比較
        $shop = new Shop(); // Shopモデルのインスタンスを作成
        $header = collect($shop->csvHeader());
        $uploadedHeader = collect(explode(",", array_shift($csvLines)));
        if ($header->count() !== $uploadedHeader->count()) {
            throw new Exception('Error:ヘッダーが一致しません');
        }

        // 連想配列のコレクションを作成
        $shops = collect($csvLines)->map(function ($oneRecord) use ($header) {
            return $header->combine(explode(",", $oneRecord));
        });

        return $shops;
    }
}
