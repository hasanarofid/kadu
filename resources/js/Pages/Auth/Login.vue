<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, Eye, EyeOff, ArrowRight, Check, Brain } from 'lucide-vue-next';

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
        <Head title="Login - KADU (Karsa Edukasi Vokasi)" />

        <div class="relative z-10 w-full p-6 sm:p-8 rounded-3xl border border-indigo-500/30 bg-slate-900/90 shadow-2xl shadow-indigo-950/80 backdrop-blur-xl flex flex-col justify-between overflow-y-auto">
            <!-- Header Branding -->
            <div class="text-center space-y-1.5 mb-5">
                <div class="inline-flex p-3 bg-gradient-to-tr from-indigo-600 via-violet-600 to-purple-500 rounded-2xl shadow-lg shadow-indigo-600/30 mb-1">
                    <Brain class="w-7 h-7 text-white" />
                </div>
                <h1 class="text-xl font-black tracking-tight text-white uppercase">
                    KADU (Karsa Edukasi Vokasi)
                </h1>
                <p class="text-xs font-semibold text-indigo-300">
                    Generator RPP Deep Learning Vokasi SMK
                </p>
            </div>

            <!-- Status Alert -->
            <div v-if="status" class="mb-4 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-xs font-semibold text-emerald-300 text-center">
                {{ status }}
            </div>

            <!-- 🎯 ONE-TAP GOOGLE LOGIN (UTAMA & TERCEPAT) -->
            <div class="space-y-3 mb-5 text-left">
                <a
                    :href="route('auth.google')"
                    class="w-full h-12 rounded-2xl bg-white hover:bg-slate-100 active:scale-[0.99] text-slate-900 text-xs sm:text-sm font-extrabold shadow-lg shadow-white/10 flex items-center justify-center gap-3 transition-all border border-slate-200 cursor-pointer group"
                >
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Masuk / Daftar via Google</span>
                </a>

                <div class="relative flex items-center justify-center my-4">
                    <div class="border-t border-slate-800 w-full"></div>
                    <span class="bg-slate-900 px-3 text-xxs font-bold text-slate-500 uppercase tracking-widest absolute">atau email</span>
                </div>
            </div>

            <!-- Login Form (Email/Password Manual) -->
            <form @submit.prevent="submit" class="space-y-4 text-left">
                <!-- Email Field -->
                <div>
                    <label for="email" class="flex items-center gap-1.5 mb-1.5 text-xs font-bold text-slate-200">
                        <Mail class="w-3.5 h-3.5 text-indigo-400" />
                        <span>Alamat Email</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <Mail class="w-4 h-4" />
                        </div>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="nama@sekolah.sch.id"
                            class="w-full h-11 bg-slate-950/90 border border-slate-800 rounded-xl pl-10 pr-4 text-xs font-medium text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner"
                        />
                    </div>
                    <InputError class="mt-1 text-xs text-rose-400" :message="form.errors.email" />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="flex items-center gap-1.5 mb-1.5 text-xs font-bold text-slate-200">
                        <Lock class="w-3.5 h-3.5 text-indigo-400" />
                        <span>Kata Sandi</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <Lock class="w-4 h-4" />
                        </div>
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi Anda"
                            class="w-full h-11 bg-slate-950/90 border border-slate-800 rounded-xl pl-10 pr-10 text-xs font-medium text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-inner"
                        />
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-indigo-400 transition-colors"
                        >
                            <Eye v-if="!showPassword" class="w-4 h-4" />
                            <EyeOff v-else class="w-4 h-4" />
                        </button>
                    </div>
                    <InputError class="mt-1 text-xs text-rose-400" :message="form.errors.password" />
                </div>

                <!-- Meta Row -->
                <div class="flex items-center justify-between pt-1 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-300 font-semibold select-none">
                        <input 
                            type="checkbox" 
                            v-model="form.remember" 
                            class="sr-only peer"
                        />
                        <div class="w-4.5 h-4.5 rounded bg-slate-950 border border-slate-700 peer-checked:bg-indigo-600 peer-checked:border-indigo-500 flex items-center justify-center text-white transition-all shadow-sm">
                            <Check v-if="form.remember" class="w-3 h-3 stroke-[3]" />
                        </div>
                        <span>Ingat Sesi Saya</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="font-bold text-indigo-400 hover:text-indigo-300 underline underline-offset-2 transition-colors"
                    >
                        Lupa kata sandi?
                    </Link>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-11 mt-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.99] text-white text-xs font-bold tracking-wide shadow-md flex items-center justify-center gap-2 transition-all disabled:opacity-50 cursor-pointer"
                >
                    <span>Masuk Manual</span>
                    <ArrowRight class="w-4 h-4" />
                </button>
            </form>

            <!-- Register Navigation Prompt -->
            <p class="mt-5 text-center text-xs font-semibold text-slate-400">
                Belum memiliki akun guru?
                <Link :href="route('register')" class="ml-1 font-bold text-indigo-400 hover:underline underline-offset-2">
                    Daftar Sekarang
                </Link>
            </p>

            <!-- Back to Landing Page -->
            <div class="mt-4 pt-3 border-t border-slate-800 text-center">
                <Link href="/" class="text-xs font-bold text-slate-400 hover:text-white transition-colors">
                    ← Kembali ke Landing Page KADU
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
