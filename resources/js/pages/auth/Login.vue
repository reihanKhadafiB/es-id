<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

import { ref } from 'vue';
import { Passkeys } from '@laravel/passkeys';

defineOptions({
    layout: {
        title: 'Masuk ke ES.ID',
        description: 'Silakan masukkan kredensial Anda untuk melanjutkan',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const isLoggingInWithPasskey = ref(false);
const passkeyError = ref('');

const loginWithPasskey = async () => {
    isLoggingInWithPasskey.value = true;
    passkeyError.value = '';

    try {
        await Passkeys.verify();
        window.location.href = '/';
    } catch (e: any) {
        if (e.message) {
            passkeyError.value = e.message;
        } else {
            passkeyError.value =
                'Gagal login dengan biometrik. Pastikan perangkat sudah didaftarkan.';
        }
    } finally {
        isLoggingInWithPasskey.value = false;
    }
};
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="status"
        class="mb-6 rounded-lg bg-green-50 p-3 text-center text-sm font-medium text-green-600"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="mt-2 flex flex-col gap-5"
    >
        <!-- Phone Number Field -->
        <div class="flex flex-col gap-1.5">
            <label
                for="phone_number"
                class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                >Nomor Telepon</label
            >
            <input
                id="phone_number"
                type="tel"
                name="phone_number"
                required
                autofocus
                :tabindex="1"
                autocomplete="tel"
                placeholder="081234567890"
                class="h-14 w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <InputError :message="errors.phone_number" class="ml-1" />
        </div>

        <!-- Password Field -->
        <div class="flex flex-col gap-1.5">
            <label
                for="password"
                class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                >Kata Sandi</label
            >
            <input
                id="password"
                type="password"
                name="password"
                required
                :tabindex="2"
                autocomplete="current-password"
                placeholder="••••••••"
                class="h-14 w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <InputError :message="errors.password" class="ml-1" />

            <div class="mt-1 flex justify-end">
                <TextLink
                    v-if="canResetPassword"
                    :href="request()"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800"
                    :tabindex="5"
                >
                    Lupa kata sandi?
                </TextLink>
            </div>
        </div>

        <!-- Login Button -->
        <button
            type="submit"
            class="mt-6 flex h-14 w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-500 font-bold text-white shadow-lg shadow-blue-500/30 transition-all hover:from-blue-700 hover:to-blue-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70"
            :tabindex="4"
            :disabled="processing"
            data-test="login-button"
        >
            <Spinner v-if="processing" class="h-5 w-5 text-white" />
            <span v-else class="text-[15px] tracking-wide">MASUK</span>
        </button>

        <div class="relative my-2 flex items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span
                class="mx-4 flex-shrink-0 text-xs font-semibold text-gray-400 uppercase"
                >Atau</span
            >
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Biometric Button -->
        <button
            type="button"
            @click="loginWithPasskey"
            :disabled="isLoggingInWithPasskey"
            class="flex h-14 w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white font-bold text-gray-800 shadow-sm transition-all hover:bg-gray-50 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70"
        >
            <Spinner
                v-if="isLoggingInWithPasskey"
                class="h-5 w-5 text-gray-600"
            />
            <svg
                v-else
                class="h-6 w-6 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"
                ></path>
            </svg>
            <span
                v-if="!isLoggingInWithPasskey"
                class="text-[15px] tracking-wide"
                >Login dengan Biometrik / Face ID</span
            >
        </button>
        <div
            v-if="passkeyError"
            class="text-center text-xs font-medium text-red-600"
        >
            {{ passkeyError }}
        </div>
    </Form>
</template>
