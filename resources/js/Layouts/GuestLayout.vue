<script setup>
import { Link } from '@inertiajs/vue3';
import { Brain, Sparkles, CheckCircle, GraduationCap, Wrench, ShieldCheck } from 'lucide-vue-next';
</script>

<template>
  <div class="h-screen h-dvh max-h-screen overflow-hidden relative font-sans antialiased text-slate-100 bg-slate-950 selection:bg-indigo-500 selection:text-white flex flex-col justify-between">
    <!-- Ambient Background Gradients -->
    <div class="fixed inset-0 pointer-events-none z-0 bg-[radial-gradient(circle_at_73%_13%,_rgba(99,102,241,0.15)_0%,_transparent_40%),_linear-gradient(135deg,_#020617_0%,_#0f172a_50%,_#1e1b4b_100%)]"></div>
    <div class="fixed inset-0 pointer-events-none z-0 opacity-30 mix-blend-screen bg-[radial-gradient(circle_at_12%_25%,_rgba(139,92,246,0.3),_transparent_30%),_radial-gradient(circle_at_88%_72%,_rgba(168,85,247,0.2),_transparent_30%)]"></div>

    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 h-screen max-h-screen overflow-hidden">
      <!-- Left Hero Showcase Section -->
      <div class="hidden lg:flex lg:col-span-7 relative h-screen max-h-screen overflow-hidden p-12 flex-col justify-between text-left">
        <!-- Brand Header -->
        <Link href="/" class="flex items-center gap-3 group z-10">
          <div class="p-2.5 bg-gradient-to-tr from-indigo-600 via-violet-600 to-purple-500 rounded-2xl shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform duration-300">
            <Brain class="w-6 h-6 text-white" />
          </div>
          <div>
            <span class="font-black text-xl tracking-tight text-white flex items-center gap-2">
              KADU
            </span>
            <span class="text-xxs text-slate-400 font-medium tracking-wide">Karsa Edukasi</span>
          </div>
        </Link>

        <!-- Hero Content in Left Panel -->
        <div class="space-y-6 max-w-xl my-auto z-10">
          <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 border border-indigo-500/30 text-indigo-300 text-xs font-bold shadow-inner">
            <Sparkles class="w-4 h-4 text-indigo-400" />
            <span>AI Generator RPP Deep Learning Vokasi SMK</span>
          </div>

          <h1 class="text-4xl font-black text-white leading-tight tracking-tight">
            Solusi Cerdas Penyusunan RPP & Modul Ajar Terintegrasi
          </h1>

          <p class="text-sm text-slate-300 font-medium leading-relaxed">
            Platform berbasis AI untuk guru vokasi SMK, lengkap dengan integrasi Payment Gateway otomatis untuk pembelian voucher dan lisensi sekolah.
          </p>

          <div class="grid grid-cols-2 gap-4 pt-2">
            <div class="p-4 rounded-2xl bg-slate-900/80 border border-indigo-900/50 backdrop-blur space-y-1">
              <div class="flex items-center gap-2 text-indigo-400 font-bold text-xs">
                <GraduationCap class="w-4 h-4" />
                <span>4-Step Wizard RPP</span>
              </div>
              <p class="text-xxs text-slate-400">Identitas, Ekosistem, Dimensi Vokasi, & PDF</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-900/80 border border-indigo-900/50 backdrop-blur space-y-1">
              <div class="flex items-center gap-2 text-purple-400 font-bold text-xs">
                <ShieldCheck class="w-4 h-4" />
                <span>Payment Gateway</span>
              </div>
              <p class="text-xxs text-slate-400">Checkout, Tokenisasi & Webhook Otomatis</p>
            </div>
          </div>
        </div>

        <!-- Footer Note -->
        <div class="z-10 text-xs text-slate-500 font-semibold flex items-center gap-2">
          <span>&copy; {{ new Date().getFullYear() }} KADU (Karsa Edukasi Vokasi)</span>
          <span>•</span>
          <a href="https://kadu.andibakhtiar.com/" target="_blank" class="text-indigo-400 hover:underline">kadu.andibakhtiar.com</a>
        </div>
      </div>

      <!-- Right Form Section -->
      <div class="col-span-1 lg:col-span-5 relative flex items-center justify-center p-4 sm:p-6 h-screen max-h-screen overflow-hidden">
        <!-- Floating Geometry Glow -->
        <div class="absolute -right-20 -top-20 w-96 h-96 rounded-full border-[28px] border-indigo-500/10 shadow-[0_0_60px_rgba(99,102,241,0.15)] transform rotate-12 pointer-events-none hidden sm:block"></div>

        <!-- Auth Card Slot -->
        <div class="w-full max-w-md relative z-10 my-auto">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
