<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Member::class, 'parent_member_id');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'parent_member_id');
    }

    public function packages()
    {
        return $this->hasMany(MemberPackage::class);
    }
}
