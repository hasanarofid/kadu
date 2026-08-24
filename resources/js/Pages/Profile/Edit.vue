<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { 
  User, 
  UserCheck, 
  Key, 
  Check, 
  CheckCircle2, 
  AlertCircle,
  Shield,
  Coins,
  Save,
  Upload
} from 'lucide-vue-next';

const props = defineProps({
  is_admin: Boolean,
  user_profile: Object,
  company_profile: Object,
  status: String,
});

const page = usePage();
const currentUser = computed(() => page.props.auth?.user);
const isAdminRole = computed(() => currentUser.value?.roles?.some(r => r.name === 'admin'));

const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

const avatarPreview = ref(null);
const avatarInput = ref(null);

const form = useForm({
  _method: 'PATCH',
  name: props.user_profile?.name || currentUser.value?.name || '',
  username: props.user_profile?.username || currentUser.value?.username || '',
  email: props.user_profile?.email || currentUser.value?.email || '',
  avatar: null,
  password: '',
  password_confirmation: '',
});

const handleAvatarChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.avatar = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
};

const submitProfile = () => {
  form.post(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset('password', 'password_confirmation', 'avatar');
    },
  });
};
</script>

<template>
  <component :is="isAdminRole ? AdminLayout : AppLayout">
    <Head title="Pengaturan Profil - KADU (Karsa Edukasi Vokasi)" />

    <div class="space-y-6 text-left max-w-4xl mx-auto">
      <!-- Flash Alert Notifications -->
      <div v-if="flashSuccess" class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow animate-fade-in">
        <CheckCircle2 class="w-4 h-4 text-emerald-400 shrink-0" />
        <span>{{ flashSuccess }}</span>
      </div>

      <div v-if="flashError" class="p-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow animate-fade-in">
        <AlertCircle class="w-4 h-4 text-rose-400 shrink-0" />
        <span>{{ flashError }}</span>
      </div>

      <!-- Header Banner Card -->
      <div class="p-6 sm:p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl flex items-center justify-between gap-4">
        <div class="space-y-1">
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 rounded-full text-indigo-300 text-xxs font-bold border border-indigo-500/20 uppercase">
            <UserCheck class="w-3.5 h-3.5 text-indigo-400" />
            Pengaturan Akun Pengguna
          </div>
          <h1 class="text-2xl font-black text-white">Profil & Keamanan Akun</h1>
          <p class="text-xs text-slate-400">Kelola informasi foto profil, nama, username, email, dan kata sandi login Anda.</p>
        </div>

        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-xl flex items-center justify-center shadow shrink-0 overflow-hidden">
          <img v-if="avatarPreview || props.user_profile?.avatar_url || currentUser?.avatar_url" :src="avatarPreview || props.user_profile?.avatar_url || currentUser?.avatar_url" alt="Avatar" class="w-full h-full object-cover" />
          <span v-else>{{ currentUser?.name ? currentUser.name.charAt(0).toUpperCase() : 'U' }}</span>
        </div>
      </div>

      <!-- MAIN FORM CARD (Dark Glassmorphism Theme) -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <form @submit.prevent="submitProfile" class="space-y-6">
          <div class="space-y-4">
            <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
              <User class="w-4 h-4 text-indigo-400" />
              Informasi Pengguna
            </h3>

            <!-- Avatar Upload Row -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 p-4 bg-slate-950/80 rounded-2xl border border-slate-800">
              <div class="relative shrink-0">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-black text-2xl flex items-center justify-center shadow overflow-hidden">
                  <img v-if="avatarPreview || props.user_profile?.avatar_url || currentUser?.avatar_url" :src="avatarPreview || props.user_profile?.avatar_url || currentUser?.avatar_url" alt="Avatar" class="w-full h-full object-cover" />
                  <span v-else>{{ currentUser?.name ? currentUser.name.charAt(0).toUpperCase() : 'U' }}</span>
                </div>
              </div>
              <div class="space-y-1.5 flex-1 min-w-0">
                <label class="block font-bold text-slate-200 text-xs">Foto Profil Saya</label>
                <div class="flex flex-wrap items-center gap-3">
                  <input 
                    type="file" 
                    ref="avatarInput" 
                    @change="handleAvatarChange" 
                    accept="image/*" 
                    class="hidden" 
                  />
                  <button 
                    type="button" 
                    @click="avatarInput.click()" 
                    class="px-3.5 py-2 bg-indigo-600/20 hover:bg-indigo-600/30 active:scale-[0.98] text-indigo-300 border border-indigo-500/30 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                  >
                    <Upload class="w-3.5 h-3.5" />
                    <span>Upload Foto Baru</span>
                  </button>
                  <span class="text-[11px] text-slate-400">Format: JPG, PNG, WEBP (Maksimal 3MB)</span>
                </div>
                <p v-if="form.errors.avatar" class="text-xs font-bold text-rose-400 mt-1">{{ form.errors.avatar }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
              <div>
                <label class="block font-bold text-slate-300 mb-1.5">Nama Lengkap</label>
                <input 
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white focus:border-indigo-500 focus:ring-0"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1.5">Username</label>
                <input 
                  v-model="form.username"
                  type="text"
                  required
                  class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white focus:border-indigo-500 focus:ring-0"
                />
              </div>

              <div class="sm:col-span-2">
                <label class="block font-bold text-slate-300 mb-1.5">Alamat Email</label>
                <input 
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white focus:border-indigo-500 focus:ring-0"
                />
              </div>
            </div>
          </div>

          <!-- Password Section -->
          <div class="space-y-4 pt-2">
            <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
              <Key class="w-4 h-4 text-indigo-400" />
              Ubah Password (Kosongkan Jika Tidak Diubah)
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
              <div>
                <label class="block font-bold text-slate-300 mb-1.5">Password Baru</label>
                <input 
                  v-model="form.password"
                  type="password"
                  placeholder="Masukkan Password Baru..."
                  class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white focus:border-indigo-500 focus:ring-0"
                />
                <p v-if="form.errors.password" class="mt-1.5 text-xs font-bold text-rose-400">{{ form.errors.password }}</p>
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1.5">Konfirmasi Password Baru</label>
                <input 
                  v-model="form.password_confirmation"
                  type="password"
                  placeholder="Ulangi Password Baru..."
                  class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white focus:border-indigo-500 focus:ring-0"
                />
                <p v-if="form.errors.password_confirmation" class="mt-1.5 text-xs font-bold text-rose-400">{{ form.errors.password_confirmation }}</p>
              </div>
            </div>
          </div>

          <!-- Bottom Submit Button -->
          <div class="flex justify-end pt-4 border-t border-slate-800">
            <button 
              type="submit"
              :disabled="form.processing"
              class="px-6 py-3 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 text-white text-xs font-extrabold rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-2 cursor-pointer disabled:opacity-50"
            >
              <Save class="w-4 h-4" />
              <span>Simpan Perubahan Profil ✨</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </component>
</template>
