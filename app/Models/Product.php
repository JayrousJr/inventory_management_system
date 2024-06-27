<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Scopes\UserIDScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['quantity'];
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'id', 'source_id');
    }
    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class, 'product_id', 'id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new UserIDScope);
    }
}
