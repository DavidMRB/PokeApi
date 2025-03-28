<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\trainerController;
use App\Http\Controllers\pokemonController;
use App\Http\Controllers\typeController;
use App\Http\Controllers\moveController;

Route::get('/pokemons', function () {
    $response = Http::get('https://pokeapi.co/api/v2/pokemon?limit=60');
    return response()->json($response->json());
});

Route::get('/pokemons/{idPokemon}', function ($idPokemon) {
    $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$idPokemon}");

    if ($response->failed()) {
        return response()->json(['error' => 'Pokemon not found'], 404);
    }

    return response()->json($response->json());

});

Route::get('/trainers/{idTrainer}', function ($idTrainer) {
    $response = Http::get("https://pokeapi.co/api/v2/trainer/{$idTrainer}");

    if ($response->failed()) {
        return response()->json(['error' => 'Trainer not found'], 404);
    }

    return response()->json($response->json());
});

// Pokemon

    Route::get('/pokemon', [pokemonController::class, 'showall']);

    Route::get('/pokemon/{id}', [pokemonController::class, 'show']);

    Route::post('/pokemon', [pokemonController::class, 'store']);

    Route::put('/pokemon/{id}', [pokemonController::class, 'update']);

    Route::patch('/pokemon/{id}', [pokemonController::class, 'updatePartial']);

    Route::delete('/pokemon/{id}', [pokemonController::class, 'del']);

// Trainer

    Route::get('/trainer', [trainerController::class, 'showall']);

    Route::get('/trainer/{id}', [trainerController::class, 'show']);

    Route::post('/trainer', [trainerController::class, 'store']);

    Route::post('/trainer/{id}/pokemon', [trainerController::class, 'assignPoke']);

    Route::get('/trainer/{id}/pokemon', [trainerController::class, 'getPoke']);

    Route::delete('/trainer/{id}/pokemon/{pokemonId}', [trainerController::class, 'removePoke']);

    Route::put('/trainer/{id}', [trainerController::class, 'update']);

    Route::patch('/trainer/{id}', [trainerController::class, 'updatePartial']);

    Route::delete('/trainer/{id}', [trainerController::class, 'del']);

// Type

    Route::get('/type', [TypeController::class, 'showall']);  // Obtener todos los tipos

    Route::post('/type', [TypeController::class, 'store']); // Crear un nuevo tipo

    Route::get('/type/{id}', [TypeController::class, 'show']); // Obtener un tipo específico

    Route::get('/type/{id}/pokemon', [TypeController::class, 'getPokeType']);

    Route::put('/type/{id}', [TypeController::class, 'update']); // Actualizar un tipo

    Route::delete('/type/{id}', [TypeController::class, 'del']);

// Move

    Route::get('/move', [MoveController::class, 'showall']);

    Route::get('/move/{id}', [MoveController::class, 'show']);

    Route::post('/move', [MoveController::class, 'store']);

    Route::put('/move/{id}', [MoveController::class, 'update']);

    Route::patch('/move/{id}', [MoveController::class, 'updatePartial']);

    Route::delete('/move/{id}', [MoveController::class, 'del']);

    Route::get('/move/{id}/pokemon', [MoveController::class, 'getPokeMove']);