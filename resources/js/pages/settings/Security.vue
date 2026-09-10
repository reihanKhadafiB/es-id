<script setup lang="ts">
import { Head, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import SecurityController from '@/actions/App/Http/Controllers/Settings/SecurityController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/security';
import { Passkeys } from '@laravel/passkeys';

// oxfmt-ignore
type Props = {
    passwordRules: string;
} ;

const props = defineProps<Props>();

const page = usePage();
const user = computed(() => page.props.auth.user);

const profileForm = useForm({
    name: user.value.name,
    phone_number: user.value.phone_number,
});

const submitProfile = () => {
    profileForm.patch(ProfileController.update.url(), {
        preserveScroll: true,
        onSuccess: () => toast.success('Profil berhasil disimpan!'),
        onError: () =>
            toast.error('Gagal menyimpan profil, periksa kembali data Anda.'),
    });
};

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submitPassword = () => {
    passwordForm.put(SecurityController.update.url(), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            toast.success('Kata sandi berhasil diganti!');
        },
        onError: () => {
            toast.error(
                'Gagal mengganti kata sandi, periksa kembali data yang Anda masukkan.',
            );
            passwordForm.reset('password', 'password_confirmation');

            // Fokuskan kembali ke input yang error (opsional tapi bagus untuk UX)
            if (passwordForm.errors.current_password) {
                document.getElementById('current_password')?.focus();
            } else if (passwordForm.errors.password) {
                document.getElementById('password')?.focus();
            }
        },
    });
};

const isRegisteringPasskey = ref(false);
const passkeyMessage = ref('');
const passkeyError = ref(false);

const registerPasskey = async () => {
    isRegisteringPasskey.value = true;
    passkeyMessage.value = '';
    passkeyError.value = false;

    try {
        await Passkeys.register({ name: 'Perangkat Ini' });
        passkeyMessage.value =
            'Perangkat berhasil didaftarkan untuk login Biometrik!';
    } catch (e: any) {
        passkeyError.value = true;
        if (e.message) {
            passkeyMessage.value = e.message;
        } else {
            passkeyMessage.value =
                'Gagal mendaftarkan perangkat. Pastikan browser Anda mendukung WebAuthn.';
        }
    } finally {
        isRegisteringPasskey.value = false;
    }
};

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pengaturan Akun',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Pengaturan Akun" />

    <h1 class="sr-only">Pengaturan Akun</h1>

    <div class="space-y-6">
        <!-- Update Profile / Phone Number -->
        <Heading
            variant="small"
            title="Profil Pengguna"
            description="Ubah nama atau nomor telepon yang digunakan untuk login"
        />

        <form @submit.prevent="submitProfile" class="space-y-6">
            <div class="flex flex-col gap-1.5">
                <Label
                    for="name"
                    class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                    >Nama Lengkap</Label
                >
                <input
                    id="name"
                    class="h-14 w-full rounded-2xl border border-gray-200 bg-white px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="name"
                    v-model="profileForm.name"
                    required
                    autocomplete="name"
                    placeholder="Nama Lengkap"
                />
                <InputError class="ml-1" :message="profileForm.errors.name" />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label
                    for="phone_number"
                    class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                    >Nomor Telepon</Label
                >
                <input
                    id="phone_number"
                    type="tel"
                    class="h-14 w-full rounded-2xl border border-gray-200 bg-white px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="phone_number"
                    v-model="profileForm.phone_number"
                    required
                    autocomplete="tel"
                    placeholder="081234567890"
                />
                <InputError
                    class="ml-1"
                    :message="profileForm.errors.phone_number"
                />
            </div>

            <div class="mt-2">
                <button
                    type="submit"
                    :disabled="profileForm.processing"
                    data-test="update-profile-button"
                    class="flex h-14 w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-500 font-bold text-white shadow-lg shadow-blue-500/30 transition-all hover:from-blue-700 hover:to-blue-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70"
                >
                    SIMPAN PERUBAHAN PROFIL
                </button>
            </div>
        </form>

        <div class="mt-8 space-y-6 border-t border-gray-200 pt-8">
            <Heading
                variant="small"
                title="Ganti Kata Sandi"
                description="Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman"
            />

            <form @submit.prevent="submitPassword" class="space-y-6">
                <div class="flex flex-col gap-1.5">
                    <Label
                        for="current_password"
                        class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                        >Kata Sandi Saat Ini</Label
                    >
                    <PasswordInput
                        id="current_password"
                        name="current_password"
                        class="h-14 w-full rounded-2xl border border-gray-200 bg-white px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-200 dark:bg-white dark:text-gray-900 dark:placeholder:text-gray-400"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        v-model="passwordForm.current_password"
                    />
                    <InputError
                        class="ml-1"
                        :message="passwordForm.errors.current_password"
                    />
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label
                        for="password"
                        class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                        >Kata Sandi Baru</Label
                    >
                    <PasswordInput
                        id="password"
                        name="password"
                        class="h-14 w-full rounded-2xl border border-gray-200 bg-white px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-200 dark:bg-white dark:text-gray-900 dark:placeholder:text-gray-400"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        :passwordrules="props.passwordRules"
                        v-model="passwordForm.password"
                    />
                    <InputError
                        class="ml-1"
                        :message="passwordForm.errors.password"
                    />
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label
                        for="password_confirmation"
                        class="ml-1 text-xs font-bold tracking-wider text-gray-700 uppercase"
                        >Konfirmasi Kata Sandi</Label
                    >
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        class="h-14 w-full rounded-2xl border border-gray-200 bg-white px-5 text-gray-900 shadow-sm transition-all placeholder:text-gray-400 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-200 dark:bg-white dark:text-gray-900 dark:placeholder:text-gray-400"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        :passwordrules="props.passwordRules"
                        v-model="passwordForm.password_confirmation"
                    />
                    <InputError
                        class="ml-1"
                        :message="passwordForm.errors.password_confirmation"
                    />
                </div>

                <div class="mt-2">
                    <button
                        type="submit"
                        :disabled="passwordForm.processing"
                        data-test="update-password-button"
                        class="flex h-14 w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-500 font-bold text-white shadow-lg shadow-blue-500/30 transition-all hover:from-blue-700 hover:to-blue-600 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        GANTI KATA SANDI
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 space-y-6 border-t border-gray-200 pt-8">
            <Heading
                variant="small"
                title="Biometric Login (Face ID / Fingerprint)"
                description="Daftarkan perangkat ini agar Anda bisa login dengan aman dan cepat menggunakan wajah atau sidik jari tanpa perlu memasukkan kata sandi."
            />

            <div class="mt-4 flex flex-col gap-3">
                <button
                    @click="registerPasskey"
                    type="button"
                    :disabled="isRegisteringPasskey"
                    class="flex h-14 w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white font-bold text-gray-800 shadow-sm transition-all hover:bg-gray-50 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-70"
                >
                    <svg
                        v-if="!isRegisteringPasskey"
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
                        v-if="!isRegisteringPasskey"
                        class="text-[15px] tracking-wide"
                        >Daftarkan Face ID / Sidik Jari</span
                    >
                    <span v-else class="text-[15px] tracking-wide"
                        >Mendaftarkan...</span
                    >
                </button>

                <div
                    v-if="passkeyMessage"
                    class="mt-2 text-center text-sm font-medium"
                    :class="passkeyError ? 'text-red-600' : 'text-green-600'"
                >
                    {{ passkeyMessage }}
                </div>
            </div>
        </div>
    </div>
</template>
