<?php

namespace App\Models;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    //
    use HasFactory;

    protected $table = 'trainer';

    protected $fillable = [
        'name',
        'age',
        'region'
    ];

    public function pokemons()
    {
        return $this->belongsToMany(Pokemon::class, 'trainer_pokemon');
    }
}
