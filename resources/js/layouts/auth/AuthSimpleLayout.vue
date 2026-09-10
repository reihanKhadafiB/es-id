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
    <div
        class="relative flex min-h-screen flex-col items-center justify-between overflow-hidden bg-gradient-to-br from-blue-800 via-blue-700 to-indigo-900 font-sans"
    >
        <!-- Decorative Background Elements -->
        <div
            class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-white opacity-5 blur-3xl"
        ></div>
        <div
            class="absolute top-1/4 -right-20 h-64 w-64 rounded-full bg-blue-400 opacity-20 blur-2xl"
        ></div>

        <!-- Logo Section (Top Half) -->
        <div
            class="relative z-10 flex w-full flex-1 flex-col items-center justify-center p-8"
        >
            <Link
                :href="home()"
                class="flex flex-col items-center gap-4 transition-transform duration-300 hover:scale-105"
            >
                <div
                    class="flex h-24 w-24 items-center justify-center rounded-[24px] border border-white/20 bg-white/10 p-2 shadow-[0_8px_30px_rgba(0,0,0,0.12)] backdrop-blur-md"
                >
                    <img
                        src="/esid.png"
                        alt="ES.ID Logo"
                        class="h-full w-full object-contain drop-shadow-lg"
                    />
                </div>
                <h1
                    class="text-3xl font-extrabold tracking-tight text-white drop-shadow-md"
                >
                    ES.ID
                </h1>
            </Link>
            <p
                class="mt-3 max-w-xs text-center text-sm font-medium text-blue-100/80"
            >
                Sistem Kasir Pintar & Modern
            </p>
        </div>

        <!-- Login Form Container (Bottom Sheet Style) -->
        <div
            class="relative z-10 w-full max-w-md rounded-t-[32px] bg-white p-8 shadow-[0_-10px_40px_rgba(0,0,0,0.15)] md:mb-10 md:rounded-[32px]"
        >
            <!-- Sheet handle for mobile -->
            <div
                class="mx-auto mb-8 h-1.5 w-12 rounded-full bg-gray-200 md:hidden"
            ></div>

            <div class="mb-8 space-y-2 text-center md:text-left">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                    {{ title }}
                </h2>
                <p class="text-sm font-medium text-gray-500">
                    {{ description }}
                </p>
            </div>

            <!-- The form slot -->
            <slot />
        </div>
    </div>
</template>
