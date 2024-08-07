<?php

namespace App\Http\Controllers\Mobile\Dashboard;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\AnalyticService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard analytics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalytics(Request $request)
    {
        try {
            $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
            $results = AnalyticService::getMobileDashboardAnalytics($startDate, $endDate);

            return ResponseHelper::data($results);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }
}
