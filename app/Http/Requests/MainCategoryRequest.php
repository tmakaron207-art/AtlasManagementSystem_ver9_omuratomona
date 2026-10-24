<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryRequest extends FormRequest
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
// ☆メインカテゴリーのバリデーション↓
        return [
            'main_category_name' => ['required','string','max:100','unique:sub_categories,sub_category',],
            //
        ];
    }

    // ☆バリデーションエラーメッセージ日本語バージョン↓
    public function messages()
{
    return [
        'main_category_name.required'=>'メインカテゴリーを入力してください',
        'main_category_name.max'=>'メインカテゴリーは100文字以内で入力してください',
        'main_category_name.unique'=>'この名前のメインカテゴリーは既に登録されています',

    ];
}

}
