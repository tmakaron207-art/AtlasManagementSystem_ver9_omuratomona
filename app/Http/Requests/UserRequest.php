<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

    // ☆リクエストデータ（生年月日）を加工、前処理用メソッド
    public function prepareForValidation(){
        $this -> merge([
            'birth_day' => $this->old_year.'-'.
            $this->old_month.'-'.
            $this->old_day,

        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
// ☆項目ごとのルール記入↓
        'over_name'=>'required|string|max:10',
        'under_name'=>'required|string|max:10',

        'over_name_kana'=>'required|string|regex:/^[ァ-ヶー]+$/u|max:30',
        'under_name_kana'=>'required|string|regex:/^[ァ-ヶー]+$/u|max:30',

        'mail_address'
        =>['required',
        'email',
        'unique:users,mail_address',
        'max:100',],

        'sex'
        =>['required',
        'in:1,2,3',],

        'role'
        =>['required',
        'in:1,2,3,4',],


        'old_year'=>['required',],
        'old_month'=>['required',],
        'old_day'=>['required',],

        'birth_day'
        =>['required',
        'date_format:Y-m-d',
        'after_or_equal:2000-01-01',
        'before_or_equal:today',],

        'password'
        =>['required',
        'string',
        'min:8',
        'max:30',
        'confirmed'],
        ];
    }

// ☆バリデーションエラーメッセージ日本語バージョン↓
    public function messages()
{
    return [
        'over_name.required' => '※性が未入力です',
        'over_name.max' => '※10文字以下で入力して下さい',
        'under_name.required' => '※名が未入力です',
        'under_name.max' => '※10文字以下で入力して下さい',
        'over_name_kana.required' => '※性のフリガナが未入力です',
        'over_name_kana.max' => '※30文字以下で入力して下さい',
        'over_name_kana.regex' => '※カタカナのみで入力して下さい',
        'under_name_kana.required' => '※名のフリガナが未入力です',
        'under_name_kana.max' => '※30文字以下で入力して下さい',
        'under_name_kana.regex' => '※カタカナのみで入力して下さい',
        'mail_address.required' => '※メールアドレスが未入力です',
        'mail_address.unique' => '※既に登録済みのアドレスです',
        'mail_address.max' => '※100文字以下で入力して下さい',
        'mail_address.email' => '※メール形式で入力して下さい',
        'sex.required' => '※性別が未選択です',
        'sex.in' => '※正しい性別を選択してください',
        'role.required' => '※権限が未選択です',
        'role.in' => '※正しい権限を選択してください',
        'birth_day.required' => '※生年月日が未入力です',
        'birth_day.after_or_equal' => '※2000年1月1日からで入力して下さい',
        'birth_day.before_or_equal' => '※今日までで入力して下さい',
        'password.required' => '※パスワードが未入力です',
        'password.min' => '※8文字以上で入力して下さい',
        'password.max' => '※30文字以下で入力して下さい',
        'password.confirmed' => '※確認用と違います',
    ];
}


}
