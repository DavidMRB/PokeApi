<?php

namespace App\Models;

use App\Models\Trainer;
use App\Models\Type;
use App\Models\Move;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    //
    use HasFactory;

    protected $table = 'pokemon';

    protected $fillable = [
        'name',
        'hp',
        'image'
    ];

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'trainer_pokemon');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'pokemon_type');
    }

    public function moves()
    {
        return $this->belongsToMany(Move::class, 'pokemon_move');
    }
}
