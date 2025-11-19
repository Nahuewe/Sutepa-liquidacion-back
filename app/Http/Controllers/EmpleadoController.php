<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        return response()->json(Empleado::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string',
            'apellido'      => 'required|string',
            'cuil'          => 'nullable|string|unique:empleados,cuil',
            'legajo'        => 'nullable|string|unique:empleados,legajo',
            'sueldo_basico' => 'required|numeric',
            'puesto'        => 'nullable|string'
        ]);

        $empleado = Empleado::create($validated);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Crear',
            'modelo'    => 'Empleado',
            'modelo_id' => $empleado->id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json($empleado, 201);
    }

    public function show($id)
    {
        return response()->json(Empleado::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $validated = $request->validate([
            'nombre'        => 'required|string',
            'apellido'      => 'required|string',
            'cuil'          => "nullable|string|unique:empleados,cuil,$id",
            'legajo'        => "nullable|string|unique:empleados,legajo,$id",
            'sueldo_basico' => 'required|numeric',
            'puesto'        => 'nullable|string'
        ]);

        $empleado->update($validated);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Actualizar',
            'modelo'    => 'Empleado',
            'modelo_id' => $id,
            'datos'     => json_encode($validated),
        ]);

        return response()->json($empleado);
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);

        Auditoria::create([
            'user_id'   => auth()->id(),
            'accion'    => 'Eliminar',
            'modelo'    => 'Empleado',
            'modelo_id' => $id,
            'datos'     => json_encode($empleado),
        ]);

        $empleado->delete();

        return response()->json(['message' => 'Empleado eliminado']);
    }
}
