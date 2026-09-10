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
            passkeyError.value = 'Gagal login dengan biometrik. Pastikan perangkat sudah didaftarkan.';
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
        class="mb-6 text-center text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg"
    >
        {{ status }}
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-5 mt-2"
    >
        <!-- Phone Number Field -->
        <div class="flex flex-col gap-1.5">
            <label for="phone_number" class="text-xs font-bold text-gray-700 uppercase tracking-wider ml-1">Nomor Telepon</label>
            <input
                id="phone_number"
                type="tel"
                name="phone_number"
                required
                autofocus
                :tabindex="1"
                autocomplete="tel"
                placeholder="081234567890"
                class="h-14 w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm"
            />
            <InputError :message="errors.phone_number" class="ml-1" />
        </div>

        <!-- Password Field -->
        <div class="flex flex-col gap-1.5">
            <label for="password" class="text-xs font-bold text-gray-700 uppercase tracking-wider ml-1">Kata Sandi</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                :tabindex="2"
                autocomplete="current-password"
                placeholder="••••••••"
                class="h-14 w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all shadow-sm"
            />
            <InputError :message="errors.password" class="ml-1" />
            
            <div class="flex justify-end mt-1">
                <TextLink
                    v-if="canResetPassword"
                    :href="request()"
                    class="text-xs text-blue-600 font-semibold hover:text-blue-800"
                    :tabindex="5"
                >
                    Lupa kata sandi?
                </TextLink>
            </div>
        </div>

        <!-- Login Button -->
        <button
            type="submit"
            class="mt-6 w-full h-14 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white rounded-full font-bold shadow-lg shadow-blue-500/30 flex justify-center items-center gap-2 transition-all active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
            :tabindex="4"
            :disabled="processing"
            data-test="login-button"
        >
            <Spinner v-if="processing" class="text-white w-5 h-5" />
            <span v-else class="text-[15px] tracking-wide">MASUK</span>
        </button>

        <div class="relative flex items-center my-2">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-semibold uppercase">Atau</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Biometric Button -->
        <button
            type="button"
            @click="loginWithPasskey"
            :disabled="isLoggingInWithPasskey"
            class="w-full h-14 bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 rounded-full font-bold shadow-sm flex justify-center items-center gap-2 transition-all active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
        >
            <Spinner v-if="isLoggingInWithPasskey" class="text-gray-600 w-5 h-5" />
            <svg v-else class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
            </svg>
            <span v-if="!isLoggingInWithPasskey" class="text-[15px] tracking-wide">Login dengan Biometrik / Face ID</span>
        </button>
        <div v-if="passkeyError" class="text-center text-xs text-red-600 font-medium">
            {{ passkeyError }}
        </div>
    </Form>
</template>
