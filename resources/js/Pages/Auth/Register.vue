<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { User, Mail, Phone, Lock, Eye, EyeOff, QrCode, ArrowRight } from '@lucide/vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    team_code: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Daftar Mitra - Mitra Syiar Baitullah" />

        <div class="relative z-10 w-full p-4 sm:p-6 rounded-[26px] border-2 border-[#e09d49]/60 bg-gradient-to-b from-white/95 to-[#fffaf2]/90 shadow-[0_20px_50px_rgba(92,44,36,0.16)] backdrop-blur-md max-h-[95vh] flex flex-col justify-between overflow-y-auto sm:overflow-hidden">
            <!-- Header Branding -->
            <div class="text-center space-y-0.5 mb-2">
                <h1 class="text-base sm:text-lg font-black tracking-tight text-[#5c2c24] uppercase leading-tight">
                    DAFTAR MITRA SYIAR BAITULLAH
                </h1>
                <p class="text-[11px] font-semibold text-[#9d7c64]">
                    Lengkapi data untuk membuat akun mitra baru
                </p>
            </div>

            <!-- Form Registration -->
            <form @submit.prevent="submit" class="space-y-2.5">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="flex items-center gap-1.5 mb-0.5 text-[11px] font-extrabold text-[#5c2c24]">
                        <User class="w-3.5 h-3.5 text-[#e98318]" />
                        <span>Nama Lengkap</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9d7c64]">
                            <User class="w-3.5 h-3.5" />
                        </div>
                        <input
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full h-9 sm:h-10 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-9 pr-3 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                        />
                    </div>
                    <InputError class="mt-0.5" :message="form.errors.name" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="flex items-center gap-1.5 mb-0.5 text-[11px] font-extrabold text-[#5c2c24]">
                            <Mail class="w-3.5 h-3.5 text-[#e98318]" />
                            <span>Email</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9d7c64]">
                                <Mail class="w-3.5 h-3.5" />
                            </div>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="nama@email.com"
                                class="w-full h-9 sm:h-10 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-9 pr-3 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                            />
                        </div>
                        <InputError class="mt-0.5" :message="form.errors.email" />
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label for="phone" class="flex items-center gap-1.5 mb-0.5 text-[11px] font-extrabold text-[#5c2c24]">
                            <Phone class="w-3.5 h-3.5 text-[#e98318]" />
                            <span>No. WhatsApp</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9d7c64]">
                                <Phone class="w-3.5 h-3.5" />
                            </div>
                            <input
                                id="phone"
                                type="tel"
                                v-model="form.phone"
                                required
                                autocomplete="tel"
                                placeholder="08xxxxxxxxxx"
                                class="w-full h-9 sm:h-10 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-9 pr-3 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                            />
                        </div>
                        <InputError class="mt-0.5" :message="form.errors.phone" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="flex items-center gap-1.5 mb-0.5 text-[11px] font-extrabold text-[#5c2c24]">
                            <Lock class="w-3.5 h-3.5 text-[#e98318]" />
                            <span>Kata Sandi</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9d7c64]">
                                <Lock class="w-3.5 h-3.5" />
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="Min 8 karakter"
                                class="w-full h-9 sm:h-10 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-9 pr-8 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#9d7c64] hover:text-[#e98318] transition-colors"
                            >
                                <Eye v-if="!showPassword" class="w-3.5 h-3.5" />
                                <EyeOff v-else class="w-3.5 h-3.5" />
                            </button>
                        </div>
                        <InputError class="mt-0.5" :message="form.errors.password" />
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="flex items-center gap-1.5 mb-0.5 text-[11px] font-extrabold text-[#5c2c24]">
                            <Lock class="w-3.5 h-3.5 text-[#e98318]" />
                            <span>Ulangi Sandi</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9d7c64]">
                                <Lock class="w-3.5 h-3.5" />
                            </div>
                            <input
                                id="password_confirmation"
                                :type="showConfirmPassword ? 'text' : 'password'"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi kata sandi"
                                class="w-full h-9 sm:h-10 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-9 pr-8 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                            />
                            <button 
                                type="button" 
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-[#9d7c64] hover:text-[#e98318] transition-colors"
                            >
                                <Eye v-if="!showConfirmPassword" class="w-3.5 h-3.5" />
                                <EyeOff v-else class="w-3.5 h-3.5" />
                            </button>
                        </div>
                        <InputError class="mt-0.5" :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <!-- Kode Team -->
                <div>
                    <label for="team_code" class="flex items-center gap-1.5 mb-0.5 text-[11px] font-extrabold text-[#5c2c24]">
                        <QrCode class="w-3.5 h-3.5 text-[#e98318]" />
                        <span>Kode Team / Referral</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9d7c64]">
                            <QrCode class="w-3.5 h-3.5" />
                        </div>
                        <input
                            id="team_code"
                            type="text"
                            v-model="form.team_code"
                            required
                            placeholder="Masukkan kode team Anda"
                            class="w-full h-9 sm:h-10 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-9 pr-3 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                        />
                    </div>
                    <InputError class="mt-0.5" :message="form.errors.team_code" />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-10 sm:h-11 mt-1 rounded-full bg-gradient-to-r from-[#e98318] via-[#e09d49] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white text-xs font-black tracking-wide shadow-md shadow-[#e98318]/25 flex items-center justify-center gap-2 transition-all disabled:opacity-50 cursor-pointer"
                >
                    <span>Daftar Sebagai Mitra</span>
                    <ArrowRight class="w-4 h-4" />
                </button>
            </form>

            <p class="mt-2 text-[10px] leading-tight text-center text-[#9d7c64]">
                Dengan mendaftar, Anda menyetujui syarat & ketentuan Mitra Syiar Baitullah.
            </p>

            <!-- Back to Login -->
            <p class="mt-2.5 text-center text-xs font-semibold text-[#9d7c64]">
                Sudah punya akun?
                <Link :href="route('login')" class="ml-1 font-bold text-[#e98318] hover:underline underline-offset-2">
                    Masuk Sekarang
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
