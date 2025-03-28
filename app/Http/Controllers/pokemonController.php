<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class pokemonController extends Controller
{
    //
    public function showall()
    {
        $pokemons = Pokemon::with(['types', 'moves'])->get();

        if ($pokemons->isEmpty()) {
            $data = [
                'message' => 'No hay pokemones registrados en nuestra base de datos',
                'status' => 200
            ];
            return response()->json($data, 200);
        };

        return response()->json($pokemons, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'hp' => 'nullable|integer',
            'image' => 'nullable|url',
            'types' => 'required|array',
            'types.*' => 'exists:type,id',
            'moves' => 'required|array',
            'moves.*' => 'exists:move,id'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos incompletos, es necesario llenar todos los campos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $pokemon = Pokemon::create([
            'name' => $request->name,
            'hp' => $request->hp ?? 100,
            'image' => $request->image
        ]);

        if ($request->has('types')) {
            $pokemon->types()->attach($request->types);
        }
    
        if ($request->has('moves')) {
            $pokemon->moves()->attach($request->moves);
        }

        if (!$pokemon){
            $data = [
                'message' => 'Error al registrar el pokemon',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Pokemon creado exitosamente',
            'pokemon' => $pokemon->load('types', 'moves'),
            'status' => 201
        ];

        return response()->json($data, 201);

    }

    public function show($id)
    {
        $pokemon = Pokemon::with(['types', 'moves'])->find($id);

        if (!$pokemon){
            $data = [
                'message' => 'Pokemon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'pokemon' => $pokemon,
            'status' => 200
        ];

        return response()->json($data, 200);
        
    }

    public function del($id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon){
            $data = [
                'message' => 'Pokemon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $pokemon->types()->detach();
        $pokemon->moves()->detach();
        $pokemon->delete();

        $data = [
            'message' => 'Pokemon eliminado',
            'status' => 200
        ];

        return response()->json($data, 200);
        
    }

    public function update(Request $request, $id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon){
            $data = [
                'message' => 'Pokemon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'hp' => 'nullable|integer',
            'image' => 'nullable|url',
            'types' => 'required|array',  
            'types.*' => 'exists:type,id',
            'moves' => 'required|array',
            'moves.*' => 'exists:move,id'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos incompletos, es necesario llenar todos los campos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $pokemon->name = $request->name;
        $pokemon->hp = $request->hp ?? 100;
        $pokemon->image = $request->image;

        $pokemon->save();

        if ($request->has('types')) {
            $pokemon->types()->sync($request->types);
        }

        if ($request->has('moves')) {
            $pokemon->moves()->sync($request->moves);
        }

        $data = [
            'message' => 'El pokemon ha sido actualizado',
            'pokemon' => $pokemon->load(['types', 'moves']),
            'status' => 200
        ];

        return response()->json($data, 200);

    }

    public function updatePartial(Request $request, $id)
    {
        $pokemon = Pokemon::find($id);

        if (!$pokemon){
            $data = [
                'message' => 'Pokemon no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'hp' => 'nullable|integer',
            'image' => 'nullable|url',
            'types' => 'array',
            'types.*' => 'exists:type,id',
            'moves' => 'array',
            'moves.*' => 'exists:move,id'
        ]);
        
        if ($validator->fails()) {
            $data = [
                'message' => 'Debes de modificar un dato almenos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')){
            $pokemon->name = $request->name;
        }

        if ($request->has('hp')){
            $pokemon->hp = $request->hp;
        }

        if ($request->has('image')){
            $pokemon->image = $request->image;
        }

        $pokemon->save();

        if ($request->has('types')) {
            $pokemon->types()->sync($request->types);
        }

        if ($request->has('moves')) {
            $pokemon->moves()->sync($request->moves);
        }

        $data = [
            'message' => 'El pokemon ha sido actualizado',
            'pokemon' => $pokemon->load('types', 'moves'),
            'status' => 200
        ];

        return response()->json($data, 200);

    }
}
