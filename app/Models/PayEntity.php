<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayEntity extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }


    public function club()
    {
        return $this->belongsTo(Club::class);
    }

}
