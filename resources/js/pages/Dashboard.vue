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
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="-mt-2 space-y-5">
        <!-- Balance Card -->
        <div
            class="relative overflow-hidden rounded-[20px] bg-gradient-to-br from-blue-700 to-blue-900 p-5 text-white shadow-lg"
        >
            <!-- Decorative Elements -->
            <div
                class="absolute top-0 right-0 h-32 w-32 translate-x-8 -translate-y-8 transform rounded-full bg-white opacity-5 blur-2xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 h-24 w-24 -translate-x-4 translate-y-4 transform rounded-full bg-white opacity-10 blur-xl"
            ></div>

            <div class="relative z-10">
                <p
                    class="mb-1 text-xs font-medium tracking-wide text-blue-100 uppercase"
                >
                    Laba Bersih Hari Ini
                </p>
                <h2 class="text-3xl font-bold tracking-tight drop-shadow-sm">
                    {{ formatCurrency(today_revenue - today_expenses) }}
                </h2>

                <div
                    class="mt-6 flex items-center justify-between border-t border-white/10 pt-4"
                >
                    <div class="flex gap-4">
                        <div>
                            <p class="mb-0.5 text-[10px] text-blue-200">
                                Pendapatan
                            </p>
                            <p class="text-sm font-semibold">
                                {{ formatCurrency(today_revenue) }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-0.5 text-[10px] text-blue-200">
                                Pengeluaran
                            </p>
                            <p class="text-sm font-semibold text-red-300">
                                -{{ formatCurrency(today_expenses) }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="mb-0.5 text-[10px] text-blue-200">
                            Transaksi
                        </p>
                        <p class="text-sm font-semibold">
                            {{ today_transactions }}x
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div
            class="rounded-[20px] border border-gray-100 bg-white p-5 shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <h3 class="mb-5 text-sm font-bold text-gray-900">Menu Cepat</h3>
            <div class="grid grid-cols-4 gap-x-2 gap-y-6">
                <!-- Kasir -->
                <Link
                    href="/transactions/create"
                    class="group flex flex-col items-center"
                >
                    <div
                        class="mb-2 flex h-[52px] w-[52px] items-center justify-center rounded-[16px] bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md shadow-blue-200/50 transition-all duration-300 group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-blue-300/50"
                    >
                        <!-- Store / Cash register icon -->
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-center text-[11px] leading-tight font-semibold text-gray-700"
                        >Pemasukan</span
                    >
                </Link>
                <!-- Mutasi -->
                <Link
                    href="/transactions"
                    class="group flex flex-col items-center"
                >
                    <div
                        class="mb-2 flex h-[52px] w-[52px] items-center justify-center rounded-[16px] bg-gradient-to-br from-teal-400 to-teal-500 text-white shadow-md shadow-teal-200/50 transition-all duration-300 group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-teal-300/50"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-center text-[11px] leading-tight font-semibold text-gray-700"
                        >Mutasi</span
                    >
                </Link>
                <!-- Produk -->
                <Link href="/products" class="group flex flex-col items-center">
                    <div
                        class="mb-2 flex h-[52px] w-[52px] items-center justify-center rounded-[16px] bg-gradient-to-br from-orange-400 to-orange-500 text-white shadow-md shadow-orange-200/50 transition-all duration-300 group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-orange-300/50"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-center text-[11px] leading-tight font-semibold text-gray-700"
                        >Produk</span
                    >
                </Link>
                <!-- Kategori -->
                <Link
                    href="/categories"
                    class="group flex flex-col items-center"
                >
                    <div
                        class="mb-2 flex h-[52px] w-[52px] items-center justify-center rounded-[16px] bg-gradient-to-br from-indigo-400 to-indigo-500 text-white shadow-md shadow-indigo-200/50 transition-all duration-300 group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-indigo-300/50"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-center text-[11px] leading-tight font-semibold text-gray-700"
                        >Kategori</span
                    >
                </Link>
                <!-- Laporan -->
                <Link href="/reports" class="group flex flex-col items-center">
                    <div
                        class="mb-2 flex h-[52px] w-[52px] items-center justify-center rounded-[16px] bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-md shadow-purple-200/50 transition-all duration-300 group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-purple-300/50"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-center text-[11px] leading-tight font-semibold text-gray-700"
                        >Laporan</span
                    >
                </Link>
                <!-- Pengeluaran -->
                <Link href="/expenses" class="group flex flex-col items-center">
                    <div
                        class="mb-2 flex h-[52px] w-[52px] items-center justify-center rounded-[16px] bg-gradient-to-br from-rose-500 to-rose-600 text-white shadow-md shadow-rose-200/50 transition-all duration-300 group-hover:-translate-y-1 group-hover:scale-105 group-hover:shadow-lg group-hover:shadow-rose-300/50"
                    >
                        <!-- Money out / expense icon -->
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-center text-[11px] leading-tight font-semibold text-gray-700"
                        >Pengeluaran</span
                    >
                </Link>
            </div>
        </div>
    </div>
</template>
