<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);

        $transactions = Transaction::with(['user', 'items'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->latest()
            ->get();

        $availableMonths = collect();
        for ($i = 0; $i < 6; $i++) {
            $availableMonths->push(now()->subMonths($i)->format('Y-m'));
        }

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'selectedMonth' => $month,
            'availableMonths' => $availableMonths,
        ]);
    }

    public function create()
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        return Inertia::render('Transactions/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|integer|min:0',
            'payment_method' => 'required|in:cash,qris',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:0',
            'items.*.subtotal' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $transaction = Transaction::create([
                'user_id' => $request->user()->id,
                'total' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);

            foreach ($validated['items'] as $item) {
                $transaction->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'qty' => $item['qty'],
                    'price_at_transaction' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function void(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'voided',
            'voided_at' => now(),
        ]);

        return back()->with('success', 'Transaksi dibatalkan.');
    }

    public function export(Request $request)
    {
        $month = $request->get('month', date('Y-m'));
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);

        $transactions = Transaction::with(['user', 'items'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNum)
            ->latest()
            ->get();

        $exportData = collect();
        foreach ($transactions as $trx) {
            $items = $trx->items->map(function ($item) {
                return $item->product_name . ' (x' . $item->qty . ')';
            })->join(', ');

            $exportData->push([
                'ID Transaksi' => 'ESID-' . str_pad($trx->id, 5, '0', STR_PAD_LEFT),
                'Waktu' => $trx->created_at->format('Y-m-d H:i:s'),
                'Kasir' => $trx->user->name ?? '-',
                'Total (Rp)' => $trx->total,
                'Metode Pembayaran' => strtoupper($trx->payment_method),
                'Status' => strtoupper($trx->status),
                'Rincian Produk' => $items,
            ]);
        }

        $headerStyle = (new \OpenSpout\Common\Entity\Style\Style())
            ->setFontBold()
            ->setBackgroundColor('E2E8F0');

        return (new \Rap2hpoutre\FastExcel\FastExcel($exportData))
            ->headerStyle($headerStyle)
            ->download("Riwayat_Mutasi_ESID_{$month}.xlsx");
    }
}
