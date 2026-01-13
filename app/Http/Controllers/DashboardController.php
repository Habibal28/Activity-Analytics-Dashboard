<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activities;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
        $action    = $request->action;

        // base filter
        $baseQuery = Activities::query()
            ->when($startDate, fn ($q) =>
                $q->whereDate('activities.created_at', '>=', $startDate)
            )
            ->when($endDate, fn ($q) =>
                $q->whereDate('activities.created_at', '<=', $endDate)
            )
            ->when($action, fn ($q) =>
                $q->where('activities.action', $action)
            );

        // card summary
        $masterActivity = Activities::select('action')->distinct()->pluck('action')->toArray();
        $totalActivity =  (clone $baseQuery)->count();
        $totalUserActive = (clone $baseQuery)->distinct('user_id')->count('user_id');
        $avgActivityPerDay =  (clone $baseQuery)->selectRaw(
            'COALESCE(
                ROUND(COUNT(*) / NULLIF(COUNT(DISTINCT DATE(created_at)), 0), 0),
            0) as avg_per_day'
        )->value('avg_per_day');
        $mostActivity = (clone $baseQuery)->select('action', DB::raw('COUNT(*) as total'))->groupBy('action')->orderByDesc('total')->value('action');

        // chart summary
        $activityPerDay = (clone $baseQuery)
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        $topUser = (clone $baseQuery)
        ->select(
            'users.id',
            'users.name',
            DB::raw('COUNT(*) as total')
        )
        ->join('users', 'activities.user_id', '=', 'users.id')
        ->groupBy('users.id', 'users.name')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();

        $perAction = (clone $baseQuery)
        ->select(
            'action',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('action')
        ->orderBy('total', 'desc')
        ->get();

        $responseData = [
            'masterActivity'     => $masterActivity,
            'totalActivity'     => $totalActivity,
            'totalUserActive'   => $totalUserActive,
            'avgActivityPerDay' => $avgActivityPerDay,
            'mostActivity'      => $mostActivity,

            'dailyLabel'   => $activityPerDay->pluck('date'),
            'dailyTotal'   => $activityPerDay->pluck('total'),

            'topUserLabel'  => $topUser->pluck('name'),
            'topUserTotal'  => $topUser->pluck('total'),

            'perActionLabel' => $perAction->pluck('action'),
            'perActionTotal' => $perAction->pluck('total'),
        ];


        if ($request->ajax()) {
            return response()->json($responseData);
        }

        return view('dashboard', $responseData);
    
    }
}