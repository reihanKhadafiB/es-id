<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return Inertia::render('Expenses/Index', [
            'expenses' => \App\Models\Expense::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'expense_date' => 'required|date',
        ]);

        $request->user()->expenses()->create($validated);

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(\App\Models\Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
