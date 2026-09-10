<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    reports: Array<{
        date: string;
        total_revenue: number;
        total_expenses: number;
        net_profit: number;
        total_transactions: number;
        breakdown: Record<string, number>;
    }>;
    period: string;
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

const formatDate = (dateString: string) => {
    const d = new Date(dateString);
    if (props.period === 'monthly') {
        return d.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
        });
    }
    if (props.period === 'weekly') {
        const dEnd = new Date(d);
        dEnd.setDate(d.getDate() + 6);
        return `${d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })} - ${dEnd.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}`;
    }
    return d.toLocaleDateString('id-ID', {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });
};

const periods = [
    { value: 'daily', label: 'Harian' },
    { value: 'weekly', label: 'Mingguan' },
    { value: 'monthly', label: 'Bulanan' },
];
</script>

<template>
    <Head title="Laporan Penjualan" />

    <div class="flex h-full flex-col space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Laporan</h1>
                <p class="text-xs text-gray-500">
                    Rekap penjualan & pengeluaran
                </p>
            </div>
            <a
                :href="'/reports/export?period=' + period"
                target="_blank"
                class="flex items-center gap-1.5 rounded-lg bg-green-600 px-3 py-1.5 text-[11px] font-bold text-white shadow-sm transition hover:bg-green-700"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                Export Excel
            </a>
        </div>

        <!-- Tab Navigasi -->
        <div class="flex rounded-xl bg-gray-200 p-1">
            <Link
                v-for="tab in periods"
                :key="tab.value"
                :href="'/reports?period=' + tab.value"
                class="flex-1 rounded-lg py-2 text-center text-sm font-medium transition"
                :class="
                    period === tab.value
                        ? 'bg-white text-gray-900 shadow-sm'
                        : 'text-gray-500 hover:text-gray-700'
                "
            >
                {{ tab.label }}
            </Link>
        </div>

        <!-- Daftar Laporan -->
        <div class="space-y-4 pb-16">
            <div
                v-for="report in reports"
                :key="report.date"
                class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm"
            >
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">
                        {{ formatDate(report.date) }}
                    </h3>
                    <span
                        class="rounded bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700"
                        >{{ report.total_transactions }} Transaksi</span
                    >
                </div>
                <div
                    class="mt-2 flex flex-col gap-2 border-t border-gray-50 pt-2"
                >
                    <div class="flex items-end justify-between">
                        <span
                            class="text-xs tracking-wider text-gray-500 uppercase"
                            >Pendapatan</span
                        >
                        <span class="text-sm font-semibold text-gray-800">{{
                            formatCurrency(report.total_revenue)
                        }}</span>
                    </div>
                    <div class="flex items-end justify-between">
                        <span
                            class="text-xs tracking-wider text-gray-500 uppercase"
                            >Pengeluaran</span
                        >
                        <span class="text-sm font-semibold text-red-500"
                            >-{{ formatCurrency(report.total_expenses) }}</span
                        >
                    </div>
                    <div
                        class="flex items-end justify-between border-t border-dashed border-gray-200 pt-2"
                    >
                        <span
                            class="text-[11px] font-bold tracking-wider text-gray-600 uppercase"
                            >Laba Bersih</span
                        >
                        <span class="text-xl font-bold text-green-600">{{
                            formatCurrency(report.net_profit)
                        }}</span>
                    </div>
                </div>
                <!-- Breakdown Produk -->
                <div
                    v-if="Object.keys(report.breakdown).length > 0"
                    class="mt-3 border-t border-gray-100 pt-3"
                >
                    <p class="mb-2 text-xs font-semibold text-gray-600">
                        Rincian Produk Terjual:
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(qty, productName) in report.breakdown"
                            :key="productName"
                            class="inline-flex items-center rounded border border-gray-200 bg-gray-50 px-2 py-1 text-[11px] text-gray-700"
                        >
                            {{ productName }}
                            <span class="ml-1 font-bold">x{{ qty }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <div
                v-if="reports.length === 0"
                class="p-8 text-center text-sm text-gray-500"
            >
                Tidak ada data laporan untuk periode ini.
            </div>
        </div>
    </div>
</template>
