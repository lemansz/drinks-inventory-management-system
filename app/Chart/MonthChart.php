<?php

namespace App\Chart;

use App\Services\ChartService;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class MonthChart {
    protected LarapexChart $chart;
    protected ChartService $chartService;

    public function __construct(LarapexChart $chart, ChartService $chartService)
    {
       $this->chart = $chart;
       $this->chartService = $chartService;
    }

    public function build()
    {
        $month = Carbon::now()->format('F');

        $data = $this->chartService->monthlySales();

        return $this->chart->barChart()
            ->setTitle($month)
            ->addData($data['sales'], 'Sales')
            ->addData($data['profit'], 'Profit')
            ->setXAxis($data['labels'])
            ->setHeight(350);
    }
}