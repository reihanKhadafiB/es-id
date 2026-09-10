<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    expenses: Array<{ id: number, description: string, amount: number, expense_date: string }>;
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
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric', month: 'short', day: 'numeric'
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

        <form @submit.prevent="addExpense" class="bg-white p-4 rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] space-y-4">
            <h2 class="font-semibold text-gray-800 text-sm">Tambah Pengeluaran</h2>
            <div>
                <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Tanggal</label>
                <input v-model="form.expense_date" type="date" required class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Deskripsi / Catatan</label>
                <input v-model="form.description" type="text" placeholder="Contoh: Beli es batu, sedotan" required class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Nominal (Rp)</label>
                <input v-model="form.amount" type="number" placeholder="Contoh: 15000" required min="1" class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 disabled:opacity-50 transition">Simpan Pengeluaran</button>
        </form>

        <div class="bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] divide-y divide-gray-100 overflow-hidden">
            <div class="p-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 text-xs uppercase tracking-wider">Riwayat Pengeluaran</h3>
            </div>
            <div v-for="expense in expenses" :key="expense.id" class="p-4 flex flex-col gap-2 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ expense.description }}</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">{{ formatDate(expense.expense_date) }}</p>
                    </div>
                    <span class="font-bold text-red-500">-{{ formatCurrency(expense.amount) }}</span>
                </div>
                <div class="flex justify-end mt-2 pt-2 border-t border-gray-100">
                    <button @click="deleteExpense(expense.id)" class="text-red-500 text-[11px] font-bold bg-red-50 px-2 py-1.5 rounded-lg hover:bg-red-100 transition">Hapus</button>
                </div>
            </div>
            <div v-if="expenses.length === 0" class="p-8 text-center text-gray-500 text-sm">
                Belum ada data pengeluaran.
            </div>
        </div>
    </div>
</template>
