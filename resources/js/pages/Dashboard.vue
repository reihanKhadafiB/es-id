<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

defineProps<{
    today_transactions: number;
    today_revenue: number;
    today_expenses: number;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="space-y-5 -mt-2">
        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-blue-700 to-blue-900 rounded-[20px] p-5 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl transform translate-x-8 -translate-y-8"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full blur-xl transform -translate-x-4 translate-y-4"></div>

            <div class="relative z-10">
                <p class="text-blue-100 text-xs font-medium mb-1 tracking-wide uppercase">Laba Bersih Hari Ini</p>
                <h2 class="text-3xl font-bold tracking-tight drop-shadow-sm">{{ formatCurrency(today_revenue - today_expenses) }}</h2>
                
                <div class="mt-6 pt-4 border-t border-white/10 flex justify-between items-center">
                    <div class="flex gap-4">
                        <div>
                            <p class="text-blue-200 text-[10px] mb-0.5">Pendapatan</p>
                            <p class="font-semibold text-sm">{{ formatCurrency(today_revenue) }}</p>
                        </div>
                        <div>
                            <p class="text-blue-200 text-[10px] mb-0.5">Pengeluaran</p>
                            <p class="font-semibold text-sm text-red-300">-{{ formatCurrency(today_expenses) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-200 text-[10px] mb-0.5">Transaksi</p>
                        <p class="font-semibold text-sm">{{ today_transactions }}x</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="bg-white rounded-[20px] border border-gray-100 p-5 shadow-[0_4px_20px_rgba(0,0,0,0.03)]">
            <h3 class="font-bold text-gray-900 mb-5 text-sm">Menu Cepat</h3>
            <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                <!-- Kasir -->
                <Link href="/transactions/create" class="flex flex-col items-center group">
                    <div class="w-[52px] h-[52px] bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-[16px] flex items-center justify-center mb-2 shadow-md shadow-blue-200/50 transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:shadow-blue-300/50">
                        <!-- Store / Cash register icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-700 text-center leading-tight">Pemasukan</span>
                </Link>
                <!-- Mutasi -->
                <Link href="/transactions" class="flex flex-col items-center group">
                    <div class="w-[52px] h-[52px] bg-gradient-to-br from-teal-400 to-teal-500 text-white rounded-[16px] flex items-center justify-center mb-2 shadow-md shadow-teal-200/50 transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:shadow-teal-300/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-700 text-center leading-tight">Mutasi</span>
                </Link>
                <!-- Produk -->
                <Link href="/products" class="flex flex-col items-center group">
                    <div class="w-[52px] h-[52px] bg-gradient-to-br from-orange-400 to-orange-500 text-white rounded-[16px] flex items-center justify-center mb-2 shadow-md shadow-orange-200/50 transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:shadow-orange-300/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-700 text-center leading-tight">Produk</span>
                </Link>
                <!-- Kategori -->
                <Link href="/categories" class="flex flex-col items-center group">
                    <div class="w-[52px] h-[52px] bg-gradient-to-br from-indigo-400 to-indigo-500 text-white rounded-[16px] flex items-center justify-center mb-2 shadow-md shadow-indigo-200/50 transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:shadow-indigo-300/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-700 text-center leading-tight">Kategori</span>
                </Link>
                <!-- Laporan -->
                <Link href="/reports" class="flex flex-col items-center group">
                    <div class="w-[52px] h-[52px] bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-[16px] flex items-center justify-center mb-2 shadow-md shadow-purple-200/50 transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:shadow-purple-300/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-700 text-center leading-tight">Laporan</span>
                </Link>
                <!-- Pengeluaran -->
                <Link href="/expenses" class="flex flex-col items-center group">
                    <div class="w-[52px] h-[52px] bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-[16px] flex items-center justify-center mb-2 shadow-md shadow-rose-200/50 transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1 group-hover:shadow-lg group-hover:shadow-rose-300/50">
                        <!-- Money out / expense icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-semibold text-gray-700 text-center leading-tight">Pengeluaran</span>
                </Link>
            </div>
        </div>
    </div>
</template>
