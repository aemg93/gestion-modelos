<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Earning;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    public function __construct()
    {
        // Middleware de permisos según acción
        $this->middleware('permission:register earnings')->only(['store']);
        $this->middleware('permission:edit earnings')->only(['update']);
        $this->middleware('permission:delete earnings')->only(['destroy']);
    }

    /**
     * Registrar una nueva ganancia
     * - Modelo: registra solo sus propias ganancias
     * - Admin: también puede registrar
     * - S-Admin: puede registrar y además editar/eliminar
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date',
        ]);

        $user = Auth::user();

        // Asociar la ganancia al perfil del usuario autenticado
        $earning = new Earning();
        $earning->model_profile_id = $user->modelProfile->id ?? null;
        $earning->amount = $request->amount;
        $earning->date   = $request->date;
        $earning->save();

        return response()->json([
            'message' => 'Ganancia registrada correctamente',
            'earning' => $earning,
        ]);
    }

    /**
     * Editar una ganancia
     * - Solo S-Admin tiene permiso
     */
    public function update(Request $request, Earning $earning)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date'   => 'required|date',
        ]);

        $earning->update($request->only(['amount', 'date']));

        return response()->json([
            'message' => 'Ganancia actualizada correctamente',
            'earning' => $earning,
        ]);
    }

    /**
     * Eliminar una ganancia
     * - Solo S-Admin tiene permiso
     */
    public function destroy(Earning $earning)
    {
        $earning->delete();

        return response()->json([
            'message' => 'Ganancia eliminada correctamente',
        ]);
    }
}
