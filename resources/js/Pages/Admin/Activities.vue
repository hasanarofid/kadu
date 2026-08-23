<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { 
  UserPlus, 
  Users, 
  Award, 
  Activity, 
  CheckCircle2,
  Wallet,
  ArrowRightLeft
} from '@lucide/vue';

const props = defineProps({
  metrics: Object,
  active_tab: String,
  tab_description: String,
  logs: Array,
});

const switchTab = (tabKey) => {
  router.get(
    route('activities.index'),
    { tab: tabKey },
    { preserveState: true, preserveScroll: true }
  );
};

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};

const tabList = [
  { key: 'sponsor', label: 'Dana Operasional' },
  { key: 'team', label: 'Komisi Team' },
  { key: 'prestasi', label: 'Komisi Prestasi Agen' },
  { key: 'penarikan', label: 'Penarikan Saldo' },
];
</script>

<template>
  <Head title="Aktivitas & Rincian Mutasi Bonus - Mitra Syiar Baitullah" />

  <AdminLayout>
    <div class="space-y-6">
      
      <!-- 1. TOP METRIC CARDS ROW (3 Cards) -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <!-- Dana Operasional (Sponsor) -->
        <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-5 shadow-sm space-y-2 flex items-center justify-between">
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#9d7c64] block">DANA OPERASIONAL</span>
            <h3 class="text-2xl font-black text-[#5c2c24] tracking-tight">{{ formatRupiah(metrics?.dana_operasional || 0) }}</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-[#e98318]/15 text-[#e98318] flex items-center justify-center shrink-0">
            <UserPlus class="w-5 h-5" />
          </div>
        </div>

        <!-- Komisi Team -->
        <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-5 shadow-sm space-y-2 flex items-center justify-between">
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#9d7c64] block">KOMISI TEAM</span>
            <h3 class="text-2xl font-black text-[#5c2c24] tracking-tight">{{ formatRupiah(metrics?.komisi_team || 0) }}</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <Users class="w-5 h-5" />
          </div>
        </div>

        <!-- Komisi Prestasi Agen -->
        <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-5 shadow-sm space-y-2 flex items-center justify-between">
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#9d7c64] block">KOMISI PRESTASI AGEN</span>
            <h3 class="text-2xl font-black text-[#5c2c24] tracking-tight">{{ formatRupiah(metrics?.komisi_prestasi || 0) }}</h3>
          </div>
          <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <Award class="w-5 h-5" />
          </div>
        </div>

      </div>

      <!-- 2. MAIN TABLE CONTAINER CARD -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-5">
        
        <!-- Header Row with Filter Tabs -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-[#e09d49]/20 pb-5">
          <div class="flex items-start gap-3">
            <div class="p-2.5 bg-[#e98318]/15 text-[#e98318] rounded-2xl shrink-0 mt-0.5">
              <Activity class="w-5 h-5" />
            </div>
            <div>
              <h2 class="text-base font-black text-[#5c2c24] uppercase tracking-tight">RINCIAN MUTASI & AKTIVITAS BONUS</h2>
              <p class="text-xs text-[#9d7c64] font-medium mt-0.5">
                Histori pencairan & mutasi komisi syiar yang masuk langsung ke saldo e-wallet Anda.
              </p>
            </div>
          </div>

          <!-- Filter Navigation Tabs -->
          <div class="p-1 bg-[#fffaf2] border border-[#e09d49]/40 rounded-2xl flex items-center gap-1 self-start lg:self-auto overflow-x-auto max-w-full">
            <button 
              v-for="t in tabList" 
              :key="t.key"
              @click="switchTab(t.key)"
              :class="[
                active_tab === t.key 
                  ? 'bg-gradient-to-r from-[#e98318] to-[#5c2c24] text-white font-extrabold shadow-sm' 
                  : 'text-[#5c2c24] hover:bg-[#e98318]/10 font-bold',
                'px-4 py-2 text-xs rounded-xl transition-all cursor-pointer whitespace-nowrap'
              ]"
            >
              {{ t.label }}
            </button>
          </div>
        </div>

        <!-- Info Box Banner -->
        <div class="p-4 bg-[#fffaf2] border border-[#e09d49]/40 text-[#5c2c24] rounded-2xl text-xs font-semibold leading-relaxed flex items-start gap-2.5">
          <span class="text-[#e98318] font-bold shrink-0">ℹ️</span>
          <span>{{ tab_description }}</span>
        </div>

        <!-- Table List -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[10px] tracking-wider">
                <th class="py-3.5 px-4 rounded-l-xl">ID TRANSAKSI</th>
                <th class="py-3.5 px-4">TANGGAL & WAKTU</th>
                <th class="py-3.5 px-4">PEMBERI / SUMBER</th>
                <th class="py-3.5 px-4">DESKRIPSI TRANSAKSI</th>
                <th class="py-3.5 px-4 rounded-r-xl text-right">JUMLAH</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr 
                v-for="item in logs" 
                :key="item.id" 
                class="hover:bg-[#fffaf2] transition-colors"
              >
                <td class="py-3.5 px-4">
                  <span class="font-extrabold text-[#9d7c64] font-mono text-xs">{{ item.transaction_code }}</span>
                </td>
                <td class="py-3.5 px-4 text-[#9d7c64] font-mono text-xs">
                  {{ item.created_at }}
                </td>
                <td class="py-3.5 px-4 font-black text-[#e98318] font-mono text-xs">
                  {{ item.source }}
                </td>
                <td class="py-3.5 px-4 font-extrabold text-[#5c2c24]">
                  {{ item.description }}
                </td>
                <td 
                  :class="[
                    item.amount.startsWith('-') ? 'text-rose-600' : 'text-emerald-700',
                    'py-3.5 px-4 text-right font-black font-mono text-xs tracking-tight'
                  ]"
                >
                  {{ item.amount }}
                </td>
              </tr>

              <tr v-if="logs.length === 0">
                <td colspan="5" class="py-12 text-center text-[#9d7c64] text-xs italic">
                  Belum ada catatan mutasi bonus untuk kategori ini.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>
