<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function goods()
    {
        return $this->hasMany(Good::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'goods', 'user_id', 'product_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}



