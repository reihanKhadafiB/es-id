<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    reports: Array<{ date: string, total_revenue: number, total_transactions: number, breakdown: Record<string, number> }>;
    period: string;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
};

const formatDate = (dateString: string) => {
    const d = new Date(dateString);
    if (props.period === 'monthly') {
        return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
    }
    if (props.period === 'weekly') {
        const dEnd = new Date(d);
        dEnd.setDate(d.getDate() + 6);
        return `${d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })} - ${dEnd.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}`;
    }
    return d.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
};

const periods = [
    { value: 'daily', label: 'Harian' },
    { value: 'weekly', label: 'Mingguan' },
    { value: 'monthly', label: 'Bulanan' },
];
</script>

<template>
    <Head title="Laporan Penjualan" />

    <div class="space-y-6 flex flex-col h-full">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan</h1>
            <p class="text-sm text-gray-500">Rekap penjualan (exclude void)</p>
        </div>

        <!-- Tab Navigasi -->
        <div class="flex p-1 bg-gray-200 rounded-xl">
            <Link v-for="tab in periods" :key="tab.value" :href="'/reports?period=' + tab.value"
                class="flex-1 text-center py-2 text-sm font-medium rounded-lg transition"
                :class="period === tab.value ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                {{ tab.label }}
            </Link>
        </div>

        <!-- Daftar Laporan -->
        <div class="space-y-4 pb-16">
            <div v-for="report in reports" :key="report.date" class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-gray-800">{{ formatDate(report.date) }}</h3>
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2 py-1 rounded">{{ report.total_transactions }} Trx</span>
                </div>
                <div class="flex justify-between items-end border-t border-gray-50 pt-2 mt-2">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Total Pendapatan</span>
                    <span class="font-bold text-xl text-green-600">{{ formatCurrency(report.total_revenue) }}</span>
                </div>
                <!-- Breakdown Produk -->
                <div v-if="Object.keys(report.breakdown).length > 0" class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-600 mb-2">Rincian Produk Terjual:</p>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="(qty, productName) in report.breakdown" :key="productName" class="inline-flex items-center bg-gray-50 border border-gray-200 text-gray-700 text-[11px] px-2 py-1 rounded">
                            {{ productName }} <span class="ml-1 font-bold">x{{ qty }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <div v-if="reports.length === 0" class="text-center p-8 text-gray-500 text-sm">
                Tidak ada data laporan untuk periode ini.
            </div>
        </div>
    </div>
</template>
