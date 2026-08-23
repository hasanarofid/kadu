<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
  Wallet, 
  Clock, 
  CheckCircle2, 
  AlertCircle, 
  Send, 
  Check, 
  X, 
  Banknote,
  ListFilter,
  Building2,
  UserCheck,
  PiggyBank,
  ShieldAlert,
  HelpCircle
} from '@lucide/vue';

const props = defineProps({
  wallet: Object,
  user_bank: Object,
  withdrawals: Array,
  is_admin: Boolean,
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

const isUsingProfileBank = ref(false);

const form = useForm({
  bank_name: props.user_bank?.bank_name || 'Bank Mandiri',
  bank_account_number: props.user_bank?.bank_account_number || '',
  bank_account_name: props.user_bank?.bank_account_name || '',
  amount: '',
});

// Real-time calculation previews
const grossAmount = computed(() => Number(form.amount) || 0);
const adminFeeCalc = computed(() => grossAmount.value * 0.10);
const umrohSavingCalc = computed(() => grossAmount.value * 0.10);
const netReceivedCalc = computed(() => grossAmount.value * 0.80);

const toggleProfileBank = () => {
  isUsingProfileBank.value = !isUsingProfileBank.value;
  if (isUsingProfileBank.value) {
    form.bank_name = props.user_bank?.bank_name || 'Bank Mandiri';
    form.bank_account_number = props.user_bank?.bank_account_number || '';
    form.bank_account_name = props.user_bank?.bank_account_name || '';
  }
};

const submitWithdrawal = () => {
  form.post(route('admin.withdrawals.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.amount = '';
    },
  });
};

// Admin Action Forms
const approveForm = useForm({});
const rejectForm = useForm({
  notes: '',
});

const approveWithdrawal = (id) => {
  if (confirm('Apakah Anda yakin ingin MENYETUJUI permohonan penarikan saldo ini?')) {
    approveForm.post(route('admin.withdrawals.approve', id), {
      preserveScroll: true,
    });
  }
};

const rejectWithdrawal = (id) => {
  const notes = prompt('Masukkan alasan penolakan penarikan saldo:');
  if (notes !== null) {
    rejectForm.notes = notes;
    rejectForm.post(route('admin.withdrawals.reject', id), {
      preserveScroll: true,
    });
  }
};

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};

const bankList = [
  'Bank Mandiri',
  'Bank Central Asia (BCA)',
  'Bank Rakyat Indonesia (BRI)',
  'Bank Negara Indonesia (BNI)',
  'Bank Syariah Indonesia (BSI)',
  'CIMB Niaga',
  'Bank Permata',
  'Bank Danamon',
  'DANA',
  'OVO',
  'GoPay',
  'ShopeePay'
];
</script>

