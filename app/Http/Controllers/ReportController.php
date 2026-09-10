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

        $expenses = \App\Models\Expense::all();

        $reports = $transactions->groupBy('date')->map(function ($dayTransactions, $date) use ($expenses) {
            $totalRevenue = $dayTransactions->sum('total');
            $totalTransactions = $dayTransactions->count();
            
            $dayExpenses = $expenses->filter(function ($expense) use ($date) {
                // Assuming $date is like '2026-09-10 00:00:00', we take the date part
                return substr($expense->expense_date->format('Y-m-d'), 0, 10) === substr($date, 0, 10);
            });
            $totalExpenses = $dayExpenses->sum('amount');

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
                'total_expenses' => $totalExpenses,
                'net_profit' => $totalRevenue - $totalExpenses,
                'total_transactions' => $totalTransactions,
                'breakdown' => $products,
            ];
        })->values();

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'period' => $period,
        ]);
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'daily');

        $format = match ($period) {
            'monthly' => 'Y-m',
            'weekly' => 'o-\WW', // ISO week year and week number
            default => 'Y-m-d',
        };

        $transactions = Transaction::where('status', 'completed')->get();
        $expenses = \App\Models\Expense::all();

        // Group by period
        $days = $transactions->groupBy(function($t) use ($format) {
            return $t->created_at->format($format);
        });
        
        $expenseDays = $expenses->groupBy(function($e) use ($format) {
            return $e->expense_date->format($format);
        });

        $allDates = $days->keys()->merge($expenseDays->keys())->unique()->sort()->reverse();

        $exportData = collect();
        foreach ($allDates as $date) {
            $rev = $days->has($date) ? $days[$date]->sum('total') : 0;
            $exp = $expenseDays->has($date) ? $expenseDays[$date]->sum('amount') : 0;
            
            // Format label periode
            $labelPeriode = $date;
            if ($period === 'monthly') {
                $labelPeriode = \Carbon\Carbon::createFromFormat('Y-m', $date)->translatedFormat('F Y');
            } elseif ($period === 'weekly') {
                $parts = explode('-W', $date);
                if (count($parts) == 2) {
                    $startOfWeek = \Carbon\Carbon::now()->setISODate($parts[0], $parts[1])->startOfWeek();
                    $endOfWeek = $startOfWeek->copy()->endOfWeek();
                    $labelPeriode = $startOfWeek->translatedFormat('d M Y') . ' - ' . $endOfWeek->translatedFormat('d M Y');
                }
            } else {
                $labelPeriode = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('d F Y');
            }
            
            $exportData->push([
                'Periode' => $labelPeriode,
                'Total Transaksi' => $days->has($date) ? $days[$date]->count() : 0,
                'Total Pendapatan' => 'Rp ' . number_format($rev, 0, ',', '.'),
                'Total Pengeluaran' => 'Rp ' . number_format($exp, 0, ',', '.'),
                'Laba Bersih' => 'Rp ' . number_format($rev - $exp, 0, ',', '.'),
            ]);
        }

        $headerStyle = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontBold()
            ->setBackgroundColor('E2E8F0');

        return (new \Rap2hpoutre\FastExcel\FastExcel($exportData))
            ->headerStyle($headerStyle)
            ->download("Laporan_ESID_{$period}.xlsx");
    }
}
