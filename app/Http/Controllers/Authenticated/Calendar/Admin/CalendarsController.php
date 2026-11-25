<?php
// ☆講師用カレンダーコントローラー

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
// ☆予約枠の情報と実際の予約者数を結びつける
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

// ☆予約者の情報を取得する処理
    public function reserveDetail($date, $part){
        $reservePersons = ReserveSettings::with('users')
        ->where('setting_reserve', $date)
        ->where('setting_part', $part)
        ->get();
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    }

// ☆管理者用の予約設定カレンダー画面を表示する処理
    public function reserveSettings(){
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting',
    // ☆$calendar という変数をreserve_setting.Bladeファイルに渡す
        compact('calendar'));
    }


// ☆管理者がカレンダーで設定した予約日・予約枠・予約可能人数をデータベースに保存する処理
    // ☆フォームからデータを受け取る
    public function updateSettings(Request $request){
    // ☆フォームから送られてきた reserve_day という名前のデータを取得
        $reserveDays = $request->input('reserve_day');
        // ☆予約日を取り出す。$day：予約日。$part：何部か
        foreach($reserveDays as $day => $parts){
        // ☆さらに部ごとに取り出す。$frame：予約可能人数
            foreach($parts as $part => $frame){
        // ☆updateOrCreate() でデータを保存。（該当するデータがあれば更新し、なければ新しく登録するメソッド）
                ReserveSettings::updateOrCreate([
            // ☆この予約日、この部のデータはすでに存在する？」と確認するためのもの
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ],
            // ☆保存・更新する内容。
                [
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting',
        ['user_id' => Auth::id()]);
    }
}
