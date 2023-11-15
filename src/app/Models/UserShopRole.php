<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShopRole extends Model
{
    use HasFactory;

    protected $table = 'user_shop_role'; // 中間テーブルの名前を指定

    protected $fillable = [
        'user_id',
        'shop_id',
        'role_id',
    ];

    // リレーションを設定
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
