<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $today = now()->startOfDay();
        $stats = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $today)
            ->selectRaw('count(*) as count, sum(total) as revenue')
            ->first();

        return inertia('Dashboard', [
            'today_transactions' => $stats->count ?? 0,
            'today_revenue' => $stats->revenue ?? 0,
        ]);
    })->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('transactions/{transaction}/void', [TransactionController::class, 'void'])->name('transactions.void');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/settings.php';
