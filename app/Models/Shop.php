<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\Scopes\UserIDScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['shop_name', 'shop_location', 'description',];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
    function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
    function expense(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
