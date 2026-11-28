<?php
// ☆日付ごとのクラス名や表示判定

namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

// ☆ログインしている自分のIDを取得するためuse宣言追加↓（参加した部の表示用）
use Illuminate\Support\Facades\Auth;


class CalendarWeekDay{
  protected $carbon;

// ☆カレンダーの日付を $this->carbon に入れておく役割
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

// ☆日付を「左側の日付が、右側の日付以下か」を判定するメソッド↓
function isPastOrToday(){
    return $this->carbon->lessThanOrEqualTo(Carbon::today());
}

// ☆自分がその日に参加していた部を調べるメソッド↓
function getletsPart(){
    // ☆カレンダーで処理している日の予約データを絞り込む準備↓
    $reserve = ReserveSettings::where('setting_reserve', $this->carbon->format('Y-m-d'))
    // ☆usersとの紐づきがある予約に絞り、紐づいているユーザーのIDがログイン中の自分と一致するか調べる↓
    ->whereHas('users', function($query) {
            $query->where('users.id', Auth::id());
            })
    // ☆条件に一致する最初の1件のみ取得する↓
    ->first();

    // ☆どこの部に入っているのか取得するため、まず予約データがあるかないかを判断する設定↓
    if($reserve){
        // ☆取得した $reserve の中にある setting_part を取り出したのをreturnで返す設定↓
        return $reserve->setting_part;
}
        return null;

}

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

// ☆1部・2部・3部の予約枠を取得
  function dayPartCounts($ymd){
    $html = [];

    $parts = [
        '1' => '1部',
        '2' => '2部',
        '3' => '3部',
    ];

    $html[] = '<div class="text-left">';

    foreach($parts as $part => $partName){

// ☆予約者一覧を取得
        $reserve = ReserveSettings::with('users')
            ->where('setting_reserve', $ymd)
            ->where('setting_part', $part)
            ->first();

        if($reserve){
            // 予約している人数を取得
            $reserveCount = $reserve->users->count();

            // 予約詳細画面へのURLを作成
            $url = route('calendar.admin.detail', [
                'date' => $ymd,
                'part' => $part,
            ]);

            $html[] = '<p class="day_part m-0 pt-1">';
            $html[] = '<a href="' . $url . '">';
            $html[] = $partName . '（' . $reserveCount . '人）';
            $html[] = '</a>';
            $html[] = '</p>';
        }
    }

    $html[] = '</div>';

    return implode("", $html);
  }


  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
