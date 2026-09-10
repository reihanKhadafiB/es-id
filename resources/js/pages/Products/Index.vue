<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';
import { ref } from 'vue';

const props = defineProps<{
    products: Array<{
        id: number;
        category_id: number;
        name: string;
        price: number;
        is_active: boolean;
        category: { name: string };
    }>;
    categories: Array<{ id: number; name: string }>;
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

const editForm = useForm({
    category_id: '',
    name: '',
    price: '',
    is_active: true,
});

const editingProduct = ref<number | null>(null);

const addProduct = () => {
    form.post('/products', {
        onSuccess: () => form.reset('name', 'price'),
    });
};

const startEdit = (product: any) => {
    editingProduct.value = product.id;
    editForm.category_id = product.category_id;
    editForm.name = product.name;
    editForm.price = product.price;
    editForm.is_active = product.is_active;
};

const updateProduct = (id: number) => {
    editForm.put(`/products/${id}`, {
        onSuccess: () => {
            editingProduct.value = null;
        },
    });
};

const toggleStatus = (product: any) => {
    useForm({ ...product, is_active: !product.is_active }).put(
        `/products/${product.id}`,
    );
};

const deleteProduct = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        useForm({}).delete(`/products/${id}`);
    }
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
    <Head title="Manajemen Produk" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">Produk</h1>
            <!-- <Link href="/categories" class="text-xs text-blue-600 font-medium bg-blue-50 px-3 py-1.5 rounded-full hover:bg-blue-100 transition">&larr; Atur Kategori</Link> -->
        </div>

        <form
            @submit.prevent="addProduct"
            class="space-y-4 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm"
        >
            <h2 class="text-sm font-semibold text-gray-800">
                Tambah Produk Baru
            </h2>
            <div>
                <select
                    v-model="form.category_id"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="" disabled>Pilih Kategori</option>
                    <option
                        v-for="cat in categories"
                        :key="cat.id"
                        :value="cat.id"
                    >
                        {{ cat.name }}
                    </option>
                </select>
            </div>
            <div>
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Nama Produk"
                    required
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <div>
                <input
                    v-model="form.price"
                    type="number"
                    placeholder="Harga (Rp)"
                    required
                    min="0"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                />
            </div>
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
            >
                Simpan Produk
            </button>
        </form>

        <div
            class="divide-y divide-gray-100 overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <div
                v-for="product in products"
                :key="product.id"
                class="flex flex-col gap-2 p-4 transition hover:bg-gray-50"
            >
                <!-- Edit Mode -->
                <div
                    v-if="editingProduct === product.id"
                    class="flex flex-col gap-2"
                >
                    <select
                        v-model="editForm.category_id"
                        class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.name }}
                        </option>
                    </select>
                    <input
                        v-model="editForm.name"
                        type="text"
                        class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                    <input
                        v-model="editForm.price"
                        type="number"
                        class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                    <div class="mt-1 flex gap-2">
                        <button
                            @click="updateProduct(product.id)"
                            class="flex-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700"
                        >
                            Simpan
                        </button>
                        <button
                            @click="editingProduct = null"
                            class="flex-1 rounded-lg bg-gray-200 px-3 py-1.5 text-xs font-bold text-gray-500 hover:bg-gray-300"
                        >
                            Batal
                        </button>
                    </div>
                </div>

                <!-- View Mode -->
                <div v-else>
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800">
                                {{ product.name }}
                            </h3>
                            <p class="mt-0.5 text-[11px] text-gray-500">
                                {{ product.category.name }}
                            </p>
                        </div>
                        <span class="font-bold text-blue-600">{{
                            formatCurrency(product.price)
                        }}</span>
                    </div>
                    <div
                        class="mt-3 flex items-center justify-between border-t border-gray-100/60 pt-3"
                    >
                        <label
                            class="flex cursor-pointer items-center gap-2 text-[12px] font-medium text-gray-600"
                        >
                            <input
                                type="checkbox"
                                :checked="product.is_active"
                                @change="toggleStatus(product)"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            />
                            Aktif Dijual
                        </label>
                        <div class="flex items-center gap-2">
                            <button
                                @click="startEdit(product)"
                                class="rounded-lg bg-blue-50 px-2 py-1.5 text-[11px] font-bold text-blue-500 transition hover:bg-blue-100"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteProduct(product.id)"
                                class="rounded-lg bg-red-50 px-2 py-1.5 text-[11px] font-bold text-red-500 transition hover:bg-red-100"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div
                v-if="products.length === 0"
                class="p-8 text-center text-sm text-gray-500"
            >
                Belum ada produk. Tambahkan kategori terlebih dahulu.
            </div>
        </div>
    </div>
</template>
