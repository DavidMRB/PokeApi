<?php

namespace App\Http\Controllers;

use App\Models\Move;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class moveController extends Controller
{
    //
    public function showall()
    {
        $moves = Move::all();

        if ($moves->isEmpty()) {
            $data = [
                'message' => 'No hay movimientos registrados en la base de datos',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        return response()->json($moves, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:move,name',
            'damage' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos incompletos o inválidos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $move = Move::create([
            'name' => $request->name,
            'damage' => $request->damage
        ]);

        $data = [
            'message' => 'Moviento creado exitosamente',
            'move' => $move,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id)
    {
        $move = Move::find($id);

        if (!$move) {
            $data = [
                'message' => 'Moviento no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'message' => 'Movimiento',
            'move' => $move,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function getPokeMove($id)
    {
        $move = Move::with('pokemons')->find($id);

        if (!$move) {
            $data = [
                'message' => 'Movimiento no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
            'move' => $move->name,
            'pokemons' => $move->pokemons,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $move = Move::find($id);

        if (!$move) {
            $data = [
                'message' => 'Movimiento no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'unique:move,name',
            'damage' => 'integer'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Datos inválidos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')) {
            $move->name = $request->name;
        }
        if ($request->has('damage')) {
            $move->damage = $request->damage;
        }

        $move->save();

        $data = [
            'message' => 'Movimiento actualizado exitosamente',
            'move' => $move,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function del($id)
    {
        $move = Move::find($id);

        if (!$move) {
            $data = [
                'message' => 'Movimiento no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $move->pokemons()->detach();
        $move->delete();

        $data = [
            'message' => 'Movimiento eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
