<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Type;

class typeController extends Controller
{
    //
    public function showall()
    {
        $types = Type::all();

        if ($types->isEmpty()) {
            $data = [
                'message' => 'No hay tipos registrados en la base de datos',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        return response()->json($types, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:type,name'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos incompletos o inválidos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $type = Type::create([
            'name' => $request->name
        ]);

        $data = [
            'message' => 'Tipo creado exitosamente',
            'type' => $type,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id)
    {
        $type = Type::find($id);

        if (!$type) {
            $data = [
                'message' => 'Tipo no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'message' => 'Tipo',
            'type' => $type,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function getPokeType($id)
    {
        $type = Type::with('pokemons')->find($id);

        if (!$type) {
            $data = [
                'message' => 'Tipo no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'type' => $type->name,
            'pokemons' => $type->pokemons,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $type = Type::find($id);

        if (!$type) {
            $data = [
                'message' => 'Tipo no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:type,name'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos inválidos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $type->name = $request->name;
        $type->save();

        $data = [
            'message' => 'Tipo actualizado exitosamente',
            'type' => $type,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function del($id)
    {
        $type = Type::find($id);

        if (!$type) {
            $data = [
                'message' => 'Tipo no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $type->pokemons()->detach();
        $type->delete();

        $data = [
            'message' => 'Tipo eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

}
