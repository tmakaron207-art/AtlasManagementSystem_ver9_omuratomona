<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// ☆追加↓
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    // ☆追加↓
    DB::table('users')->insert([
        'over_name'=>'田中',
        'under_name'=>'一郎',
        'over_name_kana'=>'タナカ',
        'under_name_kana'=>'イチロウ',
        'mail_address'=>'test@example.com',
        'sex'=>1,
        'birth_day'=>'1991-01-01',
        'role'=>1,
        'password'=>Hash::make('password123'),
        'remember_token'=>null,
        'created_at'=>now(),
        'updated_at'=>now(),
    ]);

    }
}
