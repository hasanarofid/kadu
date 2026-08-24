<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenLog extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rpp()
    {
        return $this->belongsTo(Rpp::class);
    }
}
