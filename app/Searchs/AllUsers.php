<?php
namespace App\Searchs;

use App\Models\Users\User;

class AllUsers implements DisplayUsers{

  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $users = User::query();

    // 科目が選択されている場合
        if (!empty($subjects)) {
            $users->whereHas('subjects', function ($query) use ($subjects) {
                $query->whereIn('subjects.id', $subjects);
            });

        }

    return $users->get();
  }


}
