<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { 
  FileText, 
  Plus, 
  Coins, 
  User as UserIcon, 
  Shield, 
  LogOut, 
  Brain, 
  Menu, 
  X, 
  Sparkles,
  AlertTriangle,
  Send,
  CheckCircle2
} from 'lucide-vue-next';

const page = usePage();
const currentUser = computed(() => page.props.auth?.user || { name: 'Guru Vokasi', email: 'user@kadu.com' });
const userTokens = computed(() => currentUser.value?.tokens ?? 0);

const isMobileSidebarOpen = ref(false);
const isResendingEmail = ref(false);
const verificationMessage = ref('');

const navigation = computed(() => {
  const items = [
    { name: 'Daftar RPP Vokasi', href: route('rpps.index'), icon: FileText, current: route().current('rpps.index') },
    { name: 'Buat RPP Baru (AI)', href: route('rpps.create'), icon: Sparkles, current: route().current('rpps.create') },
    { name: 'Beli Token & Histori', href: route('tokens.index'), icon: Coins, current: route().current('tokens.index') },
    { name: 'Pengaturan Profil', href: route('profile.edit'), icon: UserIcon, current: route().current('profile.edit') },
  ];

  if (currentUser.value?.roles?.some(r => r.name === 'admin')) {
    items.push({ name: 'Panel Admin CMS', href: route('admin.dashboard'), icon: Shield, current: route().current('admin.dashboard'), special: true });
  }

  return items;
});

