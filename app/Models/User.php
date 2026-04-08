<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_no',
        'photo_url',
        'gmail_account_id',
        'balance',
        'is_active',
        'refer_code',
        'referred_by_id',
        'redeemed_amount',
        'referrals_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
        'gmail_account_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->refer_code)) {
                $user->refer_code = static::generateUniqueReferCode();
            }
        });
    }

    public static function generateUniqueReferCode()
    {
        do {
            $code = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6));
        } while (static::where('refer_code', $code)->exists());

        return $code;
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function earningCoinHistories()
    {
        return $this->hasMany(EarningCoinHistory::class);
    }

    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class);
    }
}
