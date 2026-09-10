<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    categories: Array<{
        id: number,
        name: string,
        products: Array<{ id: number, name: string, price: number, is_active: boolean }>
    }>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

// Keranjang belanja
const cart = ref<Array<{ product_id: number, product_name: string, price: number, qty: number, subtotal: number }>>([]);

const addToCart = (product: any) => {
    const existing = cart.value.find(item => item.product_id === product.id);
    if (existing) {
        existing.qty++;
        existing.subtotal = existing.qty * existing.price;
    } else {
        cart.value.push({
            product_id: product.id,
            product_name: product.name,
            price: product.price,
            qty: 1,
            subtotal: product.price
        });
    }
};

const updateQty = (index: number, delta: number) => {
    const item = cart.value[index];
    item.qty += delta;
    if (item.qty <= 0) {
        cart.value.splice(index, 1);
    } else {
        item.subtotal = item.qty * item.price;
    }
};

const totalAmount = computed(() => {
    return cart.value.reduce((sum, item) => sum + item.subtotal, 0);
});

const form = useForm({
    total: 0,
    payment_method: 'cash',
    items: [] as any[],
});

const saveTransaction = () => {
    if (cart.value.length === 0) return;

    form.total = totalAmount.value;
    form.items = cart.value;

    form.post('/transactions', {
        onSuccess: () => {
            cart.value = [];
        }
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
};
</script>

<template>
    <Head title="Catat Transaksi" />

    <div class="space-y-6 flex flex-col h-full">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Catat Penjualan</h1>
            <p class="text-xs text-gray-500">Pilih menu dari kategori</p>
        </div>

        <!-- Daftar Menu -->
        <div class="flex-1 space-y-6 overflow-y-auto" :class="cart.length > 0 ? 'pb-[65vh]' : 'pb-32'">
            <div v-for="category in categories" :key="category.id" class="space-y-3">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-blue-600 rounded-full"></div>
                    {{ category.name }}
                </h2>
                <div class="grid grid-cols-2 gap-3">
                    <button v-for="product in category.products" :key="product.id"
                        @click="addToCart(product)"
                        class="bg-white p-3.5 rounded-[16px] border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] text-left hover:border-blue-300 transition active:scale-95 flex flex-col justify-between h-full group relative overflow-hidden">
                        
                        <!-- Soft glow effect -->
                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-full blur-xl -mr-8 -mt-8 transition group-hover:bg-blue-100"></div>
                        
                        <div class="relative z-10">
                            <p class="font-semibold text-gray-800 text-sm leading-snug mb-1">{{ product.name }}</p>
                            <p class="text-blue-600 font-bold text-xs">{{ formatCurrency(product.price) }}</p>
                        </div>
                        
                        <div class="relative z-10 mt-3 self-end">
                            <div class="w-7 h-7 rounded-full bg-gray-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                        </div>
                    </button>
                    <div v-if="category.products.length === 0" class="col-span-2 text-xs text-gray-400 italic bg-gray-50 p-4 rounded-xl text-center border border-dashed border-gray-200">
                        Tidak ada produk aktif di kategori ini.
                    </div>
                </div>
            </div>

            <div v-if="categories.length === 0" class="text-center bg-white p-8 rounded-[20px] border border-dashed border-gray-300 shadow-sm mt-4 flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Belum Ada Produk</h3>
                <p class="text-xs text-gray-500 mb-4 px-4">Silakan tambahkan data di menu Produk terlebih dahulu.</p>
                <Link href="/categories" class="bg-blue-600 text-white text-xs font-bold px-5 py-2.5 rounded-full hover:bg-blue-700 transition shadow-sm">
                    Atur Produk Sekarang
                </Link>
            </div>
        </div>

        <!-- Keranjang Mengambang / Sticky Bottom -->
        <div v-if="cart.length > 0" class="fixed bottom-20 left-4 right-4 bg-white/90 backdrop-blur-md rounded-[24px] border border-white/50 shadow-[0_-4px_25px_rgba(0,0,0,0.1)] z-40 p-4 max-h-[60vh] flex flex-col">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Pesanan ({{ cart.reduce((sum, item) => sum + item.qty, 0) }})</h3>
                </div>
                <button @click="cart = []" class="text-red-500 text-xs font-bold bg-red-50 px-3 py-1.5 rounded-full hover:bg-red-100 transition">Kosongkan</button>
            </div>

            <div class="overflow-y-auto flex-1 mb-4 pr-1 space-y-3">
                <div v-for="(item, index) in cart" :key="item.product_id" class="flex justify-between items-center bg-gray-50/50 p-2.5 rounded-xl border border-gray-100/50">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">{{ item.product_name }}</p>
                        <p class="text-blue-600 font-medium text-xs">{{ formatCurrency(item.price) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center bg-white rounded-full shadow-sm border border-gray-100 p-0.5">
                            <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-800 font-bold transition">&minus;</button>
                            <span class="w-6 text-center text-xs font-bold">{{ item.qty }}</span>
                            <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 font-bold transition">&plus;</button>
                        </div>
                        <span class="font-bold text-gray-900 text-sm w-16 text-right">{{ formatCurrency(item.subtotal) }}</span>
                    </div>
                </div>
            </div>

            <!-- Pilihan Pembayaran -->
            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 mb-2 px-1 uppercase tracking-wider">Metode Pembayaran</p>
                <div class="grid grid-cols-2 gap-2">
                    <button 
                        @click="form.payment_method = 'cash'"
                        class="py-2 px-3 rounded-xl border text-sm font-bold flex items-center justify-center gap-2 transition"
                        :class="form.payment_method === 'cash' ? 'bg-blue-50 border-blue-600 text-blue-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Tunai (Cash)
                    </button>
                    <button 
                        @click="form.payment_method = 'qris'"
                        class="py-2 px-3 rounded-xl border text-sm font-bold flex items-center justify-center gap-2 transition"
                        :class="form.payment_method === 'qris' ? 'bg-blue-50 border-blue-600 text-blue-700' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        QRIS
                    </button>
                </div>
            </div>

            <button @click="saveTransaction" :disabled="form.processing" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3.5 rounded-full flex justify-between px-6 items-center shadow-md shadow-blue-600/20 hover:shadow-lg hover:shadow-blue-600/30 disabled:opacity-50 transition active:scale-[0.98]">
                <span>Bayar Sekarang</span>
                <span class="bg-white/20 px-3 py-1 rounded-full text-sm backdrop-blur-sm">{{ formatCurrency(totalAmount) }}</span>
            </button>
        </div>
    </div>
</template>
