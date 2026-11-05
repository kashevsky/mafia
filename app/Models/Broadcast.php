<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'scheduled_time',
        'status',
        'user_count',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'scheduled_time' => 'datetime',
    ];

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function sent_to()
    {
        return $this->belongsToMany(User::class, 'broadcast_sent_log', 'broadcast_id', 'user_id');
    }
}
