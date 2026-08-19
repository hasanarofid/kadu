<script setup>
import { ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { 
  LayoutDashboard, 
  Settings as SettingsIcon, 
  FileText, 
  Users, 
  Menu, 
  X, 
  LogOut, 
  Layers,
  ChevronDown,
  ChevronLeft,
  ChevronRight,
  Bell,
  Wallet,
  KeyRound,
  GitFork,
  UserPlus,
  ArrowUpRight,
  ShoppingBag,
  Sparkles,
  UserCheck,
  CheckCircle2,
  Activity,
  Crown
} from '@lucide/vue';

const page = usePage();
const user = page.props.auth?.user || { name: 'President Director (Admin)', email: 'admin@xseller.id' };

const isSidebarOpen = ref(false);
const isSidebarCollapsed = ref(false);
const isUserMenuOpen = ref(false);
const isNotificationsOpen = ref(false);

// Toast Notification Stack state (Hidden / empty by default)
const toastStack = ref([]);

const closeToast = (id) => {
  toastStack.value = toastStack.value.filter(t => t.id !== id);
};

const closeAllToasts = () => {
  toastStack.value = [];
};

// Navigation items matching Gambar 2 sidebar
const navigation = [
  { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard, current: route().current('admin.dashboard') },
  { name: 'Pohon Jaringan', href: route('admin.pohon-jaringan'), icon: GitFork, current: route().current('admin.pohon-jaringan') },
  { name: 'Aktivasi Member', href: route('admin.activation.index'), icon: UserPlus, current: route().current('admin.activation.index') },
  { name: 'PIN Wallet', href: route('admin.voucher-wallet.index'), icon: KeyRound, current: route().current('admin.voucher-wallet.index') },
  { name: 'Keuangan', href: route('admin.finance.index'), icon: Wallet, current: route().current('admin.finance.index') },
  { name: 'Penarikan Saldo', href: route('admin.dashboard'), icon: ArrowUpRight, current: false },
  { name: 'Data Jaringan', href: route('admin.dashboard'), icon: Users, current: false },
  { name: 'Aktivitas', href: route('admin.dashboard'), icon: Activity, current: false },
  { name: 'Laporan', href: route('admin.dashboard'), icon: FileText, current: false },
  { name: 'Pengaturan Profil', href: route('profile.edit'), icon: UserCheck, current: route().current('profile.edit') },
  { name: 'Pengaturan Sistem', href: route('admin.settings.index'), icon: SettingsIcon, current: route().current('admin.settings.index') },
];

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-[#f4f6f9] text-slate-800 font-sans antialiased relative overflow-hidden flex flex-col justify-between">
    
    <div>
      <!-- Floating Toast Notification Stack (Upper Right corner matching Gambar 2) -->
      <div v-if="toastStack.length > 0" class="fixed top-4 right-4 z-50 flex flex-col items-end gap-2 max-w-xs">
        <button 
          @click="closeAllToasts" 
          class="px-3 py-1 bg-white/90 border border-slate-200 hover:bg-slate-50 text-slate-700 text-[11px] font-semibold rounded-full shadow-md backdrop-blur flex items-center gap-1.5 transition-all cursor-pointer"
        >
          <CheckCircle2 class="w-3.5 h-3.5 text-emerald-500" />
          <span>Tutup Semua</span>
        </button>

        <div 
          v-for="toast in toastStack" 
          :key="toast.id"
          class="w-full p-3 bg-emerald-50/95 border border-emerald-300 text-emerald-800 rounded-2xl shadow-lg backdrop-blur-md flex items-center justify-between gap-3 text-xs font-bold animate-fade-in transition-all"
        >
          <div class="flex items-center gap-2">
            <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
              <CheckCircle2 class="w-3.5 h-3.5" />
            </div>
            <span>{{ toast.text }}</span>
          </div>
          <button @click="closeToast(toast.id)" class="text-emerald-500 hover:text-emerald-700 p-0.5 cursor-pointer">
            <X class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>

      <!-- Mobile Sidebar Backdrop -->
      <div 
        v-if="isSidebarOpen" 
        @click="isSidebarOpen = false" 
        class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden transition-opacity"
      ></div>

      <!-- Left Sidebar (White Light Sidebar matching Gambar 2) -->
      <aside 
        :class="[
          isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
          isSidebarCollapsed ? 'lg:w-20' : 'lg:w-64',
          'fixed top-0 bottom-0 left-0 z-40 bg-white border-r border-slate-200 transition-all duration-300 ease-in-out lg:fixed flex flex-col justify-between shadow-sm'
        ]"
      >
        <div>
          <!-- Sidebar Brand Header (Hidden in desktop since Top Bar has main brand) -->
          <div class="flex items-center h-16 px-6 border-b border-slate-100 lg:hidden justify-between">
            <div class="flex items-center gap-2">
              <div class="p-2 bg-emerald-500 text-white rounded-lg">
                <Layers class="w-5 h-5" />
              </div>
              <span class="font-bold text-slate-800 text-sm">DUTA SYNERGY</span>
            </div>
            <button @click="isSidebarOpen = false" class="p-2 text-slate-500 hover:text-slate-800">
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Sidebar User Profile Summary Card (Matching Gambar Mockup) -->
          <div v-if="!isSidebarCollapsed" class="p-4 flex flex-col items-center text-center space-y-2 border-b border-slate-100">
            <div class="w-14 h-14 rounded-full bg-[#1e293b] text-white font-extrabold flex items-center justify-center text-xl shadow-lg border-2 border-slate-100">
              {{ user.name ? user.name.charAt(0).toUpperCase() : 'P' }}
            </div>
            <div>
              <h3 class="text-xs font-black text-slate-800 tracking-tight leading-tight">{{ user.name }}</h3>
              <p class="text-[10px] text-slate-400 font-medium">@{{ user.username || 'admin' }}</p>
            </div>
            <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border border-amber-500/40 text-amber-600 bg-amber-50/60 text-[9px] font-extrabold uppercase tracking-wider">
              <Crown class="w-3 h-3 text-amber-500" />
              <span>MEMBER</span>
            </div>

            <!-- Dompet Saya Card Widget matching Mockup -->
            <div class="w-full mt-2 p-3 bg-slate-50 border border-slate-200/80 rounded-2xl text-left space-y-1.5 shadow-sm">
              <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">DOMPET SAYA</span>
              <p class="text-sm font-black text-slate-900 leading-tight">Rp 2.500.000</p>
              <div class="grid grid-cols-2 gap-1 pt-1.5 border-t border-slate-200/60 text-[9px]">
                <div>
                  <span class="text-slate-400 font-medium block">PIN WALLET:</span>
                  <span class="font-bold text-slate-800">2 Pcs</span>
                </div>
                <div>
                  <span class="text-slate-400 font-medium block">TOTAL BONUS:</span>
                  <span class="font-bold text-emerald-600">Rp 400.000</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation Menu (Active Item Dark Pill #0d131d) -->
          <nav class="px-3 py-2 space-y-1 overflow-y-auto max-h-[calc(100vh-280px)]">
            <Link 
              v-for="item in navigation" 
              :key="item.name" 
              :href="item.href"
              :class="[
                item.current 
                  ? 'bg-[#0d131d] text-white font-bold shadow-md shadow-slate-900/20' 
                  : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium',
                isSidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3.5',
                'group flex items-center py-2.5 text-xs rounded-xl transition-all duration-200'
              ]"
              :title="isSidebarCollapsed ? item.name : ''"
            >
              <component 
                :is="item.icon" 
                :class="[
                  item.current ? 'text-white' : 'text-slate-400 group-hover:text-slate-700',
                  isSidebarCollapsed ? 'lg:mr-0' : 'mr-3',
                  'h-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110'
                ]" 
              />
              <span :class="[isSidebarCollapsed ? 'lg:hidden' : 'block', 'whitespace-nowrap']">{{ item.name }}</span>
            </Link>
          </nav>
        </div>

        <!-- Sidebar Collapse Toggle -->
        <div class="p-3 border-t border-slate-100 hidden lg:block text-right">
          <button 
            @click="isSidebarCollapsed = !isSidebarCollapsed"
            class="p-1.5 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-500 transition-colors cursor-pointer"
          >
            <ChevronLeft v-if="!isSidebarCollapsed" class="w-4 h-4" />
            <ChevronRight v-else class="w-4 h-4" />
          </button>
        </div>
      </aside>

      <!-- Main Content Wrapper -->
      <div 
        :class="[
          isSidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64',
          'flex flex-col min-h-screen transition-all duration-300 ease-in-out'
        ]"
      >
        <!-- Top Bar Header (Dark Midnight Bar matching Gambar 2) -->
        <header class="flex items-center justify-between h-16 px-6 md:px-8 bg-[#0d131d] text-white sticky top-0 z-30 shadow-md">
          <div class="flex items-center gap-4">
            <button 
              @click="isSidebarOpen = true" 
              class="p-2 text-slate-300 hover:text-white lg:hidden"
            >
              <Menu class="w-6 h-6" />
            </button>
            
            <!-- Left Header Logo Badge: DUTA SYNERGY v2.4 Binary -->
            <div class="flex items-center gap-3">
              <div class="w-7 h-7 rounded-lg bg-emerald-500 flex items-center justify-center text-white font-extrabold text-sm shadow-md">
                <span>D</span>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h1 class="text-xs font-black tracking-wider text-emerald-400 uppercase">DUTA SYNERGY</h1>
                  <span class="px-1.5 py-0.2 text-[9px] font-bold bg-slate-800 text-slate-300 border border-slate-700 rounded">v2.4 Binary</span>
                </div>
                <p class="text-[9px] text-slate-400 font-medium tracking-tight hidden sm:block">SISTEM MLM 2 KAKI LENGKAP & REAL-TIME</p>
              </div>
            </div>

            <span class="text-slate-700 hidden md:inline">•</span>

            <!-- Brand title: XSELLER (without .id) -->
            <div class="hidden md:flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              <span class="text-xs font-black tracking-wide text-white uppercase">XSELLER</span>
            </div>
          </div>

          <!-- Right Header Controls -->
          <div class="flex items-center gap-4 text-xs font-semibold">
            <!-- Notification Bell -->
            <button 
              @click="isNotificationsOpen = !isNotificationsOpen"
              class="relative p-2 rounded-full bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 transition-colors cursor-pointer"
            >
              <Bell class="w-4 h-4" />
              <span class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full animate-ping"></span>
            </button>

            <!-- User Avatar Circle -->
            <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 text-slate-200 font-bold flex items-center justify-center text-xs">
              {{ user.name ? user.name.charAt(0).toUpperCase() : 'P' }}
            </div>

            <!-- Role Badge Text -->
            <div class="hidden sm:flex items-center gap-2">
              <span class="text-[11px] text-slate-400">MASUK SEBAGAI:</span>
              <span class="font-bold text-white text-xs">{{ user.name }}</span>
              <span class="px-2 py-0.5 text-[9px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-md">Admin</span>
            </div>

            <!-- Admin Switch Button Dropdown Pill -->
            <div class="relative">
              <button 
                @click="isUserMenuOpen = !isUserMenuOpen"
                class="px-3 py-1.5 rounded-full bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
              >
                <Users class="w-3.5 h-3.5 text-amber-400" />
                <span>Admin</span>
                <ChevronDown class="w-3.5 h-3.5 text-slate-400" />
              </button>

              <div v-if="isUserMenuOpen" @click="isUserMenuOpen = false" class="fixed inset-0 z-10"></div>
              <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-48 bg-white text-slate-800 border border-slate-200 rounded-xl shadow-xl py-1 z-20 overflow-hidden">
                <Link :href="route('profile.edit')" class="block px-4 py-2 text-xs font-semibold hover:bg-slate-100">
                  Pengaturan Profil
                </Link>
                <button @click="logout" class="w-full text-left block px-4 py-2 text-xs font-bold text-rose-600 hover:bg-slate-100 border-t border-slate-100">
                  Keluar
                </button>
              </div>
            </div>
          </div>
        </header>

        <!-- Main Dashboard Content Area -->
        <main class="flex-1 p-6 md:p-8">
          <slot />
        </main>

        <!-- Main Footer (Matching Gambar 2 Footer) -->
        <footer class="p-4 text-center text-[11px] text-slate-500 border-t border-slate-200 bg-white">
          <p>© 2026 Duta Synergy Corp. Hak Cipta Dilindungi Undang-Undang. Aplikasi Simulasi MLM Binary 2 Kaki Terintegrasi.</p>
        </footer>
      </div>
    </div>

    <!-- Bottom Dark Banner Disclaimer (Matching Gambar 2 Bottom Bar) -->
    <div class="w-full py-2 bg-[#0d131d] text-slate-400 text-center text-[11px] font-medium border-t border-slate-800">
      <span>This app was developed by another user. It may be inaccurate or unsafe. </span>
      <a href="#" class="text-slate-300 underline hover:text-white">Report legal issue</a>
    </div>

  </div>
</template>ate>
