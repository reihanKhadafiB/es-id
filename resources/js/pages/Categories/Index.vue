<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';
import { ref } from 'vue';

defineProps<{
    categories: Array<{ id: number; name: string }>;
}>();

defineOptions({
    layout: MobileAppLayout,
});

const form = useForm({
    name: '',
});

const editForm = useForm({
    name: '',
});

const editingCategory = ref<number | null>(null);

const addCategory = () => {
    form.post('/categories', {
        onSuccess: () => form.reset(),
    });
};

const startEdit = (category: any) => {
    editingCategory.value = category.id;
    editForm.name = category.name;
};

const updateCategory = (id: number) => {
    editForm.put(`/categories/${id}`, {
        onSuccess: () => {
            editingCategory.value = null;
        },
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
            <h1 class="text-xl font-bold text-gray-900">Kategori</h1>
        </div>

        <form
            @submit.prevent="addCategory"
            class="flex items-center gap-2 rounded-[20px] border border-gray-100 bg-white p-4 shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <div
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600"
            >
                <svg
                    class="h-5 w-5"
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
            <input
                v-model="form.name"
                type="text"
                placeholder="Buat kategori baru..."
                required
                class="flex-1 border-0 bg-transparent text-sm font-medium text-gray-900 placeholder-gray-400 focus:ring-0"
            />
            <button
                type="submit"
                :disabled="form.processing"
                class="rounded-full px-3 py-2 text-sm font-bold text-blue-600 transition hover:bg-blue-50 disabled:opacity-50"
            >
                Simpan
            </button>
        </form>

        <div
            class="overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-[0_4px_20px_rgba(0,0,0,0.03)]"
        >
            <ul class="divide-y divide-gray-50">
                <li
                    v-for="category in categories"
                    :key="category.id"
                    class="p-4 transition hover:bg-gray-50"
                >
                    <!-- Edit Mode -->
                    <div
                        v-if="editingCategory === category.id"
                        class="flex items-center gap-2"
                    >
                        <input
                            v-model="editForm.name"
                            type="text"
                            required
                            class="flex-1 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                        <button
                            @click="updateCategory(category.id)"
                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700"
                        >
                            Simpan
                        </button>
                        <button
                            @click="editingCategory = null"
                            class="rounded-lg bg-gray-200 px-3 py-1.5 text-xs font-bold text-gray-500 hover:bg-gray-300"
                        >
                            Batal
                        </button>
                    </div>

                    <!-- View Mode -->
                    <div v-else class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-100 bg-gray-50 text-gray-500"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16"
                                    ></path>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">{{
                                category.name
                            }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                @click="startEdit(category)"
                                class="flex items-center justify-center rounded-full p-2 text-blue-500 transition hover:bg-blue-50"
                                title="Edit"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                    ></path>
                                </svg>
                            </button>
                            <button
                                @click="deleteCategory(category.id)"
                                class="flex items-center justify-center rounded-full p-2 text-red-500 transition hover:bg-red-50"
                                title="Hapus"
                            >
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    ></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </li>
                <li
                    v-if="categories.length === 0"
                    class="flex flex-col items-center justify-center p-8 text-sm text-gray-400"
                >
                    <svg
                        class="mb-2 h-12 w-12 text-gray-200"
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
                    Belum ada kategori.
                </li>
            </ul>
        </div>
    </div>
</template>
