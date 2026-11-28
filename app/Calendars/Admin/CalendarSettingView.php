<?php
//  ☆予約設定画面に表示するカレンダーを作り、各日付に1部・2部・3部の予約可能人数を入力できるようにするクるクス

namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarSettingView{
  private $carbon;

// ☆new CalendarSettingView() が実行されたとき、自動的に呼び出される処理
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

// ☆レンダーのタイトルを作るメソッド。
  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

// ☆render() は、カレンダーのHTMLを作るメソッド
  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
// ☆getWeeks() で週を取得する
    $weeks = $this->getWeeks();

// ☆今週ごとに取り出す
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
// ☆今度は週の中の日付を1日ずつ取り出し
      foreach($days as $day){
// ☆$startDay	表示している月の1日
        $startDay = $this->carbon->format("Y-m-01");
// ☆$toDay	現在の日付
        $toDay = $this->carbon->format("Y-m-d");


// ☆その日付が1日から現在の日付までの範囲に入っているか確認
    //   if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
// ☆上記のif文から下記に変更。今日以前か判定できるように設定↓
    if($day->isPastOrToday()){

// ☆範囲内なら past-day というクラスを付ける
        $html[] = '<td class="past-day border">';

// ☆まず自分が参加していた部を取得して、$part に保存↓
        $part =  $day->getletsPart();


// ☆参加しているかしてないかをifで判断する設定↓
    if($part === null){

        // ☆参加していなければ「受付終了」を表示↓
        $html[] ='受付終了';

    }else{

         // ☆参加していれば参加していた部を表示↓
            $html[] = $part . '部';
        }
// ☆過去の日側のtdを閉じる↓
    $html[] = '</td>';


        }else{
// ☆それ以外なら曜日などに応じたクラスを付ける
          $html[] = '<td class="border '.$day->getClassName().'">';

        // ☆日付そのものをHTMLとして表示する処理
        $html[] = $day->render();
        $html[] = '</td>';
        }

// ☆adjust-area というdivを作り、その日付が存在する場合に予約人数の入力欄を作る
        $html[] = '<div class="adjust-area">';
        if($day->everyDay()){
// ☆もう一度日付を確認、過去日・当日なのか、それ以降なのかで処理を分けている
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'" disabled></p>';
          }else{
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'"></p>';
          }
        }
        $html[] = '</div>';
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
// ☆フォームの送信先を指定
    $html[] = '<form action="'.route('calendar.admin.update').'" method="post" id="reserveSetting">'.csrf_field().'</form>';
    return implode("", $html);
  }


// ☆protected は、このクラス自身と継承したクラスから使えるという意味
  protected function getWeeks(){
// ☆週の情報を入れるための空の配列を用意
    $weeks = [];
// ☆firstOfMonth()：その月の初日を取得
    $firstDay = $this->carbon->copy()->firstOfMonth();
// ☆lastOfMonth()：その月の最終日を取得
    $lastDay = $this->carbon->copy()->lastOfMonth();
// ☆copy()：元の日付を変更せずに複製

// ☆CalendarWeek という別のクラスを使って、最初の週を作っている
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
// ☆月初の日付をコピー
    $tmpDay = $firstDay->copy()
// ☆7日進める
    ->addDay(7)
// ☆その週の月曜日に合わせる
    ->startOfWeek();

// ☆while は条件が成立している間、繰り返す処理。
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }

// ☆作成した週の配列を返し
    return $weeks;
  }
}
