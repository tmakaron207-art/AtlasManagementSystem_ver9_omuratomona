<?php

// ☆投稿登録用（投稿タイトル投稿本文など）バリデーション設定用FormRequest
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
// ☆投稿編集用のバリデーション設定↓
        return [
            'post_title'=>['required','string','max:100'],
            'post_body'=>['required','string','max:2000'],
            //
        ];
    }

    // ☆バリデーションエラーメッセージ日本語バージョン↓
    public function messages()
{
    return [
        'post_title.required'=>'タイトルを入力してください',
        'post_title.max:100'=>'タイトルは100文字以下で入力してください',
        'post_body.required'=>'本文を入力してください',
        'post_body.max:2000'=>'本文は2000文字以下で入力してください',
    ];
}
}
