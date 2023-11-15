<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_shop_role', 'role_id', 'user_id')
            ->withPivot('shop_id');
    }
}
