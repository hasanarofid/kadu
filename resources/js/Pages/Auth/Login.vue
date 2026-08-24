<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, Eye, EyeOff, ArrowRight, Check, Brain, Sparkles } from 'lucide-vue-next';

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
            <div class="text-center space-y-1.5 mb-4">
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

            <!-- Login Form -->
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
                    class="w-full h-12 mt-2 rounded-xl bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 active:scale-[0.99] text-white text-xs sm:text-sm font-extrabold tracking-wide shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2 transition-all disabled:opacity-50 cursor-pointer"
                >
                    <span>Masuk ke Dashboard</span>
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
