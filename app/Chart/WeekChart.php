<?php

namespace App\Chart;

use App\Services\ChartService;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class WeekChart
{
    protected $chart;
    protected $chartService;

    public function __construct(LarapexChart $chart, ChartService $chartService)
    {
       $this->chart = $chart;
       $this->chartService = $chartService;
    }

    public function build()
    {
        $data = $this->chartService->weeklySales();
        
        return $this->chart->barChart()
            ->setTitle("Past 7 days")
            ->addData($data['sales'], 'Sales')
            ->addData($data['profit'], 'Profit')
            ->setXAxis($data['labels'])
            ->setHeight(350);
    }
}