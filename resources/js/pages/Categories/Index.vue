<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';
import { ref } from 'vue';

defineProps<{
    categories: Array<{ id: number, name: string }>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const form = useForm({
    name: '',
});

const editingCategory = ref<number | null>(null);

const addCategory = () => {
    form.post('/categories', {
        onSuccess: () => form.reset(),
    });
};

const deleteCategory = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        form.delete(`/categories/${id}`);
    }
};
</script>

<template>
    <Head title="Kategori Produk" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Kategori</h1>
            <Link href="/products" class="text-sm text-blue-600 font-medium">Lihat Produk &rarr;</Link>
        </div>

        <form @submit.prevent="addCategory" class="flex gap-2">
            <input v-model="form.name" type="text" placeholder="Nama kategori baru" required class="flex-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
            <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 disabled:opacity-50">Tambah</button>
        </form>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <ul class="divide-y divide-gray-100">
                <li v-for="category in categories" :key="category.id" class="p-4 flex items-center justify-between">
                    <span class="font-medium text-gray-800">{{ category.name }}</span>
                    <button @click="deleteCategory(category.id)" class="text-red-500 text-sm hover:bg-red-50 p-2 rounded-lg transition">Hapus</button>
                </li>
                <li v-if="categories.length === 0" class="p-4 text-center text-gray-500 text-sm">
                    Belum ada kategori.
                </li>
            </ul>
        </div>
    </div>
</template>
