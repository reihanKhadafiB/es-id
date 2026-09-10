<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    transactions: Array<any>;
    selectedMonth: string;
    availableMonths: Array<string>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const changeMonth = (e: any) => {
    router.get(
        '/transactions',
        { month: e.target.value },
        { preserveState: true },
    );
};

const voidTransaction = (id: number) => {
    if (
        confirm(
            'Apakah Anda yakin ingin MEMBATALKAN transaksi ini? Data tidak akan dihapus tetapi tidak akan dihitung di laporan.',
        )
    ) {
        useForm({}).put(`/transactions/${id}/void`);
    }
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatMonthLabel = (yyyyMm: string) => {
    const [y, m] = yyyyMm.split('-');
    const date = new Date(parseInt(y), parseInt(m) - 1, 1);
    return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
};
</script>

<template>
    <Head title="Riwayat Transaksi" />

    <div class="space-y-4 pb-20">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    Mutasi Transaksi
                </h1>
                <p class="text-xs text-gray-500">
                    Histori penjualan dan pembatalan
                </p>
            </div>
            <a
                :href="'/transactions/export?month=' + selectedMonth"
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
                Export
            </a>
        </div>

        <!-- Month Filter (Horizontal Pills) -->
        <div class="scrollbar-hide -mx-4 overflow-x-auto px-4">
            <div class="flex gap-2">
                <button
                    v-for="m in availableMonths"
                    :key="m"
                    @click="changeMonth({ target: { value: m } })"
                    class="rounded-full border px-4 py-2 text-[13px] font-semibold whitespace-nowrap transition-all duration-200"
                    :class="
                        selectedMonth === m
                            ? 'border-blue-600 bg-blue-600 text-white shadow-md'
                            : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
                    "
                >
                    {{ formatMonthLabel(m) }}
                </button>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <div class="divide-y divide-gray-100">
                <div
                    v-for="transaction in transactions"
                    :key="transaction.id"
                    class="relative p-4 transition-colors hover:bg-gray-50"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-start gap-3">
                            <div class="mt-1">
                                <div
                                    v-if="transaction.status !== 'voided'"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-green-50 text-green-600"
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
                                            d="M7 11l5-5m0 0l5 5m-5-5v12"
                                        ></path>
                                    </svg>
                                </div>
                                <div
                                    v-else
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-red-50 text-red-500"
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
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <p
                                        class="text-[13px] font-semibold text-gray-900"
                                    >
                                        Penjualan #ESID-{{
                                            String(transaction.id).padStart(
                                                5,
                                                '0',
                                            )
                                        }}
                                    </p>
                                    <span
                                        v-if="
                                            transaction.payment_method ===
                                            'qris'
                                        "
                                        class="rounded bg-blue-100 px-1.5 py-0.5 text-[9px] font-bold text-blue-700 uppercase"
                                        >QRIS</span
                                    >
                                    <span
                                        v-else
                                        class="rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-bold text-gray-600 uppercase"
                                        >CASH</span
                                    >
                                </div>
                                <p class="mt-0.5 text-[11px] text-gray-500">
                                    {{ formatDate(transaction.created_at) }}
                                </p>
                                <p
                                    v-if="transaction.status === 'voided'"
                                    class="mt-0.5 text-[10px] font-medium text-red-500"
                                >
                                    Batal:
                                    {{ formatDate(transaction.voided_at) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end text-right">
                            <p
                                class="text-sm font-bold"
                                :class="
                                    transaction.status === 'voided'
                                        ? 'text-gray-400 line-through'
                                        : 'text-green-600'
                                "
                            >
                                +{{ formatCurrency(transaction.total) }}
                            </p>
                            <button
                                v-if="transaction.status !== 'voided'"
                                @click="voidTransaction(transaction.id)"
                                class="mt-1 text-[14px] font-medium text-red-500 hover:underline"
                            >
                                Batalkan
                            </button>
                        </div>
                    </div>

                    <!-- Items Details -->
                    <div class="mt-3 pl-11">
                        <div
                            class="space-y-1 rounded-lg bg-gray-50 p-2.5 text-[11px]"
                        >
                            <div
                                v-for="item in transaction.items"
                                :key="item.id"
                                class="flex justify-between text-gray-600"
                            >
                                <span
                                    ><span class="text-gray-400"
                                        >{{ item.qty }}x</span
                                    >
                                    {{ item.product_name }}</span
                                >
                                <span class="font-medium">{{
                                    formatCurrency(item.subtotal)
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="transactions.length === 0"
                    class="flex flex-col items-center p-8 text-center text-sm text-gray-400"
                >
                    <svg
                        class="mb-3 h-12 w-12 text-gray-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        ></path>
                    </svg>
                    Belum ada riwayat mutasi.
                </div>
            </div>
        </div>
    </div>
</template>
