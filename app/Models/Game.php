<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'winner_id',
    ];

    public function boards()
    {
        return $this->hasMany(Board::class);
    }

    public function moves()
    {
        return $this->hasMany(Move::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
