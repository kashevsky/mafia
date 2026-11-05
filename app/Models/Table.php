<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
