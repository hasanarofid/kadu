<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { 
  FileSpreadsheet, 
  FileText, 
  FileDown, 
  Users, 
  Award, 
  Banknote, 
  Wallet,
  Layers
} from '@lucide/vue';

const props = defineProps({
  active_type: String,
  report_data: Array,
});

const switchTab = (type) => {
  router.get(
    route('reports.index'),
    { type: type },
    { preserveState: true, preserveScroll: true }
  );
};

const exportExcel = () => {
  window.open(route('reports.export-excel', { type: props.active_type }), '_blank');
};

const exportPdf = () => {
  window.open(route('reports.export-pdf', { type: props.active_type }), '_blank');
};

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};

const reportTabs = [
  { type: 'member', label: 'Laporan Mitra' },
  { type: 'bonus', label: 'Laporan Bonus' },
  { type: 'pencairan', label: 'Laporan Pencairan' },
  { type: 'topup', label: 'Laporan Topup' },
];
</script>

<template>
  <Head title="Menu Laporan - Mitra Syiar Baitullah" />

  <AdminLayout>
    <div class="space-y-6">
      
      <!-- 1. TOP HEADER & EXPORT ACTION BUTTONS -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#e09d49]/20 pb-5">
        <div>
          <h2 class="text-xl md:text-2xl font-black text-[#5c2c24] tracking-tight">
            Menu Laporan
          </h2>
          <p class="text-xs text-[#9d7c64] font-medium mt-0.5">
            Export data transaksi dan mitra ke Excel atau PDF.
          </p>
        </div>

        <!-- Export Buttons (Green Excel & Red PDF) -->
        <div class="flex items-center gap-2.5 shrink-0">
          <!-- Excel Button -->
          <button 
            @click="exportExcel"
            class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 rounded-2xl text-xs font-extrabold shadow-sm transition-all flex items-center gap-2 cursor-pointer"
          >
            <FileSpreadsheet class="w-4 h-4 text-emerald-600" />
            <span>Excel</span>
          </button>

          <!-- PDF Button -->
          <button 
            @click="exportPdf"
            class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 rounded-2xl text-xs font-extrabold shadow-sm transition-all flex items-center gap-2 cursor-pointer"
          >
            <FileText class="w-4 h-4 text-rose-600" />
            <span>PDF</span>
          </button>
        </div>
      </div>

      <!-- 2. REPORT NAVIGATION TABS -->
      <div class="flex items-center gap-2.5 overflow-x-auto pb-1 max-w-full">
        <button 
          v-for="t in reportTabs" 
          :key="t.type"
          @click="switchTab(t.type)"
          :class="[
            active_type === t.type 
              ? 'bg-gradient-to-r from-[#e98318] to-[#5c2c24] text-white font-extrabold shadow-md' 
              : 'bg-white text-[#5c2c24] hover:bg-[#fffaf2] font-bold border border-[#e09d49]/40',
            'px-5 py-2.5 text-xs rounded-2xl transition-all cursor-pointer whitespace-nowrap'
          ]"
        >
          {{ t.label }}
        </button>
      </div>

      <!-- 3. MAIN TABLE DATA CONTAINER CARD -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm">
        <div class="overflow-x-auto">
          
          <!-- TAB 1: LAPORAN MITRA -->
          <table v-if="active_type === 'member'" class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[10px] tracking-wider">
                <th class="py-3.5 px-4 rounded-l-xl">MITRA</th>
                <th class="py-3.5 px-4">AGEN SPONSOR</th>
                <th class="py-3.5 px-4">TEAM</th>
                <th class="py-3.5 px-4">SALDO WALLET</th>
                <th class="py-3.5 px-4 rounded-r-xl text-right">TGL DAFTAR</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr v-for="row in report_data" :key="row.id" class="hover:bg-[#fffaf2] transition-colors">
                <!-- Mitra -->
                <td class="py-3.5 px-4 space-y-0.5">
                  <h4 class="font-extrabold text-[#5c2c24] text-xs leading-tight">{{ row.name }}</h4>
                  <p class="text-[11px] text-[#9d7c64] font-mono">@{{ row.username }}</p>
                </td>

                <!-- Agen Sponsor -->
                <td class="py-3.5 px-4 font-mono text-[#5c2c24] font-bold">
                  {{ row.sponsor }}
                </td>

                <!-- TEAM (Level Generasi) -->
                <td class="py-3.5 px-4">
                  <span class="px-2.5 py-1 text-[10px] font-black bg-[#e98318]/15 text-[#e98318] rounded-lg border border-[#e09d49]/40 uppercase tracking-wider inline-flex items-center gap-1">
                    <Layers class="w-3 h-3 text-[#e98318]" />
                    <span>{{ row.team }}</span>
                  </span>
                </td>

                <!-- Saldo Wallet -->
                <td class="py-3.5 px-4 font-black text-[#5c2c24] font-mono text-xs">
                  {{ formatRupiah(row.saldo) }}
                </td>

                <!-- Tgl Daftar -->
                <td class="py-3.5 px-4 text-right text-[#9d7c64] font-mono text-xs">
                  {{ row.created_at }}
                </td>
              </tr>

              <tr v-if="report_data.length === 0">
                <td colspan="5" class="py-12 text-center text-[#9d7c64] text-xs italic">
                  Belum ada data mitra terdaftar.
                </td>
              </tr>
            </tbody>
          </table>

          <!-- TAB 2: LAPORAN BONUS -->
          <table v-else-if="active_type === 'bonus'" class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[10px] tracking-wider">
                <th class="py-3.5 px-4 rounded-l-xl">MITRA</th>
                <th class="py-3.5 px-4">JENIS BONUS</th>
                <th class="py-3.5 px-4">SUMBER MITRA</th>
                <th class="py-3.5 px-4">DESKRIPSI</th>
                <th class="py-3.5 px-4">NOMINAL BONUS</th>
                <th class="py-3.5 px-4 rounded-r-xl text-right">TANGGAL</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr v-for="row in report_data" :key="row.id" class="hover:bg-[#fffaf2] transition-colors">
                <td class="py-3.5 px-4 space-y-0.5">
                  <h4 class="font-extrabold text-[#5c2c24] text-xs">{{ row.name }}</h4>
                  <p class="text-[11px] text-[#9d7c64] font-mono">@{{ row.username }}</p>
                </td>
                <td class="py-3.5 px-4">
                  <span class="px-2.5 py-0.5 text-[9px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-md uppercase tracking-wider">
                    {{ row.category }}
                  </span>
                </td>
                <td class="py-3.5 px-4 font-mono font-bold text-[#5c2c24]">{{ row.source }}</td>
                <td class="py-3.5 px-4 text-[#5c2c24] font-medium">{{ row.description }}</td>
                <td class="py-3.5 px-4 font-black text-emerald-700 font-mono text-xs">+{{ formatRupiah(row.amount) }}</td>
                <td class="py-3.5 px-4 text-right text-[#9d7c64] font-mono text-xs">{{ row.created_at }}</td>
              </tr>
            </tbody>
          </table>

          <!-- TAB 3: LAPORAN PENCAIRAN -->
          <table v-else-if="active_type === 'pencairan'" class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[10px] tracking-wider">
                <th class="py-3.5 px-4 rounded-l-xl">MITRA</th>
                <th class="py-3.5 px-4">REKENING TUJUAN</th>
                <th class="py-3.5 px-4">NOMINAL WD</th>
                <th class="py-3.5 px-4">STATUS</th>
                <th class="py-3.5 px-4 rounded-r-xl text-right">TANGGAL</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr v-for="row in report_data" :key="row.id" class="hover:bg-[#fffaf2] transition-colors">
                <td class="py-3.5 px-4 space-y-0.5">
                  <h4 class="font-extrabold text-[#5c2c24] text-xs">{{ row.name }}</h4>
                  <p class="text-[11px] text-[#9d7c64] font-mono">@{{ row.username }}</p>
                </td>
                <td class="py-3.5 px-4 font-medium text-slate-700">
                  {{ row.bank_name }} - <strong class="font-mono text-[#5c2c24]">{{ row.bank_account_number }}</strong> a.n {{ row.bank_account_name }}
                </td>
                <td class="py-3.5 px-4 font-black text-[#5c2c24] font-mono text-xs">{{ formatRupiah(row.amount) }}</td>
                <td class="py-3.5 px-4">
                  <span 
                    :class="[
                      row.status === 'APPROVED' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' :
                      row.status === 'REJECTED' ? 'bg-rose-100 text-rose-700 border-rose-200' :
                      'bg-amber-100 text-amber-700 border-amber-200',
                      'px-2.5 py-0.5 text-[9px] font-extrabold rounded-md border uppercase tracking-wider'
                    ]"
                  >
                    {{ row.status }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-right text-[#9d7c64] font-mono text-xs">{{ row.created_at }}</td>
              </tr>
            </tbody>
          </table>

          <!-- TAB 4: LAPORAN TOPUP -->
          <table v-else-if="active_type === 'topup'" class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[10px] tracking-wider">
                <th class="py-3.5 px-4 rounded-l-xl">MITRA</th>
                <th class="py-3.5 px-4">KATEGORI</th>
                <th class="py-3.5 px-4">DESKRIPSI</th>
                <th class="py-3.5 px-4">NOMINAL</th>
                <th class="py-3.5 px-4 rounded-r-xl text-right">TANGGAL</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr v-for="row in report_data" :key="row.id" class="hover:bg-[#fffaf2] transition-colors">
                <td class="py-3.5 px-4 space-y-0.5">
                  <h4 class="font-extrabold text-[#5c2c24] text-xs">{{ row.name }}</h4>
                  <p class="text-[11px] text-[#9d7c64] font-mono">@{{ row.username }}</p>
                </td>
                <td class="py-3.5 px-4">
                  <span class="px-2.5 py-0.5 text-[9px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200 rounded-md uppercase tracking-wider">
                    {{ row.category }}
                  </span>
                </td>
                <td class="py-3.5 px-4 font-medium text-[#5c2c24]">{{ row.description }}</td>
                <td class="py-3.5 px-4 font-black text-[#5c2c24] font-mono text-xs">
                  {{ formatRupiah(row.amount) }}
                </td>
                <td class="py-3.5 px-4 text-right text-[#9d7c64] font-mono text-xs">{{ row.created_at }}</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>

    </div>
  </AdminLayout>
</template>
