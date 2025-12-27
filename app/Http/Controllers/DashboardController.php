<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Earning;
use App\Models\WorkHour;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $modelId = $request->get('model_id', 1);

        $earnings = Earning::where('model_profile_id', $modelId)
            ->select('amount', 'date')
            ->orderBy('date')
            ->get();

        $workHours = WorkHour::where('model_profile_id', $modelId)
            ->select('hours', 'date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'earnings' => $earnings,
            'work_hours' => $workHours,
            'summary' => [
                'meta' => 36,
                'trabajadas' => $workHours->sum('hours'),
                'faltantes' => 36 - $workHours->sum('hours'),
            ],
        ]);
    }
}
