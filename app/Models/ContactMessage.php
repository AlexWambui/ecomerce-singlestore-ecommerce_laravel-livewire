<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContactMessage extends Model
{
    protected static function booted(): void
    {
        static::creating(function (ContactMessage $message) {
            if (empty($message->uuid)) {
                $message->uuid = (string) Str::uuid();
            }
        });
    }

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'is_important' => 'boolean',
    ];
}
