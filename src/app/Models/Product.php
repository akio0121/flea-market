<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategory;

class Product extends Model
{
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function goods()
    {
        return $this->hasMany(Good::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'goods', 'product_id', 'user_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }
}
