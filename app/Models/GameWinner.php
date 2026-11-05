<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameWinner extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
