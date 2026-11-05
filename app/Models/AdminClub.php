<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminClub extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
