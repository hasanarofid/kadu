<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenPackage extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'tokens' => 'integer',
        ];
    }
}
