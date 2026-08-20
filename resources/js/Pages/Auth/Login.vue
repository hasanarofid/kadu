<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, Eye, EyeOff, ArrowRight, Check } from '@lucide/vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: true,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Login - Mitra Syiar Baitullah" />

        <div class="relative z-10 w-full p-6 sm:p-10 rounded-[30px] border-2 border-[#e09d49]/60 bg-gradient-to-b from-white/95 to-[#fffaf2]/90 shadow-[0_26px_70px_rgba(92,44,36,0.18)] backdrop-blur-md">
            <!-- Header Branding -->
            <div class="text-center space-y-1 mb-3">
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-[#5c2c24] uppercase">
                    MITRA SYIAR BAITULLAH
                </h1>
                <p class="text-xs sm:text-sm font-semibold text-[#9d7c64]">
                    Sistem mitra dan agen umroh terpercaya
                </p>
            </div>

            <!-- Islamic Ornament -->
            <div class="flex items-center justify-center gap-3 my-4 max-w-[240px] mx-auto text-[#e98318]">
                <div class="h-[1px] flex-1 bg-gradient-to-r from-transparent to-[#e09d49]"></div>
                <svg width="26" height="26" viewBox="0 0 48 48" fill="none" class="flex-shrink-0">
                    <g stroke="currentColor" stroke-width="2.2" stroke-linejoin="round">
                        <path d="M24 5l5.4 8.5 10-.4-4.6 8.9 5.4 8.5-10 .4L24 40l-5.4-8.5-10 .4 4.6-8.9-5.4-8.5 10-.4L24 5z"/>
                        <circle cx="24" cy="23" r="6.5"/>
                    </g>
                </svg>
                <div class="h-[1px] flex-1 bg-gradient-to-l from-transparent to-[#e09d49]"></div>
            </div>

            <!-- Status Alert -->
            <div v-if="status" class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs font-semibold text-emerald-700 text-center">
                {{ status }}
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <!-- Email Field -->
                <div>
                    <label for="email" class="flex items-center gap-2 mb-1.5 text-xs font-extrabold text-[#5c2c24]">
                        <Mail class="w-4 h-4 text-[#e98318]" />
                        <span>Alamat Email</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#9d7c64]">
                            <Mail class="w-4 h-4" />
                        </div>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="Masukkan alamat email Anda"
                            class="w-full h-12 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-10 pr-4 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                        />
                    </div>
                    <InputError class="mt-1" :message="form.errors.email" />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="flex items-center gap-2 mb-1.5 text-xs font-extrabold text-[#5c2c24]">
                        <Lock class="w-4 h-4 text-[#e98318]" />
                        <span>Kata Sandi</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#9d7c64]">
                            <Lock class="w-4 h-4" />
                        </div>
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi Anda"
                            class="w-full h-12 bg-white/80 border border-[#e09d49]/70 rounded-xl pl-10 pr-12 text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all shadow-sm"
                        />
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#9d7c64] hover:text-[#e98318] transition-colors"
                        >
                            <Eye v-if="!showPassword" class="w-4 h-4" />
                            <EyeOff v-else class="w-4 h-4" />
                        </button>
                    </div>
                    <InputError class="mt-1" :message="form.errors.password" />
                </div>

                <!-- Meta Row -->
                <div class="flex items-center justify-between pt-1 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-[#5c2c24] font-semibold select-none">
                        <input 
                            type="checkbox" 
                            v-model="form.remember" 
                            class="sr-only peer"
                        />
                        <div class="w-5 h-5 rounded bg-gradient-to-tr from-[#e98318] to-[#e09d49] peer-checked:opacity-100 flex items-center justify-center text-white shadow-sm border border-[#e09d49]">
                            <Check v-if="form.remember" class="w-3.5 h-3.5 stroke-[3]" />
                        </div>
                        <span>Ingat Sesi Saya</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="font-bold text-[#e98318] hover:text-[#5c2c24] underline underline-offset-2 transition-colors"
                    >
                        Lupa kata sandi?
                    </Link>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-13 mt-2 rounded-full bg-gradient-to-r from-[#e98318] via-[#e09d49] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white text-xs sm:text-sm font-black tracking-wide shadow-lg shadow-[#e98318]/25 flex items-center justify-center gap-3 transition-all disabled:opacity-50 cursor-pointer"
                >
                    <span>Masuk ke Dashboard</span>
                    <ArrowRight class="w-4 h-4" />
                </button>
            </form>

            <!-- Register Navigation Prompt -->
            <p class="mt-5 text-center text-xs font-semibold text-[#9d7c64]">
                Belum punya akun?
                <Link :href="route('register')" class="ml-1 font-bold text-[#e98318] hover:underline underline-offset-2">
                    Daftar Sekarang
                </Link>
            </p>

            <!-- Motto -->
            <div class="mt-6 flex items-center justify-center gap-2 text-xs font-bold italic text-[#5c2c24]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#e98318]"></span>
                <span class="font-serif">Bersama Menjadi Tamu Allah</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#e98318]"></span>
            </div>
        </div>
    </GuestLayout>
</template>

