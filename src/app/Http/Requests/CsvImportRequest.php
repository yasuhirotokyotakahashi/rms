<?php

namespace App\Http\Requests;

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
}
