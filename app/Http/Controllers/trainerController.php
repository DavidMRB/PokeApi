<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class trainerController extends Controller
{
    //
    public function showall()
    {
        $trainers = Trainer::all();

        if ($trainers->isEmpty()) {
            $data = [
                'message' => 'No hay entrenadores registrados en la base de datos',
                'status' => 200
            ];

            return response()->json($data, 200);

        }

        return response()->json($trainers, 200);
    }

    public function show($id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($trainer, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'nullable|integer',
            'region' => 'nullable'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos incompletos, es necesario llenar todos los campos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);

        }

        $trainer = Trainer::create([
            'name' => $request->name,
            'age' => $request->age,
            'region' => $request->region
        ]);

        if (!$trainer) {
            $data = [
                'message' => 'Error al registrar el entrenador',
                'status' => 500
            ];

            return response()->json($data, 500);

        }

        $data = [
            'trainer' => $trainer, 
            'status' => 201
        ];

        return response()->json($data, 201);

    }

    public function assignPoke(Request $request, $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);

        }

        $validator = Validator::make($request->all(), [
            'pokemon_id' => 'required|array',
            'pokemon_id.*' => 'exists:pokemon,id'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Pokemon no encontrado',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);

        }

        $trainer->pokemons()->attach($request->pokemon_id);

        $data = [
            'message' => 'Pokémon asignado al entrenador',
            'status' => 201
        ];

        return response()->json($data, 201);

    }

    public function getPoke(Request $request, $id)
    {
        $trainer = Trainer::with('pokemons.types')->find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);

        }

        $pokemonsQuery = $trainer->pokemons();

        if ($request->has('type')) {
            $typeName = $request->query('type');

            $pokemonsQuery = $pokemonsQuery->whereHas('types', function ($query) use ($typeName) {
                $query->where('name', $typeName);
            });
        }

        $pokemons = $pokemonsQuery->paginate(5);

        $data = [
            'trainer' => $trainer->name,
            'pokemons' => $pokemons,
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function removePoke($id, $pokemonId)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);

        }

        if (!$trainer->pokemons()->where('pokemon_id', $pokemonId)->exists()) {
            $data = [
                'message' => 'El entrenador no tiene este Pokémon asignado',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $trainer->pokemons()->detach($pokemonId);

        $data = [
            'message' => 'Pokémon eliminado del entrenador',
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function del($id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);

        }

        $trainer->pokemons()->detach();

        $trainer->delete();

        $data = [
            'message' => 'Entrenador eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function update(Request $request, $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);

        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'age' => 'nullable|integer',
            'region' => 'nullable'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos incompletos, es necesario llenar todos los campos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);

        }

        $trainer->name = $request->name;
        $trainer->age = $request->age;
        $trainer->region = $request->region;

        $trainer->save();

        $data = [
            'message' => 'Entrenador actualizado',
            'trainer' => $trainer,
            'status' => 200
        ];

        return response()->json($data, 200);
       
    }

    public function updatePartial(Request $request, $id)
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            $data = [
                'message' => 'Entrenador no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);

        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'age' => 'integer|nullable',
            'region' => 'string|nullable'
        ]);
        
        if ($validator->fails()) {
            $data = [
                'message' => 'Debes modificar al menos un dato',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);

        }

        if ($request->has('name')) {
            $trainer->name = $request->name;
        }

        if ($request->has('age')) {
            $trainer->age = $request->age;
        }

        if ($request->has('region')) {
            $trainer->region = $request->region;
        }

        $trainer->save();

        $data = [
            'message' => 'El entrenador ha sido actualizado',
            'trainer' => $trainer,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

}
