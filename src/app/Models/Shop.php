<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shop extends Model
{
    use HasFactory;

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    protected $fillable = ['name', 'address'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_shop_role', 'favorites', 'shop_id', 'user_id')->withPivot('role_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'shop_id', 'user_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeSearchByArea($query, $area)
    {
        return $query->whereHas('address', function ($query) use ($area) {
            $query->where('city', 'like', '%' . $area . '%');
        });
    }

    public function scopeSearchByGenre($query, $genre)
    {
        return $query->whereHas('genre', function ($query) use ($genre) {
            $query->where('name', 'like', '%' . $genre . '%');
        });
    }

    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function csvHeader(): array
    {
        return [
            'name',
            'address_id',
            'genre_id',
            'description',
            'image_path',
        ];
    }

    public function getCsvData(): \Illuminate\Support\Collection
    {
        $data = DB::table('shops')->get();
        return $data;
    }
    public function insertRow($row): array
    {
        return [
            $row->name,
            $row->address_id,
            $row->genre_id,
            $row->description,
            $row->image_path,
        ];
    }
}
