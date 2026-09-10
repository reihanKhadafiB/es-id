<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

defineProps<{
    today_transactions: number;
    today_revenue: number;
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

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Ringkasan penjualan hari ini</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                <p class="text-sm text-blue-600 font-medium mb-1">Transaksi</p>
                <p class="text-3xl font-bold text-blue-900">{{ today_transactions }}</p>
            </div>
            <div class="bg-green-50 rounded-2xl p-4 border border-green-100">
                <p class="text-sm text-green-600 font-medium mb-1">Pendapatan</p>
                <p class="text-xl font-bold text-green-900">{{ formatCurrency(today_revenue) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <h2 class="font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 gap-4">
                <Link href="/transactions/create" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-2 shadow-sm text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Catat Penjualan</span>
                </Link>
                <Link href="/products" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-2 shadow-sm text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Atur Produk</span>
                </Link>
            </div>
        </div>
    </div>
</template>
