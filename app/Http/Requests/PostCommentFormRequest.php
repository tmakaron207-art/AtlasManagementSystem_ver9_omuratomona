<?php

// ☆コメント登録用バリデーション設定用FormRequest
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostCommentFormRequest extends FormRequest
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
        return [

// ☆コメント欄のバリデーション設定↓
            'comment'=>[
                'required',
                'string',
                'max:250',
            ],
            //
        ];
    }


    // ☆バリデーションエラーメッセージ日本語バージョン↓
    public function messages()
{
    return [
        'comment.required' => '※コメントが未入力です',
        'comment.max' => '※250文字以下で入力して下さい',

    ];
}

}
