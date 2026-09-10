<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Toaster } from '@/components/ui/sonner';

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
    ];
});
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col bg-gray-50 pb-28 font-sans text-gray-900"
    >
        <!-- Background Extended Header (Premium M-Banking Style) -->
        <div
            class="absolute top-0 left-0 z-0 h-56 w-full overflow-hidden rounded-b-[2.5rem] bg-gradient-to-br from-blue-800 via-blue-700 to-indigo-900 shadow-lg"
        >
            <!-- Decorative circles for premium feel -->
            <div
                class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-white opacity-5 blur-2xl"
            ></div>
            <div
                class="absolute top-12 -left-12 h-32 w-32 rounded-full bg-blue-400 opacity-20 blur-xl"
            ></div>
        </div>

        <!-- Top Header -->
        <header
            class="relative z-40 flex items-center justify-between px-5 pt-8 pb-4 text-white"
        >
            <div class="flex items-center gap-1">
                <div class="flex h-14 w-14 items-center justify-center">
                    <img
                        src="/esid.png"
                        alt="ES.ID Logo"
                        class="h-full w-full object-contain drop-shadow-lg"
                    />
                </div>
                <div class="flex flex-col justify-center">
                    <span
                        class="text-2xl font-extrabold tracking-tight text-white drop-shadow-md"
                        >ES.ID</span
                    >
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    v-if="page.url.startsWith('/settings')"
                    href="/dashboard"
                    class="flex items-center justify-center rounded-full border border-white/20 bg-white/10 p-2 text-white shadow-sm backdrop-blur-md transition-all hover:bg-white/20"
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
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        ></path>
                    </svg>
                </Link>
                <Link
                    v-else
                    href="/settings/security"
                    class="flex items-center justify-center rounded-full border border-white/20 bg-white/10 p-2 text-white shadow-sm backdrop-blur-md transition-all hover:bg-white/20"
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
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                        ></path>
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        ></path>
                    </svg>
                </Link>
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white shadow-sm backdrop-blur-md transition-all hover:bg-white/20"
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
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        ></path>
                    </svg>
                    Logout
                </Link>
            </div>
        </header>

        <!-- Main Content (Wrapped in overlapping card) -->
        <main class="relative z-10 mt-4 flex flex-1 flex-col">
            <div
                class="flex-1 rounded-t-3xl bg-gray-50 px-4 pt-6 shadow-[0_-8px_15px_rgba(0,0,0,0.05)]"
            >
                <slot />
            </div>
        </main>

        <!-- Bottom Navigation (Floating M-Banking Style) -->
        <nav
            v-if="!page.url.startsWith('/settings')"
            class="fixed right-4 bottom-4 left-4 z-50 rounded-[24px] border border-gray-100/50 bg-white/95 shadow-[0_8px_30px_rgba(0,0,0,0.12)] backdrop-blur-md"
        >
            <div class="flex h-16 items-center justify-around px-2">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="group relative flex h-full w-full flex-col items-center justify-center text-[10px] transition-all duration-300"
                    :class="
                        item.current
                            ? 'font-bold text-blue-700'
                            : 'font-medium text-gray-400 hover:text-blue-500'
                    "
                >
                    <div
                        class="mb-0.5 flex flex-col items-center justify-center rounded-[14px] px-4 py-1.5 transition-all duration-300"
                        :class="
                            item.current
                                ? 'bg-blue-50/80 text-blue-700 shadow-inner'
                                : 'bg-transparent text-gray-400 group-hover:text-blue-500'
                        "
                    >
                        <svg
                            class="h-6 w-6 transition-transform duration-300"
                            :class="
                                item.current
                                    ? 'scale-110 drop-shadow-sm'
                                    : 'group-hover:scale-105'
                            "
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="item.icon"
                            ></path>
                        </svg>
                    </div>
                    <span>{{ item.name }}</span>
                    <!-- Active Dot Indicator -->
                    <div
                        v-if="item.current"
                        class="absolute -bottom-1 h-1 w-1 rounded-full bg-blue-600"
                    ></div>
                </Link>
            </div>
        </nav>
        <Toaster position="bottom-center" richColors theme="light" />
    </div>
</template>
