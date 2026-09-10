<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';
import { ref } from 'vue';

const props = defineProps<{
    products: Array<{ id: number, category_id: number, name: string, price: number, is_active: boolean, category: { name: string } }>;
    categories: Array<{ id: number, name: string }>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const form = useForm({
    category_id: '',
    name: '',
    price: '',
    is_active: true,
});

const addProduct = () => {
    form.post('/products', {
        onSuccess: () => form.reset('name', 'price'),
    });
};

const toggleStatus = (product: any) => {
    useForm({ ...product, is_active: !product.is_active }).put(`/products/${product.id}`);
};

const deleteProduct = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        useForm({}).delete(`/products/${id}`);
    }
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
};
</script>

<template>
    <Head title="Manajemen Produk" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Produk</h1>
            <Link href="/categories" class="text-sm text-blue-600 font-medium">&larr; Atur Kategori</Link>
        </div>

        <form @submit.prevent="addProduct" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h2 class="font-semibold text-gray-800 text-sm">Tambah Produk Baru</h2>
            <div>
                <select v-model="form.category_id" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="" disabled>Pilih Kategori</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
            </div>
            <div>
                <input v-model="form.name" type="text" placeholder="Nama Produk" required class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <input v-model="form.price" type="number" placeholder="Harga (Rp)" required min="0" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
            </div>
            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 disabled:opacity-50">Simpan Produk</button>
        </form>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-100">
            <div v-for="product in products" :key="product.id" class="p-4 flex flex-col gap-2">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ product.name }}</h3>
                        <p class="text-xs text-gray-500">{{ product.category.name }}</p>
                    </div>
                    <span class="font-bold text-blue-600">{{ formatCurrency(product.price) }}</span>
                </div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-50">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-600">
                        <input type="checkbox" :checked="product.is_active" @change="toggleStatus(product)" class="rounded text-blue-600 focus:ring-blue-500">
                        Aktif Dijual
                    </label>
                    <button @click="deleteProduct(product.id)" class="text-red-500 text-xs font-medium bg-red-50 px-2 py-1 rounded hover:bg-red-100">Hapus</button>
                </div>
            </div>
            <div v-if="products.length === 0" class="p-8 text-center text-gray-500 text-sm">
                Belum ada produk. Tambahkan kategori terlebih dahulu.
            </div>
        </div>
    </div>
</template>
