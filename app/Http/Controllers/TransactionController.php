<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'items'])->latest()->get();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
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
}
