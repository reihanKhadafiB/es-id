<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');

        $format = match ($period) {
            'monthly' => 'month',
            'weekly' => 'week',
            default => 'day',
        };

        // Fetch all completed transactions with items
        $transactions = Transaction::with('items')
            ->where('status', 'completed')
            ->selectRaw("id, DATE_TRUNC('{$format}', created_at) as date, total")
            ->orderBy('date', 'desc')
            ->get();

        $reports = $transactions->groupBy('date')->map(function ($dayTransactions, $date) {
            $totalRevenue = $dayTransactions->sum('total');
            $totalTransactions = $dayTransactions->count();

            $products = [];
            foreach ($dayTransactions as $trx) {
                foreach ($trx->items as $item) {
                    if (! isset($products[$item->product_name])) {
                        $products[$item->product_name] = 0;
                    }
                    $products[$item->product_name] += $item->qty;
                }
            }

            arsort($products);

            return [
                'date' => $date,
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'breakdown' => $products,
            ];
        })->values();

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'period' => $period,
        ]);
    }
}
