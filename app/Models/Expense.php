<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\User;
use App\Models\Scopes\UserIDScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['expense_type', 'user_id', 'shop_id', 'total_amount', 'paid_amount', 'unpaid_amount', 'created_by', 'updated_by', 'source', 'debt_type'];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
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
