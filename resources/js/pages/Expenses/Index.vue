<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    expenses: Array<{
        id: number;
        description: string;
        amount: number;
        expense_date: string;
    }>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const form = useForm({
    description: '',
    amount: '',
    expense_date: new Date().toISOString().split('T')[0],
});

const addExpense = () => {
    form.post('/expenses', {
        onSuccess: () => form.reset('description', 'amount'),
    });
};

const deleteExpense = (id: number) => {
    if (confirm('Hapus catatan pengeluaran ini?')) {
        useForm({}).delete(`/expenses/${id}`);
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
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Pengeluaran Operasional" />

    <div class="space-y-6 pb-20">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Pengeluaran</h1>
            <p class="text-xs text-gray-500">Catat biaya operasional harian</p>
        </div>

        <form
            @submit.prevent="addExpense"
            class="space-y-4 rounded-[20px] border border-gray-100 bg-white p-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <h2 class="text-sm font-semibold text-gray-800">
                Tambah Pengeluaran
            </h2>
            <div>
                <label
                    class="mb-1 block text-[11px] font-semibold tracking-wider text-gray-500 uppercase"
                    >Tanggal</label
                >
                <input
                    v-model="form.expense_date"
                    type="date"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <div>
                <label
                    class="mb-1 block text-[11px] font-semibold tracking-wider text-gray-500 uppercase"
                    >Deskripsi / Catatan</label
                >
                <input
                    v-model="form.description"
                    type="text"
                    placeholder="Contoh: Beli es batu, sedotan"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <div>
                <label
                    class="mb-1 block text-[11px] font-semibold tracking-wider text-gray-500 uppercase"
                    >Nominal (Rp)</label
                >
                <input
                    v-model="form.amount"
                    type="number"
                    placeholder="Contoh: 15000"
                    required
                    min="1"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:opacity-50"
            >
                Simpan Pengeluaran
            </button>
        </form>

        <div
            class="divide-y divide-gray-100 overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <div
                class="flex items-center justify-between border-b border-gray-100 bg-gray-50 p-3"
            >
                <h3
                    class="text-xs font-bold tracking-wider text-gray-700 uppercase"
                >
                    Riwayat Pengeluaran
                </h3>
            </div>
            <div
                v-for="expense in expenses"
                :key="expense.id"
                class="flex flex-col gap-2 p-4 transition hover:bg-gray-50"
            >
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-bold text-gray-800">
                            {{ expense.description }}
                        </h4>
                        <p class="mt-0.5 text-[11px] text-gray-500">
                            {{ formatDate(expense.expense_date) }}
                        </p>
                    </div>
                    <span class="font-bold text-red-500"
                        >-{{ formatCurrency(expense.amount) }}</span
                    >
                </div>
                <div
                    class="mt-2 flex justify-end border-t border-gray-100 pt-2"
                >
                    <button
                        @click="deleteExpense(expense.id)"
                        class="rounded-lg bg-red-50 px-2 py-1.5 text-[11px] font-bold text-red-500 transition hover:bg-red-100"
                    >
                        Hapus
                    </button>
                </div>
            </div>
            <div
                v-if="expenses.length === 0"
                class="p-8 text-center text-sm text-gray-500"
            >
                Belum ada data pengeluaran.
            </div>
        </div>
    </div>
</template>
