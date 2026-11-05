<?php

namespace App\Models;

use App\Models\GameVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function games()
    {
        return $this->hasMany(Game::class)
            ->with('results', 'winners')
            ->select('games.*')
            ->selectRaw("
            CASE
                WHEN date >= CURDATE() THEN date - CURDATE()
                ELSE DATEDIFF('9999-12-31', date)
            END AS sort_order
        ")
            // ЭТА СТРОКА ПОДНИМАЕТ PENDING НАВЕРХ:
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END ASC")
            // Далее идет ваша стандартная сортировка по датам
            ->orderByRaw('sort_order ASC')
            ->orderBy('date', 'asc');
    }



    public function photos()
    {
        return $this->hasMany(ClubPhoto::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function videos()
    {
        return $this->hasMany(ClubVideo::class);
    }
}
