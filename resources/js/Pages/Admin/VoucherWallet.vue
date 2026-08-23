<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
  KeyRound, 
  Wallet, 
  Send, 
  Sparkles, 
  CheckCircle2, 
  AlertCircle, 
  ArrowRightLeft, 
  ShieldCheck,
  Clock,
  UserCheck,
  Ticket
} from '@lucide/vue';

const props = defineProps({
  wallet: Object,
  voucher_price: Number,
  vouchers: Array,
  available_vouchers: Array,
  transfers: Array,
  is_admin: Boolean,
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

// Form Beli DP Awal
const buyForm = useForm({});
const submitBuy = () => {
  buyForm.post(route('admin.voucher-wallet.buy'), {
    preserveScroll: true,
  });
};

// Form Produksi DP Awal Admin
const produceForm = useForm({
  username: '',
});
const submitProduce = () => {
  produceForm.post(route('admin.voucher-wallet.produce'), {
    preserveScroll: true,
    onSuccess: () => produceForm.reset(),
  });
};

// Form Transfer DP Awal
const transferForm = useForm({
  voucher_id: '',
  recipient_username: '',
});
const submitTransfer = () => {
  transferForm.post(route('admin.voucher-wallet.transfer'), {
    preserveScroll: true,
    onSuccess: () => transferForm.reset(),
  });
};

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};
</script>

<template>
  <Head title="DP Awal Aktivasi - Mitra Syiar Baitullah" />

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

      <!-- Main Layout Grid (Left: Actions, Right: List & History) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN: Forms & Actions (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
          
          <!-- 1. BELI DP AWAL AKTIVASI CARD -->
          <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-start gap-3">
              <div class="p-2.5 bg-[#e98318]/15 text-[#e98318] rounded-xl shrink-0">
                <Ticket class="w-5 h-5" />
              </div>
              <div>
                <h3 class="text-sm font-black text-[#5c2c24] uppercase tracking-tight">BELI DP AWAL AKTIVASI</h3>
                <p class="text-xs text-[#9d7c64] mt-1 leading-relaxed font-medium">
                  DP Awal Aktivasi digunakan untuk mengaktifkan pendaftaran mitra baru. Harga DP Awal: <strong class="text-[#5c2c24] font-bold">{{ formatRupiah(voucher_price || 500000) }}</strong> per DP Awal.
                </p>
              </div>
            </div>

            <!-- Saldo Box -->
            <div class="p-4 bg-[#fffaf2] border border-[#e09d49]/40 rounded-2xl space-y-1">
              <span class="text-[10px] font-extrabold text-[#9d7c64] uppercase tracking-wider block">Saldo Wallet Tersedia:</span>
              <p class="text-xl font-black text-[#5c2c24] tracking-tight">{{ formatRupiah(wallet?.saldo || 2500000) }}</p>
            </div>

            <button 
              @click="submitBuy"
              :disabled="buyForm.processing"
              class="w-full py-3 bg-gradient-to-r from-[#e98318] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white text-xs font-black rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
            >
              <Ticket class="w-4 h-4" />
              <span>Beli 1 DP Awal ({{ formatRupiah(voucher_price || 500000) }})</span>
            </button>
          </div>

          <!-- 2. FITUR KHUSUS ADMIN CARD -->
          <div v-if="is_admin" class="bg-[#5c2c24] text-white rounded-3xl p-6 shadow-lg space-y-4 relative overflow-hidden border border-[#e09d49]/40">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <ShieldCheck class="w-4 h-4 text-[#e09d49]" />
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-white">PRODUKSI DP AWAL GRATIS (ADMIN)</h3>
              </div>
              <span class="px-2 py-0.5 text-[9px] font-bold bg-[#e98318]/25 text-[#e09d49] rounded-md border border-[#e09d49]/40">Admin Mode</span>
            </div>

            <p class="text-xs text-slate-200 leading-relaxed font-medium">
              Sebagai Admin, Anda dapat memproduksi kode DP Awal Aktivasi secara gratis dan mendistribusikannya ke mitra mana saja.
            </p>

            <form @submit.prevent="submitProduce" class="space-y-3 pt-1">
              <div>
                <label class="block text-[10px] font-bold text-[#e09d49] uppercase tracking-wider mb-1">
                  KIRIM KE USERNAME (OPSIONAL)
                </label>
                <input 
                  v-model="produceForm.username"
                  type="text"
                  placeholder="cth: budi (kosongkan jika untuk diri sendiri)"
                  class="w-full px-3.5 py-2.5 bg-black/30 border border-[#e09d49]/40 rounded-xl text-white placeholder-slate-300/50 text-xs focus:outline-none focus:border-[#e09d49]"
                />
              </div>

              <button 
                type="submit"
                :disabled="produceForm.processing"
                class="w-full py-3 bg-gradient-to-r from-[#e98318] to-[#e09d49] hover:brightness-105 active:scale-[0.99] text-white text-xs font-black rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
              >
                <Sparkles class="w-4 h-4 text-white" />
                <span>Produksi DP Awal Gratis</span>
              </button>
            </form>
          </div>

          <!-- 3. TRANSFER DP AWAL AKTIVASI CARD -->
          <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-start gap-3">
              <div class="p-2.5 bg-[#e98318]/15 text-[#e98318] rounded-xl shrink-0">
                <ArrowRightLeft class="w-5 h-5" />
              </div>
              <div>
                <h3 class="text-sm font-black text-[#5c2c24] uppercase tracking-tight">TRANSFER DP AWAL AKTIVASI</h3>
                <p class="text-xs text-[#9d7c64] mt-1 leading-relaxed font-medium">
                  Kirim kode DP Awal yang Anda miliki ke mitra downline Anda agar mereka bisa melakukan registrasi mandiri.
                </p>
              </div>
            </div>

            <form @submit.prevent="submitTransfer" class="space-y-4 pt-1">
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  PILIH KODE DP AWAL
                </label>
                <select 
                  v-model="transferForm.voucher_id"
                  required
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                >
                  <option value="" disabled>-- Pilih Kode DP Awal --</option>
                  <option v-for="v in available_vouchers" :key="v.id" :value="v.id">
                    {{ v.code }} (Tersedia)
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  USERNAME PENERIMA
                </label>
                <input 
                  v-model="transferForm.recipient_username"
                  type="text"
                  required
                  placeholder="cth: budisantoso"
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] placeholder-[#9d7c64]/50 text-xs font-semibold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <button 
                type="submit"
                :disabled="transferForm.processing"
                class="w-full py-3 bg-gradient-to-r from-[#e98318] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white text-xs font-black rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
              >
                <Send class="w-4 h-4" />
                <span>Transfer DP Awal</span>
              </button>
            </form>
          </div>

        </div>

        <!-- RIGHT COLUMN: List DP Awal & History (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
          
          <!-- 4. LIST DP AWAL TABLE CARD -->
          <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#e09d49]/20 pb-4">
              <h3 class="text-sm font-black text-[#5c2c24] uppercase tracking-tight">LIST DP AWAL</h3>
              <span class="px-3 py-1 text-[10px] font-extrabold bg-[#e98318]/15 text-[#e98318] rounded-full border border-[#e09d49]/30">
                Total: {{ vouchers.length }} DP Awal
              </span>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead>
                  <tr class="border-b border-[#e09d49]/20 text-[10px] font-extrabold text-[#9d7c64] uppercase tracking-wider bg-[#f8efdf]">
                    <th class="py-3 px-3 rounded-l-xl">NO. INVOICE DP AWAL</th>
                    <th class="py-3 px-3">TANGGAL DIBUAT</th>
                    <th class="py-3 px-3">STATUS</th>
                    <th class="py-3 px-3 rounded-r-xl">KETERANGAN PENGGUNAAN</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                  <tr v-for="item in vouchers" :key="item.id" class="hover:bg-[#fffaf2] transition-colors">
                    <td class="py-3.5 px-3">
                      <span class="font-black text-[#e98318] tracking-wide font-mono text-xs">{{ item.code }}</span>
                    </td>
                    <td class="py-3.5 px-3 text-[#9d7c64] font-medium">{{ item.created_at }}</td>
                    <td class="py-3.5 px-3">
                      <span 
                        :class="[
                          item.status === 'TERSEDIA' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-slate-200 text-slate-600 border-slate-300',
                          'px-2.5 py-0.5 text-[9px] font-extrabold rounded-md border uppercase tracking-wider inline-block'
                        ]"
                      >
                        {{ item.status }}
                      </span>
                    </td>
                    <td class="py-3.5 px-3 text-[11px] text-[#9d7c64] italic">
                      {{ item.keterangan }}
                    </td>
                  </tr>
                  <tr v-if="vouchers.length === 0">
                    <td colspan="4" class="py-8 text-center text-[#9d7c64] italic">
                      Belum ada kode DP Awal di list Anda.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- 5. RIWAYAT DP AWAL CARD -->
          <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#e09d49]/20 pb-4">
              <h3 class="text-sm font-black text-[#5c2c24] uppercase tracking-tight">RIWAYAT DP AWAL</h3>
              <span class="px-3 py-1 text-[10px] font-extrabold bg-[#e98318]/15 text-[#e98318] rounded-full border border-[#e09d49]/30">
                {{ transfers.length }} Transaksi
              </span>
            </div>

            <!-- Transfer List -->
            <div class="space-y-3">
              <div 
                v-for="item in transfers" 
                :key="item.id" 
                class="p-3.5 bg-[#fffaf2] border border-[#e09d49]/30 rounded-2xl flex items-center justify-between gap-4 hover:bg-white transition-colors"
              >
                <div class="flex items-center gap-3">
                  <span 
                    :class="[
                      item.type === 'DIKIRIM' ? 'bg-rose-100 text-rose-600 border-rose-200' : 'bg-emerald-100 text-emerald-600 border-emerald-200',
                      'px-2 py-1 text-[9px] font-extrabold rounded-md border uppercase tracking-wider shrink-0'
                    ]"
                  >
                    {{ item.type }}
                  </span>
                  <div>
                    <h4 class="text-xs font-bold text-[#5c2c24] leading-tight">{{ item.keterangan }}</h4>
                    <p class="text-[10px] text-[#9d7c64] mt-0.5 font-medium">{{ item.created_at }}</p>
                  </div>
                </div>

                <div class="shrink-0 text-right">
                  <span class="font-extrabold text-[#e98318] font-mono text-xs">{{ item.voucher_code }}</span>
                </div>
              </div>

              <div v-if="transfers.length === 0" class="py-8 text-center text-[#9d7c64] text-xs italic">
                Belum ada riwayat transfer DP Awal.
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </AdminLayout>
</template>