<template>
  <Head title="Penarikan Bonus (WD) - Mitra Syiar Baitullah" />

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

      <!-- 1. TOP HEADER SUMMARY CARD -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#9d7c64] block">
            SALDO BONUS UTAMA TERSEDIA
          </span>
          <h2 class="text-3xl md:text-4xl font-black text-[#5c2c24] tracking-tight">
            {{ formatRupiah(wallet?.saldo || 0) }}
          </h2>
          <p class="text-xs text-[#9d7c64] font-medium pt-0.5">
            Min. Penarikan: <strong class="text-[#5c2c24] font-bold">{{ formatRupiah(wallet?.min_withdrawal || 50000) }}</strong> | Potongan WD: <strong class="text-amber-700 font-bold">10% Admin + 10% Tabungan Umroh</strong> (Diterima Bersih: <strong>80%</strong>)
          </p>
        </div>

        <!-- Right Side Badges (Saldo Umroh, Total Cair, & Sedang Diproses) -->
        <div class="flex items-center gap-3 shrink-0 flex-wrap">
          <!-- Saldo Tabungan Umroh Badge -->
          <div class="px-4 py-3 bg-[#fffaf2] border border-[#e09d49]/40 rounded-2xl flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#5c2c24] text-[#e09d49] flex items-center justify-center shrink-0 shadow-sm">
              <PiggyBank class="w-5 h-5" />
            </div>
            <div>
              <span class="text-[9px] font-extrabold text-[#5c2c24] uppercase tracking-wider block">TABUNGAN UMROH</span>
              <span class="text-sm font-black text-[#e98318] font-mono">{{ formatRupiah(wallet?.saldo_umroh || 0) }}</span>
            </div>
          </div>

          <!-- Total Cair Badge Pill -->
          <div class="px-4 py-3 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
              <Wallet class="w-5 h-5" />
            </div>
            <div>
              <span class="text-[9px] font-extrabold text-emerald-800 uppercase tracking-wider block">TOTAL CAIR</span>
              <span class="text-sm font-black text-emerald-700 font-mono">{{ formatRupiah(wallet?.total_cair || 0) }}</span>
            </div>
          </div>

          <!-- Sedang Diproses Badge Pill -->
          <div class="px-4 py-3 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
              <Clock class="w-5 h-5" />
            </div>
            <div>
              <span class="text-[9px] font-extrabold text-amber-800 uppercase tracking-wider block">SEDANG DIPROSES</span>
              <span class="text-sm font-black text-amber-700 font-mono">{{ formatRupiah(wallet?.total_proses || 0) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. MAIN LAYOUT GRID (Left: Form WD, Right: Queue List) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN: Formulir Penarikan Saldo (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
          <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-5">
            <div class="flex items-center gap-2 border-b border-[#e09d49]/20 pb-4">
              <div class="p-2 bg-[#e98318]/15 text-[#e98318] rounded-xl">
                <Banknote class="w-5 h-5" />
              </div>
              <h3 class="text-xs font-black text-[#5c2c24] uppercase tracking-tight">FORMULIR PENARIKAN BONUS</h3>
            </div>

            <!-- Profile Bank Toggle Badge Button -->
            <button 
              type="button"
              @click="toggleProfileBank"
              :class="[
                isUsingProfileBank ? 'bg-[#5c2c24] text-white border-[#5c2c24]' : 'bg-[#fffaf2] text-[#5c2c24] border-[#e09d49]/60 hover:bg-[#e98318]/10',
                'w-full py-2.5 px-4 rounded-xl border text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer'
              ]"
            >
              <div :class="[isUsingProfileBank ? 'bg-[#e98318] text-white' : 'border border-[#e09d49] bg-white text-transparent', 'w-4 h-4 rounded flex items-center justify-center transition-colors']">
                <Check class="w-3 h-3 stroke-[3]" />
              </div>
              <span>Gunakan Rekening Bank di Profil Saya</span>
            </button>

            <!-- Form -->
            <form @submit.prevent="submitWithdrawal" class="space-y-4">
              <!-- Pilih Bank Tujuan -->
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  PILIH BANK TUJUAN
                </label>
                <select 
                  v-model="form.bank_name"
                  required
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                >
                  <option v-for="bank in bankList" :key="bank" :value="bank">
                    {{ bank }}
                  </option>
                </select>
              </div>

              <!-- Nomor Rekening / No HP -->
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NOMOR REKENING / NO. HP
                </label>
                <input 
                  v-model="form.bank_account_number"
                  type="text"
                  required
                  placeholder="Masukkan nomor rekening tujuan"
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] placeholder-[#9d7c64]/50 text-xs font-semibold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <!-- Nama Pemilik Rekening -->
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NAMA PEMILIK REKENING
                </label>
                <input 
                  v-model="form.bank_account_name"
                  type="text"
                  required
                  placeholder="Nama lengkap pemilik rekening"
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] placeholder-[#9d7c64]/50 text-xs font-semibold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <!-- Nominal Penarikan (Rupiah) -->
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NOMINAL PENARIKAN (RUPIAH)
                </label>
                <input 
                  v-model="form.amount"
                  type="number"
                  required
                  min="50000"
                  step="1000"
                  placeholder="Min. Rp 50.000"
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] placeholder-[#9d7c64]/50 text-xs font-black focus:outline-none focus:border-[#e98318]"
                />
                <div class="flex items-center justify-between text-[10px] mt-1.5 font-medium">
                  <span class="text-[#9d7c64]">Min. {{ formatRupiah(wallet?.min_withdrawal || 50000) }}</span>
                  <span class="text-[#9d7c64]">Max: <strong class="text-[#e98318] font-bold">{{ formatRupiah(wallet?.saldo || 0) }}</strong></span>
                </div>
              </div>

              <!-- Real-time Deduction Breakdown Card -->
              <div v-if="grossAmount > 0" class="p-3.5 bg-[#fffaf2] border border-[#e09d49]/40 rounded-2xl space-y-2 text-xs">
                <div class="flex items-center justify-between text-[11px] text-[#5c2c24]">
                  <span>Nominal WD Kotor (100%):</span>
                  <strong class="font-mono font-bold">{{ formatRupiah(grossAmount) }}</strong>
                </div>
                <div class="flex items-center justify-between text-[11px] text-amber-800">
                  <span>Potongan Admin (10%):</span>
                  <span class="font-mono">- {{ formatRupiah(adminFeeCalc) }}</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-[#e98318]">
                  <span>Tabungan Umroh (10%):</span>
                  <span class="font-mono">+ {{ formatRupiah(umrohSavingCalc) }}</span>
                </div>
                <div class="pt-2 border-t border-[#e09d49]/30 flex items-center justify-between font-black text-[#5c2c24]">
                  <span>Transfer Bersih Diterima (80%):</span>
                  <span class="font-mono text-emerald-700 text-sm">{{ formatRupiah(netReceivedCalc) }}</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit"
                :disabled="form.processing"
                class="w-full py-3.5 bg-gradient-to-r from-[#e98318] via-[#e09d49] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white text-xs font-black rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
              >
                <Send class="w-4 h-4" />
                <span>Kirim Permohonan WD</span>
              </button>
            </form>
          </div>
        </div>

        <!-- RIGHT COLUMN: Antrean Penarikan Semua Mitra (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
          <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#e09d49]/20 pb-4">
              <div class="flex items-center gap-2">
                <ListFilter class="w-4 h-4 text-[#e98318]" />
                <h3 class="text-xs font-black text-[#5c2c24] uppercase tracking-tight">
                  {{ is_admin ? 'ANTREAN PENARIKAN SEMUA MITRA (ADMIN)' : 'RIWAYAT PENARIKAN SALDO ANDA' }}
                </h3>
              </div>
              <span class="px-2.5 py-1 text-[10px] font-extrabold bg-[#e98318]/15 text-[#e98318] rounded-full border border-[#e09d49]/30">
                {{ withdrawals.length }} Transaksi
              </span>
            </div>

            <!-- Withdrawals Queue List / Empty State -->
            <div v-if="withdrawals.length === 0" class="p-12 text-center border-2 border-dashed border-[#e09d49]/30 rounded-3xl bg-[#fffaf2]/50">
              <p class="text-xs text-[#9d7c64] italic font-medium">
                Belum ada permohonan penarikan saldo di sistem.
              </p>
            </div>

            <div v-else class="space-y-3">
              <div 
                v-for="item in withdrawals" 
                :key="item.id"
                class="p-4 bg-[#fffaf2] border border-[#e09d49]/30 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-white transition-colors"
              >
                <div class="space-y-1.5">
                  <div class="flex items-center gap-2">
                    <span class="font-bold text-[#5c2c24] text-xs">{{ item.user_name }}</span>
                    <span class="text-[10px] text-[#9d7c64] font-medium">@{{ item.user_username }}</span>
                    <span 
                      :class="[
                        item.status === 'approved' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                        item.status === 'rejected' ? 'bg-rose-100 text-rose-700 border-rose-200' : 
                        'bg-amber-100 text-amber-700 border-amber-200',
                        'px-2 py-0.5 text-[9px] font-extrabold rounded-md border uppercase tracking-wider ml-1'
                      ]"
                    >
                      {{ item.status === 'approved' ? 'DISETUJUI' : item.status === 'rejected' ? 'DITOLAK' : 'PENDING' }}
                    </span>
                  </div>

                  <p class="text-xs text-slate-700 font-medium">
                    {{ item.bank_name }} - <strong class="text-[#5c2c24] font-mono">{{ item.bank_account_number }}</strong> a.n {{ item.bank_account_name }}
                  </p>

                  <!-- Breakdown Badge Pill -->
                  <div class="text-[10px] text-[#9d7c64] flex items-center gap-3 pt-0.5 flex-wrap">
                    <span>Admin 10%: <strong class="text-slate-700 font-mono">{{ formatRupiah(item.fee) }}</strong></span>
                    <span>Tabungan Umroh 10%: <strong class="text-[#e98318] font-mono">{{ formatRupiah(item.umroh_saving) }}</strong></span>
                    <span>Transfer Bersih 80%: <strong class="text-emerald-700 font-bold font-mono">{{ formatRupiah(item.net_received) }}</strong></span>
                  </div>

                  <p class="text-[10px] text-[#9d7c64]">
                    Diajukan pada: {{ item.created_at }}
                    <span v-if="item.admin_notes" class="text-rose-500 font-semibold block mt-0.5">Catatan: {{ item.admin_notes }}</span>
                  </p>
                </div>

                <!-- Right Side: Amount & Admin Actions -->
                <div class="flex flex-col items-end gap-2 shrink-0">
                  <span class="text-xs text-[#9d7c64] font-bold">WD Kotor:</span>
                  <span class="text-sm font-black text-[#5c2c24] font-mono tracking-tight">
                    {{ formatRupiah(item.amount) }}
                  </span>

                  <!-- Admin Action Buttons (Approve / Reject) -->
                  <div v-if="is_admin && item.status === 'pending'" class="flex items-center gap-1.5 pt-1">
                    <button 
                      @click="approveWithdrawal(item.id)"
                      class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg shadow-sm transition-colors flex items-center gap-1 cursor-pointer"
                    >
                      <Check class="w-3 h-3" />
                      <span>Setujui</span>
                    </button>

                    <button 
                      @click="rejectWithdrawal(item.id)"
                      class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold rounded-lg shadow-sm transition-colors flex items-center gap-1 cursor-pointer"
                    >
                      <X class="w-3 h-3" />
                      <span>Tolak</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>
