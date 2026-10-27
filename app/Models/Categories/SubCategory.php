<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];

// ☆サブカテゴリーが一つのメインカテゴリーに属しているリレーション設定↓（投稿用）
    public function mainCategory(){
        // リレーションの定義
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }


// ☆サブカテゴリーと投稿のリレーション設定↓（検索用）
    public function posts(){
        // リレーションの定義
        return $this->belongsToMany(
        \App\Models\Post::class,
        'post_sub_categories',
        'sub_category_id',
        'post_id'
    );
    }
}
