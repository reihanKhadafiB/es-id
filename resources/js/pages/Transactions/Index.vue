<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    transactions: Array<any>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const voidTransaction = (id: number) => {
    if (confirm('Apakah Anda yakin ingin MEMBATALKAN transaksi ini? Data tidak akan dihapus tetapi tidak akan dihitung di laporan.')) {
        useForm({}).put(`/transactions/${id}/void`);
    }
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleString('id-ID', {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Riwayat Transaksi" />

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Penjualan</h1>
            <p class="text-sm text-gray-500">Daftar transaksi terbaru</p>
        </div>

        <div class="space-y-4">
            <div v-for="transaction in transactions" :key="transaction.id" 
                class="bg-white rounded-2xl p-4 border shadow-sm relative overflow-hidden"
                :class="transaction.status === 'voided' ? 'border-red-200 bg-red-50/30' : 'border-gray-100'">
                
                <!-- Void Stamp -->
                <div v-if="transaction.status === 'voided'" class="absolute -right-6 top-6 bg-red-500 text-white text-[10px] font-bold py-1 px-8 transform rotate-45">
                    VOIDED
                </div>

                <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                    <div>
                        <p class="font-mono text-xs text-gray-500">#TRX-{{ String(transaction.id).padStart(5, '0') }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ formatDate(transaction.created_at) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">{{ formatCurrency(transaction.total) }}</p>
                    </div>
                </div>
                
                <div class="space-y-1 mb-4">
                    <div v-for="item in transaction.items" :key="item.id" class="flex justify-between text-xs">
                        <span class="text-gray-700"><span class="text-gray-400">{{ item.qty }}x</span> {{ item.product_name }}</span>
                        <span class="text-gray-600 font-medium">{{ formatCurrency(item.subtotal) }}</span>
                    </div>
                </div>

                <div v-if="transaction.status !== 'voided'" class="flex justify-end">
                    <button @click="voidTransaction(transaction.id)" class="text-xs font-medium text-red-600 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                        Batalkan Transaksi (Void)
                    </button>
                </div>
                <div v-else class="text-xs text-red-500 italic text-right">
                    Dibatalkan pada {{ formatDate(transaction.voided_at) }}
                </div>
            </div>
            
            <div v-if="transactions.length === 0" class="text-center p-8 text-gray-500 text-sm">
                Belum ada transaksi.
            </div>
        </div>
    </div>
</template>
