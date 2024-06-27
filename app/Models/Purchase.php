<?php

namespace App\Models;

use App\Models\Scopes\UserIDScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'shop_id', 'supplier', 'category_id', 'product_name', 'product_name_category', 'quantity_in_purchase', 'buying_price', 'selling_price', 'total_cost', 'paid_amount', 'unpaid_amount', 'amount_exp', 'source'];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function debt(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsto(Shop::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserIDScope);
    }
}
