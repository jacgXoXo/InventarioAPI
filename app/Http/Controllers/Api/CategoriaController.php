<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(
            Categoria::with('productos')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string'
        ]);

        $categoria = Categoria::create($request->all());

        return response()->json([
            'mensaje' => 'Categoría creada correctamente',
            'categoria' => $categoria
        ],201);
    }

    public function show($id)
    {
        $categoria = Categoria::with('productos')->find($id);

        if(!$categoria){
            return response()->json([
                'mensaje' => 'Categoría no encontrada'
            ],404);
        }

        return response()->json($categoria);
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);

        if(!$categoria){
            return response()->json([
                'mensaje' => 'Categoría no encontrada'
            ],404);
        }

        $categoria->update($request->all());

        return response()->json([
            'mensaje' => 'Categoría actualizada correctamente',
            'categoria' => $categoria
        ]);
    }

    public function destroy($id)
    {
        $categoria = Categoria::find($id);

        if(!$categoria){
            return response()->json([
                'mensaje' => 'Categoría no encontrada'
            ],404);
        }

        $categoria->delete();

        return response()->json([
            'mensaje' => 'Categoría eliminada correctamente'
        ]);
    }
}