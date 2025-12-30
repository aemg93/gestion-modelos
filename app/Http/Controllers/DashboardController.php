<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Earning;
use App\Models\WorkHour;
use App\Models\ModelProfile;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $from = $request->get('from');
        $to = $request->get('to');
        $meta = 36;

        // 🔹 Si el usuario es modelo, forzamos su propio model_id
        if ($user->hasRole('Modelo')) {
            $modelId = $user->model_profile_id;
        } else {
            $modelId = $request->get('model_id');
        }

        // 🔹 Si no se pasa model_id y el usuario no es Admin/S-Admin → bloqueamos
        if (!$modelId && !$user->hasAnyRole(['Admin', 'S-Admin'])) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // 🔹 Si hay model_id → datos de ese modelo
        if ($modelId) {
            $earnings = Earning::where('model_profile_id', $modelId)
                ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('date', '<=', $to))
                ->select('amount', 'date')
                ->orderBy('date')
                ->get();

            $workHours = WorkHour::where('model_profile_id', $modelId)
                ->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                ->when($to, fn($q) => $q->whereDate('date', '<=', $to))
                ->select('hours', 'date')
                ->orderBy('date')
                ->get();

            $trabajadas = $workHours->sum('hours');
            $faltantes = max($meta - $trabajadas, 0);
            $ganancias = $earnings->sum('amount');
            $cumplimiento = $meta > 0 ? round(($trabajadas / $meta) * 100, 2) : 0;

            return response()->json([
                'earnings' => $earnings,
                'work_hours' => $workHours,
                'summary' => [
                    'meta' => $meta,
                    'trabajadas' => $trabajadas,
                    'faltantes' => $faltantes,
                    'ganancias' => $ganancias,
                    'cumplimiento' => $cumplimiento,
                ],
            ]);
        }

        // 🔹 Resumen global (solo Admin/S-Admin)
        $summary = ModelProfile::with([
            'workHours' => function ($q) use ($from, $to) {
                $q->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                  ->when($to, fn($q) => $q->whereDate('date', '<=', $to));
            },
            'earnings' => function ($q) use ($from, $to) {
                $q->when($from, fn($q) => $q->whereDate('date', '>=', $from))
                  ->when($to, fn($q) => $q->whereDate('date', '<=', $to));
            }
        ])->get()->map(function ($model) use ($meta) {
            $worked = $model->workHours->sum('hours');
            $remaining = max($meta - $worked, 0);
            $ganancias = $model->earnings->sum('amount');
            $cumplimiento = $meta > 0 ? round(($worked / $meta) * 100, 2) : 0;

            return [
                'id' => $model->id,
                'modelo' => $model->name,
                'horas_trabajadas' => $worked,
                'horas_restantes' => $remaining,
                'ganancias' => $ganancias,
                'cumplimiento' => $cumplimiento,
            ];
        });

        return response()->json([
            'summary' => $summary,
        ]);
    }

    // 🔹 Método: lista de modelos reales
    public function models()
    {
        $user = auth()->user();

        // Si es modelo, solo devuelve su propio perfil
        if ($user->hasRole('Modelo')) {
            return response()->json([
                ['id' => $user->model_profile_id, 'name' => $user->name]
            ]);
        }

        // Admin/S-Admin ven todos los modelos
        $models = ModelProfile::select('id', 'name')->orderBy('name')->get();
        return response()->json($models);
    }
}
