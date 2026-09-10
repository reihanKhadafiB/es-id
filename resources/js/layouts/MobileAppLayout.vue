<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

// Gunakan computed agar reaktif saat navigasi antar halaman
const navigation = computed(() => {
    const url = page.url;
    return [
        {
            name: 'Dashboard',
            href: '/dashboard',
            icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            current: url.startsWith('/dashboard'),
        },
        {
            name: 'Catat',
            href: '/transactions/create',
            icon: 'M12 6v6m0 0v6m0-6h6m-6 0H6',
            current: url.startsWith('/transactions/create'),
        },
        {
            name: 'Riwayat',
            href: '/transactions',
            icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            current: url === '/transactions',
        },
        {
            name: 'Laporan',
            href: '/reports',
            icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            current: url.startsWith('/reports'),
        },
        {
            name: 'Produk',
            href: '/products',
            icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
            current: url.startsWith('/products') || url.startsWith('/categories'),
        },
    ];
});

const logoutForm = useForm({});
const logout = () => {
    logoutForm.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 pb-16 flex flex-col">
        <!-- Top Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-40 px-4 py-3 flex justify-between items-center shadow-sm">
            <div class="font-bold text-blue-600 text-lg">EsTeh App</div>
            <button @click="logout" class="text-sm font-medium text-gray-500 hover:text-red-600 flex items-center gap-1 transition-colors duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Keluar
            </button>
        </header>

        <!-- Main Content -->
        <main class="p-4 flex-1">
            <slot />
        </main>

        <!-- Bottom Navigation -->
        <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around items-center h-16">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="flex flex-col items-center justify-center w-full h-full text-xs transition-colors duration-150 group"
                    :class="item.current
                        ? 'text-blue-600'
                        : 'text-gray-400 hover:text-blue-500'"
                >
                    <!-- Indikator aktif: garis biru di atas ikon -->
                    <span
                        class="absolute top-0 h-0.5 w-10 rounded-full transition-all duration-150"
                        :class="item.current ? 'bg-blue-600' : 'bg-transparent'"
                    ></span>

                    <svg
                        class="w-6 h-6 mb-0.5 transition-transform duration-150"
                        :class="item.current ? 'scale-110' : 'group-hover:scale-105'"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"></path>
                    </svg>
                    <span :class="item.current ? 'font-semibold' : 'font-normal'">
                        {{ item.name }}
                    </span>
                </Link>
            </div>
        </nav>
    </div>
</template>
