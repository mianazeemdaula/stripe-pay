<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected $fillable = [
        'amount',
        'type',
        'description',
        'user_id',
    ];

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
