<?php
// ☆スクール予約のモデル

namespace App\Models\Calendars;

use Illuminate\Database\Eloquent\Model;

class ReserveSettings extends Model
{
    const UPDATED_AT = null;
    public $timestamps = false;

// ☆データベースにcreate() や update() などでまとめて保存・更新OKのカラムを指定する
    protected $fillable = [
    // ☆予約日
        'setting_reserve',
    // ☆予約の部（1部・2部・3部）
        'setting_part',
    // ☆予約可能人数
        'limit_users',
    ];

// ☆予約枠と予約したユーザーを結びつける部分
    // ☆$reserveSetting->usersでそのユーザーを取得できるように設定
    public function users(){
    // ☆多対多
        return $this->belongsToMany(
        // ☆関連付けるUserモデル
            'App\Models\Users\User',
        // ☆中間テーブル
            'reserve_setting_users',
        // ☆中間テーブルにある予約枠のID
            'reserve_setting_id',
        // ☆中間テーブルにあるユーザーのID
            'user_id')
        // ☆中間テーブルにある追加情報も取得できるようにする設定
            ->withPivot('reserve_setting_id', 'id');
    }
}
