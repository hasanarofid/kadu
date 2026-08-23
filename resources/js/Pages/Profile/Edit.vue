<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
  Settings, 
  UserCheck, 
  CreditCard, 
  Plus, 
  Trash2, 
  Check, 
  CheckCircle2, 
  AlertCircle,
  Upload,
  User,
  Building2,
  Crown
} from '@lucide/vue';

const props = defineProps({
  is_admin: Boolean,
  user_profile: Object,
  company_profile: Object,
  status: String,
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

// Form for User Profile & Bank info
const form = useForm({
  company_name: props.company_profile?.name || 'Mitra Syiar Baitullah',
  company_owner: props.company_profile?.owner || 'President Director',
  company_copyright: props.company_profile?.copyright || 'Mitra Syiar Baitullah. Hak Cipta Dilindungi Undang-Undang.',
  name: props.user_profile?.name || '',
  username: props.user_profile?.username || '',
  email: props.user_profile?.email || '',
  phone: props.user_profile?.phone || '',
  bank_name: props.user_profile?.bank_name || 'Bank Mandiri',
  bank_account_number: props.user_profile?.bank_account_number || '',
  bank_account_name: props.user_profile?.bank_account_name || '',
  password: '',
  site_logo: null,
});

// Bank Accounts list state for corporate
const banksList = ref(props.company_profile?.banks || []);

// Bank modal/add form
const showAddBank = ref(false);
const newBank = useForm({
  bank_name: 'Bank Mandiri',
  account_number: '',
  account_name: '',
});

const handleLogoChange = (e) => {
  if (e.target.files.length > 0) {
    form.site_logo = e.target.files[0];
  }
};

const submitProfile = () => {
  form.post(route('profile.update'), {
    preserveScroll: true,
    forceFormData: true,
  });
};

const addBank = () => {
  if (!newBank.account_number || !newBank.account_name) return;
  banksList.value.push({
    bank_name: newBank.bank_name,
    account_number: newBank.account_number,
    account_name: newBank.account_name,
  });
  newBank.reset();
  showAddBank.value = false;
  saveBanks();
};

const removeBank = (index) => {
  banksList.value.splice(index, 1);
  saveBanks();
};

const bankForm = useForm({ banks: [] });
const saveBanks = () => {
  bankForm.banks = banksList.value;
  bankForm.post(route('profile.update-banks'), {
    preserveScroll: true,
  });
};

const bankOptions = [
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
  <Head :title="is_admin ? 'Pengaturan Profil Instansi & Administrator' : 'Pengaturan Profil Mitra Syiar'" />

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

      <!-- MAIN CONTAINER CARD -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <!-- Header -->
        <div class="flex items-start gap-3 border-b border-[#e09d49]/20 pb-5">
          <div class="p-2.5 bg-[#e98318]/15 text-[#e98318] rounded-2xl shrink-0 mt-0.5">
            <Settings class="w-6 h-6" />
          </div>
          <div class="space-y-1">
            <div class="flex items-center gap-2 flex-wrap">
              <h2 class="text-lg md:text-xl font-black text-[#5c2c24] tracking-tight">
                {{ is_admin ? 'Pengaturan Profil Instansi & Administrator' : 'Pengaturan Profil Mitra Syiar' }}
              </h2>
              <span class="px-2.5 py-0.5 text-[9px] font-black bg-[#e98318]/20 text-[#e98318] border border-[#e09d49]/40 rounded-full uppercase tracking-wider">
                Role: {{ user_profile?.role_name }}
              </span>
            </div>
            <p class="text-xs text-[#9d7c64] font-medium">
              {{ is_admin ? 'Kelola identitas platform, bank utama perusahaan, serta kredensial akun login administrator.' : 'Kelola data diri, informasi rekening bank pencairan bonus, serta kata sandi akun login Anda.' }}
            </p>
          </div>
        </div>

        <form @submit.prevent="submitProfile" class="space-y-6">
          
          <!-- SECTION 1 (ADMIN ONLY): LOGOS & COMPANY IDENTITIES -->
          <div v-if="is_admin" class="space-y-6">
            <div class="bg-[#fffaf2] border border-[#e09d49]/40 rounded-2xl p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Company Logo -->
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white border border-[#e09d49]/50 flex items-center justify-center text-[#9d7c64] text-xs font-bold shrink-0 shadow-xs">
                  <img v-if="company_profile?.logo_url" :src="company_profile.logo_url" class="max-h-12 max-w-12 object-contain" />
                  <span v-else>LOGO</span>
                </div>
                <div class="space-y-1.5">
                  <h4 class="text-xs font-extrabold text-[#5c2c24]">Logo Perusahaan</h4>
                  <p class="text-[10px] text-[#9d7c64] font-medium">Tampil di header utama navigasi.</p>
                  <label class="inline-flex items-center px-3 py-1.5 bg-[#e98318] hover:brightness-105 text-white text-[11px] font-bold rounded-xl shadow-xs cursor-pointer transition-colors">
                    <Upload class="w-3.5 h-3.5 mr-1.5" />
                    <span>Pilih File Logo</span>
                    <input type="file" @change="handleLogoChange" accept="image/*" class="hidden" />
                  </label>
                </div>
              </div>

              <!-- Admin Profile Avatar -->
              <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-[#5c2c24] text-white border-2 border-[#e09d49] flex items-center justify-center text-xl font-extrabold shrink-0 shadow-xs">
                  {{ user_profile?.name ? user_profile.name.charAt(0).toUpperCase() : 'A' }}
                </div>
                <div class="space-y-1.5">
                  <h4 class="text-xs font-extrabold text-[#5c2c24]">Avatar Profil Admin</h4>
                  <p class="text-[10px] text-[#9d7c64] font-medium">Administrator Utama Sistem.</p>
                  <span class="px-2.5 py-1 text-[10px] font-black bg-[#5c2c24] text-white rounded-lg">ADMINISTRATOR</span>
                </div>
              </div>
            </div>

            <!-- Company Identities Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NAMA PERUSAHAAN / PLATFORM
                </label>
                <input 
                  v-model="form.company_name"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NAMA PEMILIK / OWNER UTAMA
                </label>
                <input 
                  v-model="form.company_owner"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  TEKS FOOTER HAK CIPTA
                </label>
                <input 
                  v-model="form.company_copyright"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>
            </div>

            <!-- Corporate Bank Accounts -->
            <div class="bg-emerald-50/50 border border-emerald-200/80 rounded-2xl p-5 space-y-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <CreditCard class="w-4 h-4 text-emerald-700" />
                  <h3 class="text-xs font-black text-emerald-900 uppercase tracking-tight">
                    DAFTAR REKENING BANK PERUSAHAAN (ADMIN)
                  </h3>
                </div>

                <button 
                  type="button"
                  @click="showAddBank = !showAddBank"
                  class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
                >
                  <Plus class="w-3.5 h-3.5" />
                  <span>Tambah Rekening</span>
                </button>
              </div>

              <div v-if="showAddBank" class="p-4 bg-white border border-emerald-200 rounded-xl space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <input v-model="newBank.bank_name" placeholder="Nama Bank" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs" />
                  <input v-model="newBank.account_number" placeholder="Nomor Rekening" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs" />
                  <input v-model="newBank.account_name" placeholder="Nama Pemilik" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs" />
                </div>
                <button @click="addBank" type="button" class="px-4 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg cursor-pointer">Simpan Rekening</button>
              </div>

              <div v-if="banksList.length === 0" class="text-center py-4 text-xs text-slate-400 italic">
                Tidak ada rekening bank perusahaan tersimpan.
              </div>

              <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div v-for="(b, idx) in banksList" :key="idx" class="p-3 bg-white border border-emerald-200/80 rounded-xl flex items-center justify-between">
                  <div>
                    <h4 class="text-xs font-extrabold text-slate-900">{{ b.bank_name }}</h4>
                    <p class="text-[11px] text-slate-600 font-mono">{{ b.account_number }} a.n {{ b.account_name }}</p>
                  </div>
                  <button type="button" @click="removeBank(idx)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 2: USER / MITRA PERSONAL & BANK CREDENTIALS -->
          <div class="bg-[#fffaf2] border border-[#e09d49]/40 rounded-2xl p-5 space-y-5">
            <div class="flex items-center gap-2 border-b border-[#e09d49]/30 pb-3">
              <UserCheck class="w-4 h-4 text-[#e98318]" />
              <h3 class="text-xs font-black text-[#5c2c24] uppercase tracking-tight">
                {{ is_admin ? 'DETAIL AKUN ADMINISTRATOR UTAMA' : 'INFORMASI PROFIL AKUN & REKENING MITRA' }}
              </h3>
            </div>

            <!-- Profile Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  USERNAME
                </label>
                <input 
                  v-model="form.username"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NAMA LENGKAP MITRA
                </label>
                <input 
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  ALAMAT EMAIL
                </label>
                <input 
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  NO. WHATSAPP / HP
                </label>
                <input 
                  v-model="form.phone"
                  type="text"
                  placeholder="cth: 081234567890"
                  class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  PASSWORD BARU (KOSONGKAN JIKA TIDAK DIUBAH)
                </label>
                <input 
                  v-model="form.password"
                  type="password"
                  placeholder="Password Baru"
                  class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                />
              </div>

              <div>
                <label class="block text-[10px] font-extrabold text-[#5c2c24] uppercase tracking-wider mb-1">
                  ROLE STATUS AKUN
                </label>
                <div class="px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-black flex items-center gap-1.5">
                  <Crown class="w-3.5 h-3.5 text-[#e98318]" />
                  <span>{{ user_profile?.role_name }}</span>
                </div>
              </div>
            </div>

            <!-- Bank Account Info for Withdrawal -->
            <div class="pt-3 border-t border-[#e09d49]/30 space-y-3">
              <h4 class="text-xs font-black text-[#5c2c24] uppercase tracking-wide flex items-center gap-1.5">
                <CreditCard class="w-4 h-4 text-[#e98318]" />
                <span>INFORMASI REKENING BANK PENCAIRAN BONUS</span>
              </h4>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-[10px] font-bold text-[#9d7c64] uppercase mb-1">BANK TUJUAN</label>
                  <select 
                    v-model="form.bank_name"
                    class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                  >
                    <option v-for="b in bankOptions" :key="b" :value="b">{{ b }}</option>
                  </select>
                </div>

                <div>
                  <label class="block text-[10px] font-bold text-[#9d7c64] uppercase mb-1">NOMOR REKENING</label>
                  <input 
                    v-model="form.bank_account_number"
                    type="text"
                    placeholder="cth: 1234567890"
                    class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                  />
                </div>

                <div>
                  <label class="block text-[10px] font-bold text-[#9d7c64] uppercase mb-1">NAMA PEMILIK REKENING</label>
                  <input 
                    v-model="form.bank_account_name"
                    type="text"
                    placeholder="Nama lengkap sesuai buku tabungan"
                    class="w-full px-3.5 py-2.5 bg-white border border-[#e09d49]/60 rounded-xl text-[#5c2c24] text-xs font-bold focus:outline-none focus:border-[#e98318]"
                  />
                </div>
              </div>
            </div>

          </div>

          <!-- Bottom Submit Button -->
          <div class="flex justify-end pt-2">
            <button 
              type="submit"
              :disabled="form.processing"
              class="px-6 py-3.5 bg-gradient-to-r from-[#e98318] via-[#e09d49] to-[#5c2c24] hover:brightness-105 active:scale-[0.99] text-white text-xs font-black rounded-2xl shadow-md transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50"
            >
              <Check class="w-4 h-4 stroke-[3]" />
              <span>Simpan Profil & Rekening</span>
            </button>
          </div>

        </form>

      </div>

    </div>
  </AdminLayout>
</template>
