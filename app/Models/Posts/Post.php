<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

// ☆Postモデル（投稿）と PostCommentモデル（コメント）のリレーション↓
// ☆1つの投稿に複数のコメントが紐づく＝hasMany
    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
//☆サブカテゴリーの検索で完全一致のためのリレーションの定義
        return $this->belongsTo('App\Models\Categories\SubCategory');
    }

    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }
}
