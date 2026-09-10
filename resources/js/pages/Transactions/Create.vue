<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    categories: Array<{
        id: number;
        name: string;
        products: Array<{
            id: number;
            name: string;
            price: number;
            is_active: boolean;
        }>;
    }>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

// Keranjang belanja
const cart = ref<
    Array<{
        product_id: number;
        product_name: string;
        price: number;
        qty: number;
        subtotal: number;
    }>
>([]);

const addToCart = (product: any) => {
    const existing = cart.value.find((item) => item.product_id === product.id);
    if (existing) {
        existing.qty++;
        existing.subtotal = existing.qty * existing.price;
    } else {
        cart.value.push({
            product_id: product.id,
            product_name: product.name,
            price: product.price,
            qty: 1,
            subtotal: product.price,
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
        },
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Catat Transaksi" />

    <div class="flex h-full flex-col space-y-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Catat Penjualan</h1>
            <p class="text-xs text-gray-500">Pilih menu dari kategori</p>
        </div>

        <!-- Daftar Menu -->
        <div
            class="flex-1 space-y-6 overflow-y-auto"
            :class="cart.length > 0 ? 'pb-[65vh]' : 'pb-32'"
        >
            <div
                v-for="category in categories"
                :key="category.id"
                class="space-y-3"
            >
                <h2 class="flex items-center gap-2 font-semibold text-gray-800">
                    <div class="h-4 w-1.5 rounded-full bg-blue-600"></div>
                    {{ category.name }}
                </h2>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        v-for="product in category.products"
                        :key="product.id"
                        @click="addToCart(product)"
                        class="group relative flex h-full flex-col justify-between overflow-hidden rounded-[16px] border border-gray-100 bg-white p-3.5 text-left shadow-[0_2px_10px_rgba(0,0,0,0.02)] transition hover:border-blue-300 active:scale-95"
                    >
                        <!-- Soft glow effect -->
                        <div
                            class="absolute top-0 right-0 -mt-8 -mr-8 h-16 w-16 rounded-full bg-blue-50 blur-xl transition group-hover:bg-blue-100"
                        ></div>

                        <div class="relative z-10">
                            <p
                                class="mb-1 text-sm leading-snug font-semibold text-gray-800"
                            >
                                {{ product.name }}
                            </p>
                            <p class="text-xs font-bold text-blue-600">
                                {{ formatCurrency(product.price) }}
                            </p>
                        </div>

                        <div class="relative z-10 mt-3 self-end">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-full bg-gray-50 text-blue-600 transition-colors group-hover:bg-blue-600 group-hover:text-white"
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
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div
                        v-if="category.products.length === 0"
                        class="col-span-2 rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4 text-center text-xs text-gray-400 italic"
                    >
                        Tidak ada produk aktif di kategori ini.
                    </div>
                </div>
            </div>

            <div
                v-if="categories.length === 0"
                class="mt-4 flex flex-col items-center rounded-[20px] border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm"
            >
                <div
                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50"
                >
                    <svg
                        class="h-8 w-8 text-blue-300"
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
                <h3 class="mb-1 font-bold text-gray-800">Belum Ada Produk</h3>
                <p class="mb-4 px-4 text-xs text-gray-500">
                    Silakan tambahkan data di menu Produk terlebih dahulu.
                </p>
                <Link
                    href="/categories"
                    class="rounded-full bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700"
                >
                    Atur Produk Sekarang
                </Link>
            </div>
        </div>

        <!-- Keranjang Mengambang / Sticky Bottom -->
        <div
            v-if="cart.length > 0"
            class="fixed right-4 bottom-20 left-4 z-40 flex max-h-[60vh] flex-col rounded-[24px] border border-white/50 bg-white/90 p-4 shadow-[0_-4px_25px_rgba(0,0,0,0.1)] backdrop-blur-md"
        >
            <div
                class="mb-4 flex items-center justify-between border-b border-gray-100 pb-2"
            >
                <div class="flex items-center gap-2">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600"
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
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">
                        Pesanan ({{
                            cart.reduce((sum, item) => sum + item.qty, 0)
                        }})
                    </h3>
                </div>
                <button
                    @click="cart = []"
                    class="rounded-full bg-red-50 px-3 py-1.5 text-xs font-bold text-red-500 transition hover:bg-red-100"
                >
                    Kosongkan
                </button>
            </div>

            <div class="mb-4 flex-1 space-y-3 overflow-y-auto pr-1">
                <div
                    v-for="(item, index) in cart"
                    :key="item.product_id"
                    class="flex items-center justify-between rounded-xl border border-gray-100/50 bg-gray-50/50 p-2.5"
                >
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ item.product_name }}
                        </p>
                        <p class="text-xs font-medium text-blue-600">
                            {{ formatCurrency(item.price) }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center rounded-full border border-gray-100 bg-white p-0.5 shadow-sm"
                        >
                            <button
                                @click="updateQty(index, -1)"
                                class="flex h-6 w-6 items-center justify-center rounded-full font-bold text-gray-500 transition hover:bg-gray-100 hover:text-gray-800"
                            >
                                &minus;
                            </button>
                            <span class="w-6 text-center text-xs font-bold">{{
                                item.qty
                            }}</span>
                            <button
                                @click="updateQty(index, 1)"
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 font-bold text-blue-600 transition hover:bg-blue-100"
                            >
                                &plus;
                            </button>
                        </div>
                        <span
                            class="w-16 text-right text-sm font-bold text-gray-900"
                            >{{ formatCurrency(item.subtotal) }}</span
                        >
                    </div>
                </div>
            </div>

            <!-- Pilihan Pembayaran -->
            <div class="mb-4">
                <p
                    class="mb-2 px-1 text-xs font-semibold tracking-wider text-gray-500 uppercase"
                >
                    Metode Pembayaran
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        @click="form.payment_method = 'cash'"
                        class="flex items-center justify-center gap-2 rounded-xl border px-3 py-2 text-sm font-bold transition"
                        :class="
                            form.payment_method === 'cash'
                                ? 'border-blue-600 bg-blue-50 text-blue-700'
                                : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
                        "
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
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                            ></path>
                        </svg>
                        Tunai (Cash)
                    </button>
                    <button
                        @click="form.payment_method = 'qris'"
                        class="flex items-center justify-center gap-2 rounded-xl border px-3 py-2 text-sm font-bold transition"
                        :class="
                            form.payment_method === 'qris'
                                ? 'border-blue-600 bg-blue-50 text-blue-700'
                                : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
                        "
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
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                            ></path>
                        </svg>
                        QRIS
                    </button>
                </div>
            </div>

            <button
                @click="saveTransaction"
                :disabled="form.processing"
                class="flex w-full items-center justify-between rounded-full bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3.5 font-bold text-white shadow-md shadow-blue-600/20 transition hover:shadow-lg hover:shadow-blue-600/30 active:scale-[0.98] disabled:opacity-50"
            >
                <span>Bayar Sekarang</span>
                <span
                    class="rounded-full bg-white/20 px-3 py-1 text-sm backdrop-blur-sm"
                    >{{ formatCurrency(totalAmount) }}</span
                >
            </button>
        </div>
    </div>
</template>
