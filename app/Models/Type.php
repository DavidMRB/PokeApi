<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    use HasFactory;

    protected $table = 'type';

    protected $fillable = [
        'name'
    ];

    public function pokemons()
    {
        return $this->belongsToMany(Pokemon::class, 'pokemon_type');
    }
}
