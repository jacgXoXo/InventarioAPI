<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        return response()->json(
            Proveedor::with('productos')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor = Proveedor::create($request->all());

        return response()->json([
            'mensaje' => 'Proveedor creado correctamente',
            'proveedor' => $proveedor
        ], 201);
    }

    public function show(string $id)
    {
        $proveedor = Proveedor::with('productos')->find($id);

        if (!$proveedor) {
            return response()->json([
                'mensaje' => 'Proveedor no encontrado'
            ], 404);
        }

        return response()->json($proveedor);
    }

    public function update(Request $request, string $id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json([
                'mensaje' => 'Proveedor no encontrado'
            ], 404);
        }

        $proveedor->update($request->all());

        return response()->json([
            'mensaje' => 'Proveedor actualizado correctamente',
            'proveedor' => $proveedor
        ]);
    }

    public function destroy(string $id)
    {
        $proveedor = Proveedor::find($id);

        if (!$proveedor) {
            return response()->json([
                'mensaje' => 'Proveedor no encontrado'
            ], 404);
        }

        $proveedor->delete();

        return response()->json([
            'mensaje' => 'Proveedor eliminado correctamente'
        ]);
    }
}