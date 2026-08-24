<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'username',
        'email_verified_at',
        'tokens',
        'parent_id',
        'position',
        'left_count',
        'right_count',
        'left_points',
        'right_points',
        'package_name',
        'saldo',
        'saldo_umroh',
        'total_bonus',
        'security_pin',
        'bonus_uncashed',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar_url',
    ];

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        return \Illuminate\Support\Facades\Storage::url($this->avatar);
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

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function leftSon()
    {
        return $this->hasOne(User::class, 'parent_id')->where('position', 'left');
    }

    public function rightSon()
    {
        return $this->hasOne(User::class, 'parent_id')->where('position', 'right');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'user_id');
    }

    public function rpps()
    {
        return $this->hasMany(Rpp::class);
    }

    public function tokenLogs()
    {
        return $this->hasMany(TokenLog::class);
    }

    public function tokenTransactions()
    {
        return $this->hasMany(TokenTransaction::class);
    }
}
