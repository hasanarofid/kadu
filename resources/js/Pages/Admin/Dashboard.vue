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
        <!-- Low Token Users Alert Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-base font-bold text-amber-300 flex items-center gap-2">
              <ShieldAlert class="w-4 h-4 text-amber-400" />
              Monitoring Token Rendah (Perlu Top-up/Beli)
            </h3>
            <Link :href="route('admin.users.index')" class="text-xs font-bold text-amber-400 hover:underline">Kelola Token →</Link>
          </div>

          <div v-if="!low_token_users || low_token_users.length === 0" class="py-6 text-center text-xs text-slate-500">
            Seluruh pengguna memiliki kuota token mencukupi.
          </div>

          <div v-else class="divide-y divide-slate-800 text-xs">
            <div v-for="u in low_token_users" :key="u.id" class="py-3 flex items-center justify-between">
              <div>
                <p class="font-bold text-white">{{ u.name }}</p>
                <p class="text-xxs text-slate-400">{{ u.email }}</p>
              </div>
              <div class="flex items-center gap-3">
                <span :class="['px-2.5 py-1 rounded-full font-extrabold text-xxs border', (u.tokens ?? 0) === 0 ? 'bg-rose-500/10 text-rose-300 border-rose-500/30' : 'bg-amber-500/10 text-amber-300 border-amber-500/30']">
                  {{ u.tokens ?? 0 }} Token
                </span>
                <Link :href="route('admin.users.index')" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xxs rounded-lg shadow">
                  + Isi Token
                </Link>
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

      <!-- AI Token Mutation Log Table Card -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-base font-bold text-white flex items-center gap-2">
            <Coins class="w-4 h-4 text-indigo-400" />
            Histori Mutasi & Pemakaian AI Token System
          </h3>
          <span class="text-xxs font-bold text-slate-400">Real-Time Audit Log</span>
        </div>

        <div v-if="!recent_logs || recent_logs.length === 0" class="py-8 text-center text-xs text-slate-500">
          Belum ada riwayat mutasi token AI.
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-950 text-slate-400 font-bold uppercase text-xxs border-b border-slate-800">
              <tr>
                <th class="p-3">Pengguna</th>
                <th class="p-3">Aktivitas / Deskripsi</th>
                <th class="p-3 text-center">Tipe</th>
                <th class="p-3 text-right">Mutasi Token</th>
                <th class="p-3 text-right">Saldo Setelahnya</th>
                <th class="p-3 text-right">Waktu</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 text-slate-300">
              <tr v-for="log in recent_logs" :key="log.id" class="hover:bg-slate-850/50">
                <td class="p-3 font-bold text-white">{{ log.user?.name || 'User' }}</td>
                <td class="p-3 font-medium text-slate-300">{{ log.description }}</td>
                <td class="p-3 text-center">
                  <span :class="['px-2 py-0.5 rounded-full font-bold text-xxs uppercase border', log.type === 'purchase' || log.type === 'topup' ? 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30' : 'bg-rose-500/10 text-rose-300 border-rose-500/30']">
                    {{ log.type }}
                  </span>
                </td>
                <td :class="['p-3 text-right font-black', log.type === 'purchase' || log.type === 'topup' ? 'text-emerald-400' : 'text-rose-400']">
                  {{ log.type === 'purchase' || log.type === 'topup' ? '+' : '-' }}{{ log.tokens }} Token
                </td>
                <td class="p-3 text-right font-bold text-indigo-300">{{ log.balance_after }} Token</td>
                <td class="p-3 text-right text-xxs text-slate-400">{{ new Date(log.created_at).toLocaleString('id-ID') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
