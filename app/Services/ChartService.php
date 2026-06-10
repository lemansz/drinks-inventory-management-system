<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartService 
{
    public function weeklySales()
    {
        $dates = collect(range(6, 0, -1))->map(function ($i) {
            return Carbon::now()->subDays($i)->startOfDay();
        });

        $startDate = $dates->first();
        $endDate = $dates->last();

        $sales = DB::table('sales')
            ->selectRaw("
                date(created_at) as day,
                SUM(total_amount) as total_amount,
                SUM(total_profit) as total_profit
            ")
            ->whereBetween('created_at', [$startDate->toDateTimeString(), $endDate->endOfDay()->toDateTimeString()])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $result = $dates->map(function ($date) use ($sales) {
            $key = $date->toDateString();

            return [
                'day' => $date->format('D j'),
                'sales' => $sales[$key]->total_amount ?? 0,
                'profit' => $sales[$key]->total_profit ?? 0,
            ];
        });

        return [
            'labels' => $result->pluck('day')->toArray(),
            'sales' => $result->pluck('sales')->toArray(),
            'profit' => $result->pluck('profit')->toArray(),
        ];
    }


    public function monthlySales()
    {
        $now = Carbon::now();
        $daysInMonth = $now->daysInMonth;

        $dates = collect(range(1, $daysInMonth))->map(function ($day) use ($now) {
            return Carbon::create($now->year, $now->month, $day)->startOfDay();
        });

        $startDate = $dates->first();
        $endDate = $dates->last();

        $sales = DB::table('sales')
            ->selectRaw("
                date(created_at) as day,
                SUM(total_amount) as total_amount,
                SUM(total_profit) as total_profit
            ")
            ->whereBetween('created_at', [$startDate->toDateTimeString(), $endDate->endOfDay()->toDateTimeString()])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $result = $dates->map(function ($date) use ($sales) {
            $key = $date->toDateString();

        return [
            'day' => $date->format('D j'),
            'sales' => $sales[$key]->total_amount ?? 0,
            'profit' => $sales[$key]->total_profit ?? 0,
        ];
        });

        return [
            'labels' => $result->pluck('day')->toArray(),
            'sales' => $result->pluck('sales')->toArray(),
            'profit' => $result->pluck('profit')->toArray(),
        ];
    }
}