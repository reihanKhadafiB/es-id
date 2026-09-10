<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MobileAppLayout from '@/layouts/MobileAppLayout.vue';

const props = defineProps<{
    status: number;
}>();

const title = computed(() => {
    return {
        503: 'Layanan Tidak Tersedia',
        500: 'Kesalahan Server',
        404: 'Halaman Tidak Ditemukan',
        403: 'Akses Ditolak',
        401: 'Sesi Berakhir',
    }[props.status] || 'Terjadi Kesalahan';
});

const description = computed(() => {
    return {
        503: 'Maaf, layanan sedang dalam pemeliharaan. Silakan coba beberapa saat lagi.',
        500: 'Wah, sepertinya ada masalah di peladen (server) kami. Kami sedang memperbaikinya.',
        404: 'Halaman yang Anda tuju tidak dapat ditemukan. Mungkin tautannya salah atau halaman sudah dihapus.',
        403: 'Anda tidak memiliki hak akses untuk membuka halaman ini.',
        401: 'Sesi login Anda telah berakhir. Silakan login kembali untuk melanjutkan.',
    }[props.status] || 'Maaf, terjadi kesalahan yang tidak terduga.';
});

const illustration = computed(() => {
    switch (props.status) {
        case 404:
            return 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z M10 10l2 2m0 0l2 2m-2-2l-2 2m2-2l2-2';
        case 500:
        case 503:
            return 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
        case 403:
        case 401:
            return 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z';
        default:
            return 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';
    }
});
</script>

<script lang="ts">
export default {
    layout: MobileAppLayout,
};
</script>

<template>
    <Head :title="title" />
    <div class="flex flex-col items-center justify-center min-h-[60vh] px-6 text-center space-y-6">
        <!-- Error Illustration -->
        <div class="relative w-32 h-32 flex items-center justify-center bg-blue-50 rounded-full shadow-inner mb-4">
            <div class="absolute inset-0 bg-blue-100 rounded-full animate-pulse opacity-50"></div>
            <svg class="w-16 h-16 text-blue-600 relative z-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" :d="illustration" />
            </svg>
            <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-md">
                <span class="text-xs font-bold text-gray-800">{{ status }}</span>
            </div>
        </div>

        <div class="space-y-3">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ title }}</h1>
            <p class="text-sm text-gray-500 leading-relaxed max-w-xs mx-auto">
                {{ description }}
            </p>
        </div>

        <div class="pt-8 w-full">
            <Link
                href="/dashboard"
                class="w-full h-14 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-full font-bold shadow-lg shadow-blue-500/30 flex justify-center items-center transition-all active:scale-[0.98]"
            >
                KEMBALI KE BERANDA
            </Link>
        </div>
    </div>
</template>
