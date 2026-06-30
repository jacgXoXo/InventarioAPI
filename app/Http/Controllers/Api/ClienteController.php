<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return response()->json(
            Cliente::with('ventas')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'required|email|max:255|unique:clientes,correo',
        ]);

        $cliente = Cliente::create($request->all());

        return response()->json([
            'mensaje' => 'Cliente creado correctamente',
            'cliente' => $cliente
        ], 201);
    }

    public function show(string $id)
    {
        $cliente = Cliente::with('ventas')->find($id);

        if (!$cliente) {
            return response()->json([
                'mensaje' => 'Cliente no encontrado'
            ], 404);
        }

        return response()->json($cliente);
    }

    public function update(Request $request, string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'mensaje' => 'Cliente no encontrado'
            ], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'required|email|max:255|unique:clientes,correo,' . $id,
        ]);

        $cliente->update($request->all());

        return response()->json([
            'mensaje' => 'Cliente actualizado correctamente',
            'cliente' => $cliente
        ]);
    }

    public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json([
                'mensaje' => 'Cliente no encontrado'
            ], 404);
        }

        $cliente->delete();

        return response()->json([
            'mensaje' => 'Cliente eliminado correctamente'
        ]);
    }
}