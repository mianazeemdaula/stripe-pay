<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'fee',
        'status',
        'transfer_by',
        'transfer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transferBy()
    {
        return $this->belongsTo(User::class, 'transfer_by');
    }
}
