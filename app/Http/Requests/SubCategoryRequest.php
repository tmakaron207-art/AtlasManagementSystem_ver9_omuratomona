<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
             // サブカテゴリー登録
            'sub_category_name' => ['required','string','max:100','unique:sub_categories,sub_category',],
            //
        ];
    }

        // ☆バリデーションエラーメッセージ日本語バージョン↓
    public function messages()
{
    return [
        'sub_category_name.required'=>'サブカテゴリーを入力してください',
        'sub_category_name.max'=>'サブカテゴリーは100文字以内で入力してください',
        'sub_category_name.unique'=>'この名前のサブカテゴリーは既に登録されています',

    ];
}
}
