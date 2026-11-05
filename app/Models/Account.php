<?php

namespace App\Models;

use App\Models\Achivment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $guarded = false;

    protected $casts = [
        'buff_role' => 'array',
    ];


    public function setBuffRoleAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['buff_role'] = '[]';
        } elseif (is_array($value)) {
            $this->attributes['buff_role'] = json_encode($value);
        } else {
            $this->attributes['buff_role'] = $value;
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class)->with('winners');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function probabilities()
    {
        return $this->belongsToMany(Probability::class, 'account_probabilities', 'account_id', 'probability_id')
            ->wherePivot('is_activated', 0) // Фильтр: выбираем только с is_activated == 0
            ->withPivot('is_activated');    // Позволяет читать это поле, если понадобится
    }


    public function achievements()
    {
        return $this->hasMany(Achivment::class);
    }

    public function immunities()
    {
        return $this->belongsToMany(Immunity::class, 'account_immunities', 'account_id', 'immunity_id')->withPivot(['is_active']);
    }
}
