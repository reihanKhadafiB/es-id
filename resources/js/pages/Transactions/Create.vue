<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
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
            <h1 class="text-2xl font-bold text-gray-900">Catat Penjualan</h1>
        </div>

        <!-- Daftar Menu -->
        <div class="flex-1 space-y-6 overflow-y-auto pb-32">
            <div v-for="category in categories" :key="category.id" class="space-y-3">
                <h2 class="font-semibold text-gray-800 border-b border-gray-200 pb-2">{{ category.name }}</h2>
                <div class="grid grid-cols-2 gap-3">
                    <button v-for="product in category.products" :key="product.id" 
                        @click="addToCart(product)"
                        class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm text-left hover:border-blue-300 transition active:scale-95">
                        <p class="font-bold text-gray-800 text-sm leading-tight mb-1">{{ product.name }}</p>
                        <p class="text-blue-600 font-medium text-xs">{{ formatCurrency(product.price) }}</p>
                    </button>
                    <div v-if="category.products.length === 0" class="col-span-2 text-xs text-gray-400 italic">
                        Tidak ada produk aktif di kategori ini.
                    </div>
                </div>
            </div>
            
            <div v-if="categories.length === 0" class="text-center bg-white p-8 rounded-2xl border border-dashed border-gray-300 shadow-sm mt-4">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="font-bold text-gray-800 mb-1">Belum Ada Kategori & Produk</h3>
                <p class="text-sm text-gray-500 mb-4">Silakan tambahkan data di menu Produk terlebih dahulu agar bisa melakukan transaksi.</p>
                <Link href="/categories" class="inline-block bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-blue-700 transition">
                    Atur Produk
                </Link>
            </div>
        </div>

        <!-- Keranjang Mengambang / Sticky Bottom -->
        <div v-if="cart.length > 0" class="fixed bottom-16 left-0 w-full bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-40 p-4 max-h-[50vh] flex flex-col">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-800">Keranjang ({{ cart.reduce((sum, item) => sum + item.qty, 0) }} item)</h3>
                <button @click="cart = []" class="text-red-500 text-xs font-medium">Kosongkan</button>
            </div>
            
            <div class="overflow-y-auto flex-1 mb-4 pr-1 space-y-3">
                <div v-for="(item, index) in cart" :key="item.product_id" class="flex justify-between items-center text-sm">
                    <div class="flex-1 leading-tight">
                        <p class="font-medium text-gray-800">{{ item.product_name }}</p>
                        <p class="text-gray-500 text-xs">{{ formatCurrency(item.price) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center bg-gray-100 rounded-lg overflow-hidden">
                            <button @click="updateQty(index, -1)" class="px-2 py-1 text-gray-600 hover:bg-gray-200 font-bold">&minus;</button>
                            <span class="w-6 text-center font-medium">{{ item.qty }}</span>
                            <button @click="updateQty(index, 1)" class="px-2 py-1 text-gray-600 hover:bg-gray-200 font-bold">&plus;</button>
                        </div>
                        <span class="font-bold text-gray-900 w-16 text-right">{{ formatCurrency(item.subtotal) }}</span>
                    </div>
                </div>
            </div>
            
            <button @click="saveTransaction" :disabled="form.processing" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl flex justify-between px-4 items-center hover:bg-blue-700 disabled:opacity-50 transition">
                <span>Simpan Transaksi</span>
                <span class="bg-blue-800/30 px-2 py-1 rounded">{{ formatCurrency(totalAmount) }}</span>
            </button>
        </div>
    </div>
</template>
