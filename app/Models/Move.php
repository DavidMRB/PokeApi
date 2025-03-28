<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    //
    use HasFactory;

    protected $table = 'move';

    protected $fillable = [
        'name',
        'damage'
    ];

    public function pokemons()
    {
        return $this->belongsToMany(Pokemon::class, 'pokemon_move');
    }
}
