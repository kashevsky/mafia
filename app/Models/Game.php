<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function photos()
    {
        return $this->hasMany(GamePhoto::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class)->with('account');
    }
    public function newApplications()
    {
        return $this->hasMany(Application::class)->whereNull('is_approved');
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function results()
    {
        return $this->hasMany(GameResult::class)->with('account');
    }

    public function winners()
    {
        return $this->hasMany(GameWinner::class, 'game_id')
            ->with('account');
    }
}
