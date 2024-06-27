<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Shop;
use App\Models\Store;
use App\Models\Product;
use App\Models\Purchase;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\UserIDScope;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, SoftDeletes, HasRoles;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_id',
        'shop_name',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // return $this->hasRole(['Manager', 'Sales Person', 'Stock Person', 'System Administrator']);
        return true;
    }

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return $this->hasRole(['Manager']);
    // }
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
    public function expense(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class, 's_or_c_name', 'name');
    }
    public function isManager(): bool
    {
        return $this->role === "Manager";
    }
    public function isAdmin(): bool
    {
        return $this->role === "System Administrator";
    }
    public function notVisible(): bool
    {
        return false;
    }

    public function isNotAdmin(): bool
    {
        return $this->hasRole(['Sales Person', 'Manager', 'Stock Person']);
    }
    // protected static function booted()
    // {
    //     static::addGlobalScope(new UserIDScope);
    // }


}
