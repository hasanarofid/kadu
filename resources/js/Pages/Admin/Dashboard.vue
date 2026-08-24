<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
  Users, 
  FileText, 
  Coins, 
  CreditCard, 
  Sparkles, 
  Plus, 
  ArrowUpRight,
  ShieldAlert
} from 'lucide-vue-next';

const props = defineProps({
  stats: Object,
  recent_users: Array,
  recent_rpps: Array,
  recent_transactions: Array,
});
</script>

<template>
  <AdminLayout>
    <Head title="Admin Dashboard - KADU" />

    <div class="space-y-8 text-left">
      <!-- Header Welcome Banner -->
      <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-950 border border-indigo-500/30 shadow-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-2">
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/20 rounded-full text-indigo-300 text-xxs font-bold border border-indigo-500/30">
            <Sparkles class="w-3.5 h-3.5 text-indigo-400" />
            Panel Kontrol Administrator KADU
          </div>
          <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
            Dashboard Utama Administrator
          </h1>
          <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-xl">
            Ringkasan aktivitas penggunaan sistem RPP Deep Learning Vokasi, pengelolaan kuota token, dan pengguna.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <Link 
            :href="route('admin.users.index')" 
            class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-2xl shadow"
          >
            Kelola User & Token →
          </Link>
        </div>
      </div>

      <!-- Stats Metric Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 flex items-center justify-between shadow-lg">
          <div class="space-y-1">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</span>
            <p class="text-2xl font-black text-white">{{ stats.total_users }} User</p>
          </div>
          <div class="p-3.5 rounded-2xl bg-indigo-600/10 text-indigo-400 border border-indigo-500/20">
            <Users class="w-6 h-6" />
          </div>
        </div>

        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 flex items-center justify-between shadow-lg">
          <div class="space-y-1">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Total RPP Generated</span>
            <p class="text-2xl font-black text-white">{{ stats.total_rpps }} RPP</p>
          </div>
          <div class="p-3.5 rounded-2xl bg-violet-600/10 text-violet-400 border border-violet-500/20">
            <FileText class="w-6 h-6" />
          </div>
        </div>

        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 flex items-center justify-between shadow-lg">
          <div class="space-y-1">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Paket Token Aktif</span>
            <p class="text-2xl font-black text-white">{{ stats.total_packages }} Paket</p>
          </div>
          <div class="p-3.5 rounded-2xl bg-amber-600/10 text-amber-400 border border-amber-500/20">
            <Coins class="w-6 h-6" />
          </div>
        </div>

        <div class="p-5 rounded-3xl bg-slate-900/80 border border-slate-800 flex items-center justify-between shadow-lg">
          <div class="space-y-1">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
            <p class="text-xl font-black text-emerald-400">Rp {{ Number(stats.total_revenue).toLocaleString('id-ID') }}</p>
          </div>
          <div class="p-3.5 rounded-2xl bg-emerald-600/10 text-emerald-400 border border-emerald-500/20">
            <CreditCard class="w-6 h-6" />
          </div>
        </div>
      </div>

      <!-- Recent Tables Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users Table Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
              <Users class="w-4 h-4 text-indigo-400" />
              Pengguna Terbaru
            </h3>
            <Link :href="route('admin.users.index')" class="text-xs font-bold text-indigo-400 hover:underline">Lihat Semua →</Link>
          </div>

          <div class="divide-y divide-slate-800 text-xs">
            <div v-for="u in recent_users" :key="u.id" class="py-3 flex items-center justify-between">
              <div>
                <p class="font-bold text-white">{{ u.name }}</p>
                <p class="text-xxs text-slate-400">{{ u.email }}</p>
              </div>
              <div class="text-right">
                <span class="px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-300 font-bold border border-indigo-500/20 text-xxs">
                  {{ u.tokens ?? 0 }} Token
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent RPP Generated Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
              <FileText class="w-4 h-4 text-violet-400" />
              RPP Terbaru Dibuat
            </h3>
          </div>

          <div class="divide-y divide-slate-800 text-xs">
            <div v-for="r in recent_rpps" :key="r.id" class="py-3 flex items-center justify-between">
              <div class="max-w-xs">
                <p class="font-bold text-white truncate">{{ r.title }}</p>
                <p class="text-xxs text-slate-400">Oleh: {{ r.user?.name || 'User' }}</p>
              </div>
              <Link :href="route('rpps.show', r.id)" class="text-xxs font-bold px-2.5 py-1 rounded-xl bg-slate-800 text-indigo-300 hover:bg-slate-700">
                Lihat
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
