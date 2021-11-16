<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FightClub extends Model
{
    use HasFactory;

    public function fighters()
    {
        return $this->hasMany(User::class, 'fight_club_id');
    }
}
