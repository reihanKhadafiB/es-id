<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-800 via-blue-700 to-indigo-900 flex flex-col items-center justify-between relative overflow-hidden font-sans">
        <!-- Decorative Background Elements -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 -right-20 w-64 h-64 bg-blue-400 opacity-20 rounded-full blur-2xl"></div>
        
        <!-- Logo Section (Top Half) -->
        <div class="flex-1 w-full flex flex-col items-center justify-center p-8 relative z-10">
            <Link :href="home()" class="flex flex-col items-center gap-4 transition-transform hover:scale-105 duration-300">
                <div class="w-24 h-24 flex items-center justify-center bg-white/10 backdrop-blur-md rounded-[24px] border border-white/20 shadow-[0_8px_30px_rgba(0,0,0,0.12)] p-2">
                    <img src="/esid.png" alt="ES.ID Logo" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-md">ES.ID</h1>
            </Link>
            <p class="text-blue-100/80 text-sm mt-3 text-center max-w-xs font-medium">Sistem Kasir Pintar & Modern</p>
        </div>

        <!-- Login Form Container (Bottom Sheet Style) -->
        <div class="w-full max-w-md bg-white rounded-t-[32px] md:rounded-[32px] md:mb-10 shadow-[0_-10px_40px_rgba(0,0,0,0.15)] p-8 relative z-10">
            <!-- Sheet handle for mobile -->
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-8 md:hidden"></div>
            
            <div class="space-y-2 mb-8 text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ title }}</h2>
                <p class="text-sm text-gray-500 font-medium">{{ description }}</p>
            </div>
            
            <!-- The form slot -->
            <slot />
        </div>
    </div>
</template>
