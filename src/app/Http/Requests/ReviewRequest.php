<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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

            //
            'rating' => 'required', // ratingが必須であることを定義
            'comment' => 'required|max:400', // commentが400文字以下であることを定義
            'image' => 'required|image|mimes:jpeg,png|max:2048', // jpegまたはpng形式の画像で、最大2MB
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価は必須です。',
            'comment.required' => 'コメントを入力してください。',
            'comment.max' => 'コメントは400文字以内で入力してください。',
            'image.required' => '画像が必要です。',
            'image.image' => '有効な画像ファイルをアップロードしてください。',
            'image.mimes' => '画像はjpegまたはpng形式である必要があります。',
            'image.max' => '画像のサイズは最大2MBまでです。',
        ];
    }
}
