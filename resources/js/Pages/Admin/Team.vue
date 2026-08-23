<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { 
  Users, 
  UserPlus, 
  Search, 
  Award, 
  CheckCircle2, 
  Layers, 
  TrendingUp, 
  Sparkles, 
  HelpCircle,
  Filter,
  RefreshCw,
  ChevronRight,
  ArrowRight
} from '@lucide/vue';

const props = defineProps({
  target_user: Object,
  summary: Object,
  team_levels: Array,
  filtered_members: Array,
  filters: Object,
});

const selectedLevel = ref(props.filters?.level || 'all');
const searchQuery = ref(props.filters?.search || '');
const isSearching = ref(false);

const formatRp = (val) => {
  return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
};

const handleFilter = () => {
  router.get(
    route('admin.team.index'),
    {
      level: selectedLevel.value,
      search: searchQuery.value,
    },
    { preserveState: true, replace: true }
  );
};

const selectLevelFilter = (lvl) => {
  selectedLevel.value = String(lvl);
  handleFilter();
};

const resetFilter = () => {
  selectedLevel.value = 'all';
  searchQuery.value = '';
  handleFilter();
};
</script>

<template>
  <Head title="Team Saya (Generasi Multi-Tier) - Mitra Syiar Baitullah" />

  <AdminLayout>
    <div class="space-y-6">

      <!-- Header Title & User Perspective Badge -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-[#e09d49]/30 shadow-sm">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-[#e98318]/15 text-[#e98318] border border-[#e09d49]/40 rounded-full">
              Sistem Generasi Unilevel (Multi-Tier)
            </span>
            <span class="text-xs text-[#9d7c64] font-medium hidden sm:inline">• 12 Level Kedalaman</span>
          </div>
          <h1 class="text-xl sm:text-2xl font-black text-[#5c2c24] tracking-tight uppercase">
            👥 TEAM SAYA
          </h1>
          <p class="text-xs sm:text-sm text-[#9d7c64] font-medium mt-0.5">
            Daftar mitra jaringan berdasarkan level generasi sponsor. Sponsor direct tanpa batas lebar!
          </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
          <button 
            @click="router.reload()" 
            class="px-3.5 py-2 bg-white border border-[#e09d49] text-[#5c2c24] text-xs font-bold rounded-xl hover:bg-[#fffaf2] transition-all flex items-center gap-1.5 shadow-xs cursor-pointer"
          >
            <RefreshCw class="w-3.5 h-3.5 text-[#e98318]" />
            <span>Refresh Data</span>
          </button>
        </div>
      </div>

      <!-- Stat Cards Summary (Top Grid) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Mitra -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">TOTAL MITRA TEAM</span>
            <div class="p-2.5 rounded-xl bg-[#e98318]/15 text-[#e98318]">
              <Users class="w-5 h-5" />
            </div>
          </div>
          <p class="text-3xl font-black text-[#5c2c24]">{{ summary.total_members }} <span class="text-xs font-bold text-[#9d7c64]">Mitra</span></p>
          <div class="text-[11px] font-medium text-emerald-600 flex items-center gap-1">
            <CheckCircle2 class="w-3.5 h-3.5" />
            <span>Akumulasi dari Team 1 s/d Team 12</span>
          </div>
        </div>

        <!-- Direct Sponsor (Team 1) -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">DIRECT SPONSOR (TEAM 1)</span>
            <div class="p-2.5 rounded-xl bg-[#2e395d]/10 text-[#2e395d]">
              <UserPlus class="w-5 h-5" />
            </div>
          </div>
          <p class="text-3xl font-black text-[#2e395d]">{{ summary.direct_sponsors }} <span class="text-xs font-bold text-[#9d7c64]">Orang</span></p>
          <div class="text-[11px] font-semibold text-[#e98318] flex items-center gap-1">
            <Sparkles class="w-3.5 h-3.5" />
            <span>Tanpa Batas Lebar (Unilevel)</span>
          </div>
        </div>

        <!-- Total Komisi Team -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">TOTAL KOMISI TEAM</span>
            <div class="p-2.5 rounded-xl bg-[#e09d49]/20 text-[#e98318]">
              <TrendingUp class="w-5 h-5" />
            </div>
          </div>
          <p class="text-2xl sm:text-3xl font-black text-[#5c2c24]">{{ formatRp(summary.total_commission) }}</p>
          <div class="text-[11px] font-medium text-[#9d7c64]">
            <span>Potensi pendapatan generasi</span>
          </div>
        </div>

        <!-- Level Aktif -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">LEVEL KEDALAMAN</span>
            <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
              <Layers class="w-5 h-5" />
            </div>
          </div>
          <p class="text-3xl font-black text-[#5c2c24]">{{ summary.active_levels }} <span class="text-xs font-bold text-[#9d7c64]">/ 12 Level</span></p>
          <div class="text-[11px] font-semibold text-indigo-600">
            <span>Kedalaman terisi saat ini</span>
          </div>
        </div>
      </div>

      <!-- Infographic Marketing Plan Rules Card -->
      <div class="bg-gradient-to-r from-[#2e395d] via-[#5c2c24] to-[#e98318] text-white p-6 sm:p-8 rounded-3xl shadow-lg relative overflow-hidden space-y-4">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div class="space-y-2 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/20 text-xs font-bold text-[#e09d49]">
              <Award class="w-3.5 h-3.5 text-[#e09d49]" />
              <span>Aturan Penjualan & Komisi Multi-Tier (PRD 2026)</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black tracking-tight leading-snug">
              Sponsor Direct Tanpa Batas — Kelipatan Bertumbuh di Setiap Team
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 font-medium leading-relaxed">
              • <strong>Team 1 (Direct Sponsor):</strong> Tempat semua mitra yang Anda ajak langsung (mitra ke-1, ke-5, ke-6, ke-15, dst tetap berada di Level 1 & tidak turun ke Level 2).<br>
              • <strong>Ujroh Sponsor:</strong> Rp 250.000 / mitra DP Join. <strong>Sponsori 2 Mitra = Balik Modal!</strong><br>
              • <strong>Komisi Team Multi-Tier:</strong> Didistribusikan dari pertumbuhan mitra di Team 1 s/d Team 12 (Rp 5.000 s/d Rp 1.000 per mitra).
            </p>
          </div>

          <div class="shrink-0 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 text-center space-y-2 min-w-[200px]">
            <span class="text-[10px] font-black tracking-wider uppercase text-[#e09d49] block">BIAYA JOIN UTAMA</span>
            <div class="text-xl font-black text-white font-mono">DP Rp 500.000</div>
            <p class="text-[11px] text-slate-200">Bonus Sponsor Direct: <strong>Rp 250.000</strong></p>
          </div>
        </div>
      </div>

      <!-- Team Matrix Breakdown Table (12 Level Matrix) -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-[#e09d49]/20 pb-4">
          <div>
            <h3 class="text-base font-black text-[#5c2c24] uppercase tracking-wide">
              📊 Matrix Potensi Komisi Team (Generasi 1 s/d 12)
            </h3>
            <p class="text-xs font-semibold text-[#9d7c64]">
              Klik tombol pada level untuk menyaring daftar mitra di bawah
            </p>
          </div>
          <div class="text-xs font-bold text-[#e98318] italic">
            Skema Duplikasi kelipatan bertumbuh
          </div>
        </div>

        <!-- 12 Levels Grid Summary Buttons -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
          <button
            v-for="lvl in team_levels"
            :key="lvl.level"
            @click="selectLevelFilter(lvl.level)"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all cursor-pointer flex flex-col justify-between space-y-1',
              String(selectedLevel) === String(lvl.level)
                ? 'bg-[#5c2c24] text-white border-[#5c2c24] shadow-md ring-2 ring-[#e98318]/50'
                : (lvl.count > 0 ? 'bg-[#fffaf2] border-[#e09d49]/60 hover:border-[#e98318] text-[#5c2c24]' : 'bg-gray-50 border-gray-200 text-gray-400 hover:border-gray-300')
            ]"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-black uppercase">{{ lvl.name }}</span>
              <span 
                :class="[
                  'text-[9px] font-black px-1.5 py-0.5 rounded-full',
                  lvl.count > 0 ? (String(selectedLevel) === String(lvl.level) ? 'bg-[#e98318] text-white' : 'bg-[#e98318]/20 text-[#e98318]') : 'bg-gray-200 text-gray-500'
                ]"
              >
                {{ lvl.count }} Mitra
              </span>
            </div>
            <div class="text-[11px] font-mono font-bold mt-1">
              {{ formatRp(lvl.commission_per_member) }} <span class="text-[9px] opacity-80 font-normal">/mitra</span>
            </div>
            <div class="text-[10px] opacity-90 truncate font-semibold">
              Total: {{ formatRp(lvl.total_commission) }}
            </div>
          </button>
        </div>
      </div>

      <!-- Filter Controls for Member List -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-5 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <!-- Level Select Filter -->
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <Filter class="w-4 h-4 text-[#e98318] shrink-0" />
            <label class="text-xs font-bold text-[#5c2c24] shrink-0">Filter Level:</label>
            <select
              v-model="selectedLevel"
              @change="handleFilter"
              class="w-full sm:w-48 h-10 px-3 bg-white border border-[#e09d49]/60 rounded-xl text-xs font-bold text-[#5c2c24] focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
            >
              <option value="all">Semua Level Team (1-12)</option>
              <option v-for="lvl in 12" :key="lvl" :value="String(lvl)">
                Team {{ lvl }} ({{ team_levels[lvl - 1]?.count || 0 }} Mitra)
              </option>
            </select>
          </div>

          <!-- Search Input -->
          <div class="relative w-full sm:w-72">
            <Search class="w-4 h-4 text-[#9d7c64] absolute left-3 top-1/2 -translate-y-1/2" />
            <input
              v-model="searchQuery"
              @keyup.enter="handleFilter"
              type="text"
              placeholder="Cari nama, username, email..."
              class="w-full h-10 pl-9 pr-3 bg-white border border-[#e09d49]/60 rounded-xl text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
            />
          </div>

          <!-- Reset Filter Button -->
          <button
            v-if="selectedLevel !== 'all' || searchQuery !== ''"
            @click="resetFilter"
            class="text-xs text-[#e98318] font-bold hover:underline shrink-0"
          >
            Reset Filter
          </button>
        </div>
      </div>

      <!-- Member List Section -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-[#e09d49]/20 pb-3">
          <div class="flex items-center gap-2">
            <Users class="w-4 h-4 text-[#e98318]" />
            <h3 class="text-sm font-black text-[#5c2c24] uppercase">
              Daftar Anggota Team
              <span class="text-[#e98318] ml-1">({{ filtered_members.length }} Anggota)</span>
            </h3>
          </div>
          <span class="text-xs text-[#9d7c64] font-semibold">
            Filter: {{ selectedLevel === 'all' ? 'Semua Team' : 'Team ' + selectedLevel }}
          </span>
        </div>

        <!-- Empty State if no members -->
        <div v-if="filtered_members.length === 0" class="py-12 text-center space-y-3">
          <div class="w-16 h-16 rounded-full bg-[#fffaf2] border border-[#e09d49]/40 text-[#e98318] flex items-center justify-center mx-auto text-2xl">
            👥
          </div>
          <h4 class="text-base font-bold text-[#5c2c24]">Belum Ada Mitra di Level Ini</h4>
          <p class="text-xs text-[#9d7c64] max-w-md mx-auto">
            Ajak calon mitra baru menggunakan link referral Anda untuk menambah jaringan anggota di Team 1!
          </p>
        </div>

        <!-- Responsive Card List for Mobile + Table for Desktop -->
        <div v-else>
          <!-- Desktop Table View -->
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs font-medium text-[#5c2c24]">
              <thead>
                <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[11px]">
                  <th class="p-3.5 rounded-l-xl">Mitra / Username</th>
                  <th class="p-3.5">Level Team</th>
                  <th class="p-3.5">Sponsor / Upline Direct</th>
                  <th class="p-3.5">Paket Join</th>
                  <th class="p-3.5">Tgl. Bergabung</th>
                  <th class="p-3.5 rounded-r-xl text-right">Komisi / Mitra</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#e09d49]/20">
                <tr v-for="m in filtered_members" :key="m.id" class="hover:bg-[#fffaf2] transition-colors">
                  <td class="p-3.5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#5c2c24] to-[#e98318] text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                        {{ m.name ? m.name.charAt(0).toUpperCase() : 'M' }}
                      </div>
                      <div>
                        <div class="font-bold text-[#5c2c24] text-xs">{{ m.name }}</div>
                        <div class="text-[11px] text-[#e98318] font-semibold">@{{ m.username }}</div>
                        <div class="text-[10px] text-[#9d7c64]">{{ m.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="p-3.5">
                    <span class="px-2.5 py-1 text-[10px] font-black bg-[#5c2c24] text-white rounded-full">
                      {{ m.level }}
                    </span>
                  </td>
                  <td class="p-3.5">
                    <span class="font-semibold text-slate-700">{{ m.sponsor }}</span>
                  </td>
                  <td class="p-3.5">
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-[#e98318]/15 text-[#e98318] border border-[#e09d49]/30 rounded-md">
                      {{ m.package }}
                    </span>
                  </td>
                  <td class="p-3.5 text-[#9d7c64] font-medium">{{ m.joined_at }}</td>
                  <td class="p-3.5 text-right font-black text-emerald-600 text-sm">
                    {{ formatRp(m.commission) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile Card List View -->
          <div class="block md:hidden space-y-3">
            <div 
              v-for="m in filtered_members" 
              :key="m.id"
              class="p-4 rounded-2xl bg-[#fffaf2] border border-[#e09d49]/40 space-y-2 text-xs"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#5c2c24] to-[#e98318] text-white font-bold flex items-center justify-center text-xs shrink-0">
                    {{ m.name ? m.name.charAt(0).toUpperCase() : 'M' }}
                  </div>
                  <div>
                    <h4 class="font-black text-[#5c2c24] text-xs">{{ m.name }}</h4>
                    <span class="text-[#e98318] font-bold text-[11px]">@{{ m.username }}</span>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-black bg-[#5c2c24] text-white rounded-full">
                  {{ m.level }}
                </span>
              </div>

              <div class="pt-2 border-t border-[#e09d49]/20 grid grid-cols-2 gap-2 text-[11px]">
                <div>
                  <span class="text-[#9d7c64] block text-[10px]">SPONSOR</span>
                  <span class="font-bold text-slate-800">{{ m.sponsor }}</span>
                </div>
                <div>
                  <span class="text-[#9d7c64] block text-[10px]">TGL. JOIN</span>
                  <span class="font-semibold text-slate-700">{{ m.joined_at }}</span>
                </div>
              </div>

              <div class="pt-2 border-t border-[#e09d49]/20 flex items-center justify-between text-xs">
                <span class="text-[11px] font-bold text-[#e98318]">{{ m.package }}</span>
                <span class="font-black text-emerald-600 text-sm">{{ formatRp(m.commission) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>
