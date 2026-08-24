<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rpp extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'gaya_belajar' => 'array',
            'dimensi_profil' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
