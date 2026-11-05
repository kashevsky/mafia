<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'admin_clubs', 'admin_id', 'club_id');
    }

    public function childs()
    {
        return $this->hasMany(Admin::class, 'parent_id');
    }

    protected $casts = [
        'permissions' => 'array', // Laravel автоматически кодирует/декодирует JSON
    ];
    public function getPermissionsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
