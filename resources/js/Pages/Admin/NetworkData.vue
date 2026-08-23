<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { 
  Users, 
  Search, 
  CheckCircle2, 
  AlertCircle,
  Layers,
  UserCheck
} from '@lucide/vue';

const props = defineProps({
  members: Array,
  filters: Object,
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

const search = ref(props.filters?.search || '');

// Search filter with watcher
watch(search, (val) => {
  router.get(
    route('network-data.index'),
    { search: val },
    { preserveState: true, replace: true }
  );
});

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};
</script>

<template>
  <Head title="Direktori Semua Mitra - Mitra Syiar Baitullah" />

  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Flash Alert Notifications -->
      <div v-if="flashSuccess" class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
          <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
          <span>{{ flashSuccess }}</span>
        </div>
      </div>

      <div v-if="flashError" class="p-4 bg-rose-50 border border-rose-300 text-rose-800 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
          <AlertCircle class="w-4 h-4 text-rose-500 shrink-0" />
          <span>{{ flashError }}</span>
        </div>
      </div>

      <!-- Main Directory Card Container -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-6">
        
        <!-- Header & Search Bar Row -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#e09d49]/20 pb-5">
          <div class="flex items-start gap-3">
            <div class="p-2.5 bg-[#e98318]/15 text-[#e98318] rounded-2xl shrink-0">
              <Users class="w-6 h-6" />
            </div>
            <div>
              <h2 class="text-base md:text-lg font-black text-[#5c2c24] tracking-tight">
                Direktori Semua Mitra
              </h2>
              <p class="text-xs text-[#9d7c64]">
                Daftar seluruh mitra downline yang berada dalam jaringan Team Anda (Generasi 1-12).
              </p>
            </div>
          </div>

          <!-- Live Search Input -->
          <div class="relative w-full md:w-72">
            <Search class="w-4 h-4 text-[#9d7c64] absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input 
              v-model="search"
              type="text"
              placeholder="Cari Username / Nama..."
              class="w-full pl-9 pr-4 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-2xl text-[#5c2c24] placeholder-[#9d7c64]/50 text-xs font-semibold focus:outline-none focus:border-[#e98318] transition-colors"
            />
          </div>
        </div>

        <!-- Directory Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[10px] tracking-wider">
                <th class="py-3.5 px-4 rounded-l-xl">ID & USERNAME</th>
                <th class="py-3.5 px-4">NAMA LENGKAP / EMAIL</th>
                <th class="py-3.5 px-4">SPONSOR LANGSUNG</th>
                <th class="py-3.5 px-4">LEVEL TEAM</th>
                <th class="py-3.5 px-4 rounded-r-xl text-right">SALDO DOMPET</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr 
                v-for="m in members" 
                :key="m.id" 
                class="hover:bg-[#fffaf2] transition-colors"
              >
                <!-- ID & Username -->
                <td class="py-4 px-4 space-y-0.5">
                  <span class="text-[10px] font-mono font-bold text-[#9d7c64] block">{{ m.id_code }}</span>
                  <span class="font-black text-[#e98318] font-mono text-xs">@{{ m.username }}</span>
                </td>

                <!-- Nama Lengkap / Email -->
                <td class="py-4 px-4 space-y-0.5">
                  <h4 class="font-extrabold text-[#5c2c24] text-xs leading-tight">{{ m.name }}</h4>
                  <p class="text-[11px] text-[#9d7c64] font-medium">{{ m.email }}</p>
                </td>

                <!-- Sponsor Langsung -->
                <td class="py-4 px-4">
                  <span 
                    v-if="m.sponsor === 'FOUNDER'"
                    class="px-2.5 py-0.5 text-[9px] font-black bg-rose-50 text-rose-600 border border-rose-200 rounded-md uppercase tracking-wider"
                  >
                    FOUNDER
                  </span>
                  <span v-else class="font-extrabold text-[#5c2c24] font-mono text-xs">
                    {{ m.sponsor }}
                  </span>
                </td>

                <!-- LEVEL TEAM (Generasi 1, 2, 3, dst) -->
                <td class="py-4 px-4">
                  <span 
                    :class="[
                      m.level_num === 1 ? 'bg-amber-100 text-amber-800 border-amber-300' :
                      m.level_num === 2 ? 'bg-orange-100 text-orange-800 border-orange-300' :
                      'bg-slate-100 text-slate-700 border-slate-200',
                      'px-3 py-1 text-[10px] font-black rounded-lg border tracking-wide uppercase inline-flex items-center gap-1.5'
                    ]"
                  >
                    <Layers class="w-3 h-3 text-[#e98318]" />
                    <span>{{ m.level_team }}</span>
                  </span>
                </td>

                <!-- Saldo Dompet -->
                <td class="py-4 px-4 text-right">
                  <span class="font-black text-[#5c2c24] text-xs font-mono">
                    {{ formatRupiah(m.saldo) }}
                  </span>
                </td>
              </tr>

              <tr v-if="members.length === 0">
                <td colspan="5" class="py-12 text-center text-[#9d7c64] text-xs italic">
                  Tidak ada mitra jaringan yang ditemukan dalam Team Anda.
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>
