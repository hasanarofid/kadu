<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { 
  UserPlus, 
  Wand2, 
  HelpCircle, 
  KeyRound, 
  Check, 
  AlertCircle,
  User,
  Mail,
  Users,
  Sparkles,
  Ticket
} from '@lucide/vue';

const props = defineProps({
  vouchers: Array,
  users: Array,
  default_sponsor: String,
});

const form = useForm({
  username: '',
  name: '',
  email: '',
  sponsor_username: props.default_sponsor || 'admin',
  voucher_code: props.vouchers.length > 0 ? props.vouchers[0].code : '',
});

const fillDemoData = () => {
  const randomId = Math.floor(100 + Math.random() * 900);
  form.username = `hendra_${randomId}`;
  form.name = `Hendra Setiawan ${randomId}`;
  form.email = `hendra${randomId}@gmail.com`;
  form.sponsor_username = props.default_sponsor || 'admin';
  if (props.vouchers.length > 0) {
    form.voucher_code = props.vouchers[0].code;
  }
};

const submitForm = () => {
  form.post(route('admin.activation.store'), {
    preserveScroll: true,
  });
};
</script>

<template>
  <Head title="Aktivasi Mitra Baru - Mitra Syiar Baitullah" />

  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Main Card Container -->
      <div class="bg-white border border-[#e09d49]/40 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#e09d49]/20 pb-5">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <UserPlus class="w-5 h-5 text-[#e98318]" />
              <h2 class="text-base font-black text-[#5c2c24] uppercase tracking-wide">Registrasi & Aktivasi Mitra Baru</h2>
            </div>
            <p class="text-xs text-[#9d7c64] font-medium">
              Daftarkan mitra baru ke dalam jaringan Anda menggunakan Kode DP Awal (Rp 500.000).
            </p>
          </div>

          <!-- Auto Fill Demo Data Button -->
          <button 
            type="button"
            @click="fillDemoData"
            class="px-4 py-2 bg-[#fffaf2] hover:bg-[#e98318]/15 border border-[#e09d49] text-[#5c2c24] text-xs font-bold rounded-full transition-all flex items-center gap-1.5 self-start sm:self-auto cursor-pointer shadow-xs"
          >
            <Wand2 class="w-3.5 h-3.5 text-[#e98318]" />
            <span>Isi Data Demo Otomatis</span>
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-6 pt-2">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- 1. Username Mitra Baru -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-extrabold uppercase tracking-wider text-[#5c2c24]">
                USERNAME MITRA BARU
              </label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-[#9d7c64] font-bold text-xs">@</span>
                <input 
                  v-model="form.username"
                  type="text"
                  placeholder="cth: budisantoso"
                  class="w-full bg-[#fffaf2]/50 border border-[#e09d49]/60 rounded-xl pl-8 pr-4 py-2.5 text-xs font-semibold text-[#5c2c24] focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all"
                />
              </div>
              <p class="text-[10px] text-[#9d7c64]">Hanya huruf, angka, dan underscore. Otomatis menjadi lowercase.</p>
              <p v-if="form.errors.username" class="text-xs text-rose-500 font-medium">{{ form.errors.username }}</p>
            </div>

            <!-- 2. Nama Lengkap -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-extrabold uppercase tracking-wider text-[#5c2c24]">
                NAMA LENGKAP
              </label>
              <input 
                v-model="form.name"
                type="text"
                placeholder="cth: Budi Santoso"
                class="w-full bg-[#fffaf2]/50 border border-[#e09d49]/60 rounded-xl px-4 py-2.5 text-xs font-semibold text-[#5c2c24] focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all"
              />
              <p v-if="form.errors.name" class="text-xs text-rose-500 font-medium">{{ form.errors.name }}</p>
            </div>

            <!-- 3. Alamat Email -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-extrabold uppercase tracking-wider text-[#5c2c24]">
                ALAMAT EMAIL
              </label>
              <input 
                v-model="form.email"
                type="email"
                placeholder="cth: budi@gmail.com"
                class="w-full bg-[#fffaf2]/50 border border-[#e09d49]/60 rounded-xl px-4 py-2.5 text-xs font-semibold text-[#5c2c24] focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all"
              />
              <p v-if="form.errors.email" class="text-xs text-rose-500 font-medium">{{ form.errors.email }}</p>
            </div>

            <!-- 4. Username Sponsor Direct -->
            <div class="space-y-1.5">
              <div class="flex items-center gap-1">
                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-[#5c2c24]">
                  USERNAME SPONSOR DIRECT
                </label>
                <HelpCircle class="w-3.5 h-3.5 text-[#9d7c64] cursor-help" />
              </div>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-[#9d7c64] font-bold text-xs">@</span>
                <input 
                  v-model="form.sponsor_username"
                  type="text"
                  placeholder="admin"
                  class="w-full bg-[#fffaf2]/50 border border-[#e09d49]/60 rounded-xl pl-8 pr-4 py-2.5 text-xs font-semibold text-[#5c2c24] focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all"
                />
              </div>
              <p class="text-[10px] text-[#9d7c64]">Sponsor langsung berhak atas Ujroh Direct Sponsor Rp 250.000.</p>
              <p v-if="form.errors.sponsor_username" class="text-xs text-rose-500 font-medium">{{ form.errors.sponsor_username }}</p>
            </div>

          </div>

          <!-- 5. Pilih KODE DP AWAL (Full Width) -->
          <div class="bg-[#fffaf2] border border-[#e09d49]/50 rounded-2xl p-5 space-y-2">
            <label class="block text-[11px] font-black uppercase tracking-wider text-[#5c2c24] flex items-center gap-2">
              <Ticket class="w-4 h-4 text-[#e98318]" />
              <span>PILIH KODE DP AWAL (VOUCHER)</span>
            </label>
            
            <div class="relative">
              <select
                v-model="form.voucher_code"
                class="w-full bg-white border border-[#e09d49]/70 rounded-xl px-4 py-3 text-xs font-bold text-[#5c2c24] focus:outline-none focus:border-[#e98318] focus:ring-2 focus:ring-[#e98318]/20 transition-all appearance-none cursor-pointer shadow-xs"
              >
                <option value="" disabled>-- Pilih Kode DP Awal dari Wallet Anda ({{ vouchers.length }} Tersedia) --</option>
                <option v-for="v in vouchers" :key="v.code" :value="v.code">
                  {{ v.label }}
                </option>
              </select>
              <span class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-[#e98318] text-xs">
                ▼
              </span>
            </div>
            <p v-if="vouchers.length === 0" class="text-xs text-amber-700 font-semibold flex items-center gap-1 pt-1">
              <AlertCircle class="w-3.5 h-3.5 text-amber-600" />
              <span>Stok DP Awal kosong. Silakan beli DP Awal terlebih dahulu melalui menu DP Awal.</span>
            </p>
            <p v-if="form.errors.voucher_code" class="text-xs text-rose-500 font-medium">{{ form.errors.voucher_code }}</p>
          </div>

          <!-- 6. Submit Button -->
          <div class="pt-2">
            <button
              type="submit"
              :disabled="form.processing || vouchers.length === 0"
              class="w-full py-4 bg-gradient-to-r from-[#e98318] via-[#e09d49] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white font-black rounded-2xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-[#e98318]/20 transition-all cursor-pointer disabled:opacity-50"
            >
              <UserPlus class="w-4 h-4 text-white" />
              <span>Daftarkan & Aktifkan Mitra Baru dengan DP Awal</span>
            </button>
          </div>

        </form>

      </div>

    </div>
  </AdminLayout>
</template>
