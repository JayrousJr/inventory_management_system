<?php

namespace App\Models;

use App\Models\Scopes\UserIDScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Debt extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['shop_id', 'total_amount', 'paid_amount', 'remaining_amount', 'deleted_at', 'edited_by'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
    protected static function booted()
    {
        static::addGlobalScope(new UserIDScope);
    }
}