const resendVerificationEmail = () => {
  isResendingEmail.value = true;
  verificationMessage.value = '';
  router.post(route('verification.send'), {}, {
    onFinish: () => {
      isResendingEmail.value = false;
      verificationMessage.value = 'Link verifikasi baru telah dikirim ke email Anda! Cek folder Inbox/Spam.';
    }
  });
};

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white flex flex-col relative">
    <!-- Ambient Lighting -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-indigo-600/10 blur-[140px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-violet-600/10 blur-[140px] pointer-events-none"></div>

    <!-- ⚠️ UNVERIFIED EMAIL NOTIFICATION BANNER (STICKY TOP) -->
    <div v-if="currentUser && !currentUser.email_verified_at" class="bg-gradient-to-r from-amber-950/90 via-amber-900/90 to-amber-950/90 border-b border-amber-500/40 px-4 py-2.5 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-amber-200 z-50 shadow-lg backdrop-blur-md">
      <div class="flex items-center gap-2.5 min-w-0">
        <span class="inline-flex p-1.5 bg-amber-500/20 rounded-lg text-amber-400 shrink-0">
          <AlertTriangle class="w-4 h-4" />
        </span>
        <div class="truncate">
          <span class="font-extrabold text-amber-300">Akun Belum Diverifikasi:</span>
          <span class="ml-1 text-amber-200/90">Email <code class="text-amber-100 bg-slate-950/80 px-1.5 py-0.5 rounded font-mono border border-amber-500/30">{{ currentUser.email }}</code> belum diaktivasi.</span>
          <span v-if="$page.props.flash?.error" class="ml-2 text-rose-300 font-bold block sm:inline">⚠️ {{ $page.props.flash.error }}</span>
          <span v-else-if="$page.props.flash?.success || $page.props.status === 'verification-link-sent'" class="ml-2 text-emerald-300 font-bold block sm:inline">✓ Link verifikasi telah dikirim ke email! Cek Inbox/Spam.</span>
        </div>
      </div>
      <button 
        @click="resendVerificationEmail" 
        :disabled="isResendingEmail"
        class="shrink-0 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-400 active:scale-[0.98] text-slate-950 rounded-xl font-extrabold text-xs shadow-md shadow-amber-500/20 transition-all disabled:opacity-50 cursor-pointer flex items-center gap-1.5"
      >
        <Send class="w-3.5 h-3.5" />
        <span>{{ isResendingEmail ? 'Mengirim Email...' : 'Kirim Ulang Aktivasi' }}</span>
      </button>
    </div>

    <!-- Mobile Navigation Header -->
    <header class="lg:hidden bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 h-16 px-4 flex items-center justify-between sticky top-0 z-40">
      <Link href="/" class="flex items-center gap-2.5">
        <div class="p-2 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl text-white shadow">
          <Brain class="w-5 h-5" />
        </div>
        <span class="font-black text-base text-white tracking-tight">KADU</span>
      </Link>
      
      <div class="flex items-center gap-3">
        <div class="flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/30 rounded-full text-amber-300 text-xxs font-extrabold">
          <Coins class="w-3.5 h-3.5 text-amber-400" />
          <span>{{ userTokens }} Token</span>
        </div>
        <button @click="isMobileSidebarOpen = !isMobileSidebarOpen" class="p-2 text-slate-400 hover:text-white">
          <Menu v-if="!isMobileSidebarOpen" class="w-6 h-6" />
          <X v-else class="w-6 h-6" />
        </button>
      </div>
    </header>

    <!-- Mobile Backdrop -->
    <div v-if="isMobileSidebarOpen" @click="isMobileSidebarOpen = false" class="fixed inset-0 bg-slate-950/80 z-40 lg:hidden"></div>

    <div class="flex-1 flex overflow-hidden">
      <!-- Left Sidebar Navigation -->
      <aside 
        :class="[
          isMobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
          'fixed lg:static inset-y-0 left-0 z-50 w-64 bg-slate-900/95 border-r border-slate-800/80 flex flex-col justify-between transition-transform duration-300 backdrop-blur-xl shrink-0'
        ]"
      >
        <div class="space-y-5 p-5">
          <!-- Logo & Brand Header -->
          <Link href="/" class="flex items-center gap-3">
            <div class="p-2.5 bg-gradient-to-tr from-indigo-600 via-violet-600 to-purple-600 rounded-2xl text-white shadow-lg shadow-indigo-600/30">
              <Brain class="w-6 h-6" />
            </div>
            <div>
              <span class="font-black text-lg text-white tracking-tight">
                KADU
              </span>
              <p class="text-xxs text-slate-400 font-semibold">Karsa Edukasi</p>
            </div>
          </Link>

          <!-- User Card with Professional Token Widget -->
          <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800/80 space-y-3 shadow-inner">
            <div class="flex items-center gap-3">
              <div class="relative shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-extrabold flex items-center justify-center text-base shadow">
                  {{ currentUser.name ? currentUser.name.charAt(0).toUpperCase() : 'G' }}
                </div>
                <div 
                  :title="currentUser.email_verified_at ? 'Email Terverifikasi' : 'Email Belum Diverifikasi'"
                  :class="[
                    'absolute -bottom-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center text-[10px] text-white font-bold border-2 border-slate-950 shadow',
                    currentUser.email_verified_at ? 'bg-emerald-500' : 'bg-amber-500'
                  ]"
                >
                  <CheckCircle2 v-if="currentUser.email_verified_at" class="w-3 h-3" />
                  <AlertTriangle v-else class="w-3 h-3" />
                </div>
              </div>

              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ currentUser.name }}</p>
                <p class="text-xxs text-slate-400 font-semibold truncate">{{ currentUser.email }}</p>
              </div>
            </div>

            <!-- Sleek Token Counter Box -->
            <div class="p-2.5 bg-slate-900/90 border border-amber-500/30 rounded-xl flex items-center justify-between gap-2">
              <div class="min-w-0 flex-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">KUOTA TOKEN RPP</span>
                <div class="flex items-center gap-1.5 mt-0.5">
                  <Coins class="w-3.5 h-3.5 text-amber-400 shrink-0" />
                  <span class="text-xs font-black text-amber-300 tracking-tight truncate">{{ userTokens }} Token</span>
                </div>
              </div>
              
              <Link 
                :href="route('tokens.index')" 
                class="px-2.5 py-1.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 active:scale-[0.97] text-slate-950 rounded-lg text-xs font-black shadow-md shadow-amber-500/20 transition-all shrink-0 flex items-center gap-1 cursor-pointer"
              >
                <Plus class="w-3.5 h-3.5 stroke-[3]" />
                <span>Beli</span>
              </Link>
            </div>
          </div>

          <!-- Navigation Links -->
          <nav class="space-y-1.5 pt-1">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest block px-3 mb-2">MENU DASHBOARD</span>

            <Link 
              v-for="item in navigation" 
              :key="item.name" 
              :href="item.href"
              :class="[
                item.current 
                  ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold shadow-lg shadow-indigo-600/20' 
                  : item.special 
                    ? 'bg-amber-500/10 text-amber-300 border border-amber-500/20 font-bold hover:bg-amber-500/20' 
                    : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium',
                'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs transition-all'
              ]"
            >
              <component :is="item.icon" class="w-4 h-4 shrink-0" />
              <span class="truncate">{{ item.name }}</span>
            </Link>
          </nav>
        </div>

        <!-- Sidebar Footer Logout Button -->
        <div class="p-5 border-t border-slate-800/80">
          <button 
            @click="logout" 
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-500/10 hover:bg-rose-600 text-rose-400 hover:text-white rounded-xl text-xs font-bold transition-all border border-rose-500/20 cursor-pointer"
          >
            <LogOut class="w-4 h-4" />
            <span>Keluar Sistem</span>
          </button>
        </div>
      </aside>

      <!-- Main Body Content Area -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-8 space-y-6">
        <slot />
      </main>
    </div>
  </div>
</template>
