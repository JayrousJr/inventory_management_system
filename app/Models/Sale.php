<?php

namespace App\Models;

use App\Models\Debt;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Scopes\UserIDScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'product_quantity_sold', 'total_price',  'paid_amount', 'unpaid_amount', 'pro_buying_price', 'profit', 'pro_selling_price',  'saler_name', 'customer_name', 'product_name', 'debt_type', 'product_name_category', 'category', 'product_id', 'shop_id', 'source_id'];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    }
    // public function stockuser(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function debts_sale(): MorphMany
    // {
    //     return $this->morphMany(Debt::class, 'debtor');
    // }

    protected static function booted()
    {
        static::addGlobalScope(new UserIDScope);
    }
}
