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
  UserCheck
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
  <Head title="Penarikan Saldo (WD) - XSELLER" />

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

      <!-- 1. TOP HEADER SUMMARY CARD (White Card matching Mockup) -->
      <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">
            SALDO E-WALLET ANDA SAAT INI
          </span>
          <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
            {{ formatRupiah(wallet?.saldo || 2500000) }}
          </h2>
          <p class="text-xs text-slate-500 font-medium pt-0.5">
            Min. Penarikan: <strong class="text-slate-800">{{ formatRupiah(wallet?.min_withdrawal || 50000) }}</strong> | Biaya Admin: <strong class="text-emerald-600">{{ formatRupiah(wallet?.admin_fee || 0) }}</strong>
          </p>
        </div>

        <!-- Right Side Badges (Total Cair & Sedang Diproses) -->
        <div class="flex items-center gap-3 shrink-0 flex-wrap">
          <!-- Total Cair Badge Pill -->
          <div class="px-5 py-3 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm">
              <Wallet class="w-5 h-5" />
            </div>
            <div>
              <span class="text-[9px] font-extrabold text-emerald-800 uppercase tracking-wider block">TOTAL CAIR</span>
              <span class="text-sm font-black text-emerald-700 font-mono">{{ formatRupiah(wallet?.total_cair || 0) }}</span>
            </div>
          </div>

          <!-- Sedang Diproses Badge Pill -->
          <div class="px-5 py-3 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-center gap-3">
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
          <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-5">
            <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
              <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                <Banknote class="w-5 h-5" />
              </div>
              <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight">FORMULIR PENARIKAN SALDO</h3>
            </div>

            <!-- Profile Bank Toggle Badge Button -->
            <button 
              type="button"
              @click="toggleProfileBank"
              :class="[
                isUsingProfileBank ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-emerald-50/70 text-emerald-700 border-emerald-200 hover:bg-emerald-100',
                'w-full py-2.5 px-4 rounded-xl border text-xs font-bold flex items-center justify-center gap-2 transition-all cursor-pointer'
              ]"
            >
              <div :class="[isUsingProfileBank ? 'bg-emerald-600 text-white' : 'border border-emerald-400 bg-white text-transparent', 'w-4 h-4 rounded flex items-center justify-center transition-colors']">
                <Check class="w-3 h-3 stroke-[3]" />
              </div>
              <span>Gunakan Rekening Bank di Profil Saya</span>
            </button>

            <!-- Form -->
            <form @submit.prevent="submitWithdrawal" class="space-y-4">
              <!-- Pilih Bank Tujuan -->
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                  PILIH BANK TUJUAN
                </label>
                <select 
                  v-model="form.bank_name"
                  required
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs font-medium focus:outline-none focus:border-emerald-500"
                >
                  <option v-for="bank in bankList" :key="bank" :value="bank">
                    {{ bank }}
                  </option>
                </select>
              </div>

              <!-- Nomor Rekening / No HP -->
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                  NOMOR REKENING / NO. HP
                </label>
                <input 
                  v-model="form.bank_account_number"
                  type="text"
                  required
                  placeholder="Masukkan nomor rekening tujuan"
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-xs font-medium focus:outline-none focus:border-emerald-500"
                />
              </div>

              <!-- Nama Pemilik Rekening -->
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                  NAMA PEMILIK REKENING
                </label>
                <input 
                  v-model="form.bank_account_name"
                  type="text"
                  required
                  placeholder="Nama lengkap pemilik rekening"
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-xs font-medium focus:outline-none focus:border-emerald-500"
                />
              </div>

              <!-- Nominal Penarikan (Rupiah) -->
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                  NOMINAL PENARIKAN (RUPIAH)
                </label>
                <input 
                  v-model="form.amount"
                  type="number"
                  required
                  min="50000"
                  step="1000"
                  placeholder="Min. Rp 50.000"
                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-xs font-bold focus:outline-none focus:border-emerald-500"
                />
                <div class="flex items-center justify-between text-[10px] mt-1.5 font-medium">
                  <span class="text-slate-400">Min. {{ formatRupiah(wallet?.min_withdrawal || 50000) }}</span>
                  <span class="text-slate-500">Max. Bisa ditarik: <strong class="text-emerald-600 font-bold">{{ formatRupiah(wallet?.saldo || 2500000) }}</strong></span>
                </div>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit"
                :disabled="form.processing"
                class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-bold rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
              >
                <Send class="w-4 h-4" />
                <span>Kirim Permohonan WD</span>
              </button>
            </form>
          </div>
        </div>

        <!-- RIGHT COLUMN: Antrean Penarikan Semua Member (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
          <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
              <div class="flex items-center gap-2">
                <ListFilter class="w-4 h-4 text-emerald-600" />
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight">
                  {{ is_admin ? 'ANTREAN PENARIKAN SEMUA MEMBER (ADMIN)' : 'RIWAYAT PENARIKAN SALDO ANDA' }}
                </h3>
              </div>
              <span class="px-2.5 py-1 text-[10px] font-extrabold bg-slate-100 text-slate-600 rounded-full">
                {{ withdrawals.length }} Transaksi
              </span>
            </div>

            <!-- Withdrawals Queue List / Empty State -->
            <div v-if="withdrawals.length === 0" class="p-12 text-center border-2 border-dashed border-slate-100 rounded-3xl bg-slate-50/50">
              <p class="text-xs text-slate-400 italic font-medium">
                Belum ada permohonan penarikan saldo di sistem.
              </p>
            </div>

            <div v-else class="space-y-3">
              <div 
                v-for="item in withdrawals" 
                :key="item.id"
                class="p-4 bg-slate-50/70 border border-slate-100 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition-colors"
              >
                <div class="space-y-1">
                  <div class="flex items-center gap-2">
                    <span class="font-bold text-slate-800 text-xs">{{ item.user_name }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">@{{ item.user_username }}</span>
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

                  <p class="text-xs text-slate-600 font-medium">
                    {{ item.bank_name }} - <strong class="text-slate-900 font-mono">{{ item.bank_account_number }}</strong> a.n {{ item.bank_account_name }}
                  </p>

                  <p class="text-[10px] text-slate-400">
                    Diajukan pada: {{ item.created_at }}
                    <span v-if="item.admin_notes" class="text-rose-500 font-semibold block mt-0.5">Catatan: {{ item.admin_notes }}</span>
                  </p>
                </div>

                <!-- Right Side: Amount & Admin Actions -->
                <div class="flex flex-col items-end gap-2 shrink-0">
                  <span class="text-sm font-black text-slate-900 font-mono tracking-tight">
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
