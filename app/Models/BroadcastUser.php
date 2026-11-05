<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastUser extends Model
{
    use HasFactory;

    protected $table = 'account_broadcast';
    protected $guarded = [];
}
