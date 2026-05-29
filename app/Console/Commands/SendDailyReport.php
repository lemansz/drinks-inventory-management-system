<?php

namespace App\Console\Commands;

use App\Models\Sale;
use App\Models\User;
use App\Services\StockService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a PDF of today\'s sales and emails it to the admin';

    /**
     * Execute the console command.
     * 
     * We inject the StockService directly here.
     */
    public function handle(StockService $stockService)
    {
        // 1. Get the admin user and currency setting
        $user = User::first();
        $currency = $stockService->currency();

        if (!$user) {
            $this->error('No user found in the database.');
            return;
        }

        // 2. Query today's sales with products and pivot data
        $sales = Sale::with('products')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        if ($sales->isEmpty()) {
            $this->warn('No sales recorded today. Email not sent.');
            return;
        }

        // 3. Calculate Grand Totals using your database columns
        $totalSalesAmount = $sales->sum('total_amount'); 
        $totalProfit = $sales->sum('total_profit');

        // 4. Generate the PDF
        // Ensure the path matches your folder: emails.auth.reports.daily-sales-pdf
        $pdf = Pdf::loadView('emails.auth.reports.daily-sales-pdf', [
            'sales'       => $sales,
            'totalSales'  => $totalSalesAmount,
            'totalProfit' => $totalProfit,
            'date'        => today()->format('M d, Y'),
            'currency'    => $currency
        ]);

        // 5. Send the Email with Attachment
        try {
            Mail::send([], [], function ($message) use ($pdf, $user) {
                $message->to($user->email)
                    ->subject('Daily Inventory Sales Report - ' . today()->format('d/m/Y'))
                    ->html('Hello Admin, <br><br> Please find the attached PDF report for today\'s sales.')
                    ->attachData($pdf->output(), 'Daily_Report_' . today()->format('Y-m-d') . '.pdf');
            });

            $this->info('Daily report sent successfully to ' . $user->email);
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
