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
  Crown,
  Download,
  ClipboardList,
} from '@lucide/vue';

const page = usePage();
const user = page.props.auth?.user || { name: 'President Director (Admin)', email: 'admin@xseller.id' };

const isSidebarOpen = ref(false);
const isSidebarCollapsed = ref(false);
const isUserMenuOpen = ref(false);
const isNotificationsOpen = ref(false);

const notifications = ref([
  { id: 1, title: 'Bonus Sponsor Masuk', desc: 'Rp 250.000 dari pendaftaran member baru', time: '10 min lalu', read: false },
  { id: 2, title: 'Pembayaran Voucher Terverifikasi', desc: 'Order pembelian voucher telah disetujui admin', time: '1 jam lalu', read: false },
  { id: 3, title: 'Komisi Team Generasi 2', desc: 'Rp 5.000 dikreditkan ke e-Wallet Syiar Anda', time: '3 jam lalu', read: true },
]);
const unreadNotificationsCount = computed(() => notifications.value.filter(n => !n.read).length);
const markAllRead = () => { notifications.value.forEach(n => n.read = true); };

// Toast Notification Stack state (Hidden / empty by default)
const toastStack = ref([]);

const closeToast = (id) => {
  toastStack.value = toastStack.value.filter(t => t.id !== id);
};

const closeAllToasts = () => {
  toastStack.value = [];
};

// Navigation items matching spesifikasi menu terbaru
const navigation = computed(() => {
  const items = [
    { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard, current: route().current('admin.dashboard') },
    { name: 'Team', href: route('admin.team.index'), icon: Users, current: route().current('admin.team.index') },
    { name: 'Aktivasi Mitra', href: route('admin.activation.index'), icon: UserPlus, current: route().current('admin.activation.index') },
    { name: 'DP Awal', href: route('admin.voucher-wallet.index'), icon: KeyRound, current: route().current('admin.voucher-wallet.index') },
    { name: 'Keuangan', href: route('admin.finance.index'), icon: Wallet, current: route().current('admin.finance.index') },
    { name: 'Penarikan Bonus', href: route('admin.withdrawals.index'), icon: ArrowUpRight, current: route().current('admin.withdrawals.index') },
    { name: 'Data Team', href: route('admin.network-data.index'), icon: Users, current: route().current('admin.network-data.index') },
    { name: 'Aktivitas', href: route('admin.activities.index'), icon: Activity, current: route().current('admin.activities.index') },
    { name: 'Laporan', href: route('admin.reports.index'), icon: FileText, current: route().current('admin.reports.index') },
    { name: 'Pengaturan Profile', href: route('profile.edit'), icon: UserCheck, current: route().current('profile.edit') },
  ];

  if (user.value.roles?.[0]?.name === 'admin') {
    items.push({ name: 'Order Pembayaran', href: route('admin.payment-orders.index'), icon: ClipboardList, current: route().current('admin.payment-orders.index') });
    items.push({ name: 'Pengaturan Sistem', href: route('admin.settings.index'), icon: SettingsIcon, current: route().current('admin.settings.index'), special: 'amber' });
  }

  return items;
});

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

          <!-- Sidebar User Profile Summary Card (Matching Gambar Mockup Budi Santoso) -->
          <div v-if="!isSidebarCollapsed" class="p-4 flex flex-col items-center text-center space-y-2 border-b border-slate-100">
            <div class="w-14 h-14 rounded-full bg-[#1e293b] text-white font-extrabold flex items-center justify-center text-xl shadow-lg border-2 border-slate-100">
              {{ user.name ? user.name.charAt(0).toUpperCase() : 'B' }}
            </div>
            <div>
              <h3 class="text-xs font-black text-slate-800 tracking-tight leading-tight">{{ user.name }}</h3>
              <p class="text-[10px] text-slate-400 font-medium">@{{ user.username || 'budi' }}</p>
            </div>
            <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full border border-amber-500/40 text-amber-600 bg-amber-50/60 text-[9px] font-extrabold uppercase tracking-wider">
              <Crown class="w-3 h-3 text-amber-500" />
              <span>MITRA</span>
            </div>

            <!-- Dompet Saya Card Widget matching Mockup -->
            <div class="w-full mt-2 p-3 bg-slate-50 border border-slate-200/80 rounded-2xl text-left space-y-1.5 shadow-sm">
              <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">DOMPET SAYA</span>
              <p class="text-sm font-black text-slate-900 leading-tight">Rp 2.500.000</p>
              <div class="grid grid-cols-2 gap-1 pt-1.5 border-t border-slate-200/60 text-[9px]">
                <div>
                  <span class="text-slate-400 font-medium block">DP AWAL:</span>
                  <span class="font-bold text-slate-800">2x</span>
                </div>
                <div>
                  <span class="text-slate-400 font-medium block">TOTAL BONUS:</span>
                  <span class="font-bold text-emerald-600">Rp 400.000</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Navigation Menu (Active Item Dark Pill #0d131d) -->
          <nav class="px-3 py-2 space-y-1.5 overflow-y-auto max-h-[calc(100vh-280px)]">
            <template v-for="item in navigation" :key="item.name">
              <a 
                v-if="item.external"
                :href="item.href"
                :class="[
                  item.special === 'blue' ? 'bg-blue-50/80 border border-blue-300 text-blue-700 font-bold hover:bg-blue-100' : 'text-slate-600 hover:bg-slate-100 font-medium',
                  isSidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3.5',
                  'group flex items-center py-2.5 text-xs rounded-2xl transition-all duration-200 shadow-xs'
                ]"
                :title="isSidebarCollapsed ? item.name : ''"
              >
                <component 
                  :is="item.icon" 
                  :class="[
                    item.special === 'blue' ? 'text-blue-600' : 'text-slate-400',
                    isSidebarCollapsed ? 'lg:mr-0' : 'mr-3',
                    'w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110'
                  ]" 
                />
                <span :class="[isSidebarCollapsed ? 'lg:hidden' : 'block', 'whitespace-nowrap']">{{ item.name }}</span>
              </a>

              <Link 
                v-else
                :href="item.href"
                :class="[
                  item.current 
                    ? (item.special === 'amber' ? 'bg-amber-500 text-white font-black shadow-sm' : 'bg-[#0d131d] text-white font-bold shadow-md shadow-slate-900/20')
                    : (item.special === 'amber' ? 'bg-amber-50/60 border border-amber-300/80 text-amber-900 font-bold hover:bg-amber-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 font-medium'),
                  isSidebarCollapsed ? 'lg:justify-center lg:px-0' : 'px-3.5',
                  'group flex items-center py-2.5 text-xs rounded-2xl transition-all duration-200'
                ]"
                :title="isSidebarCollapsed ? item.name : ''"
              >
                <component 
                  :is="item.icon" 
                  :class="[
                    item.current ? 'text-white' : (item.special === 'amber' ? 'text-amber-600' : 'text-slate-400 group-hover:text-slate-700'),
                    isSidebarCollapsed ? 'lg:mr-0' : 'mr-3',
                    'w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110'
                  ]" 
                />
                <span :class="[isSidebarCollapsed ? 'lg:hidden' : 'block', 'whitespace-nowrap']">{{ item.name }}</span>
              </Link>
            </template>
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
        <!-- Top Bar Header (Deep Navy Bar matching Palette #2e395d) -->
        <header class="flex items-center justify-between min-h-16 py-2 px-4 md:px-8 bg-[#2e395d] text-white sticky top-0 z-30 shadow-md">
          <!-- Sisi Kiri (Anak Panah 1: Renggangkan & Kebaca) -->
          <div class="flex items-center gap-4 lg:gap-8">
            <button 
              @click="isSidebarOpen = true" 
              class="p-2 text-slate-300 hover:text-white lg:hidden rounded-lg hover:bg-white/10 transition-colors"
              title="Buka Menu"
            >
              <Menu class="w-6 h-6" />
            </button>
            
            <!-- Branding Logo & Titles -->
            <div class="flex items-center gap-3.5 sm:gap-4">
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-[#e98318] to-[#e09d49] flex items-center justify-center text-white font-black text-base shadow-md border border-[#e09d49]/50 shrink-0">
                <span>M</span>
              </div>
              <div class="space-y-0.5">
                <div class="flex items-center gap-2.5 flex-wrap">
                  <h1 class="text-xs sm:text-sm font-black tracking-wider text-[#e09d49] uppercase leading-tight">MITRA SYIAR BAITULLAH</h1>
                  <span class="px-2.5 py-0.5 text-[9.5px] font-extrabold bg-[#e98318]/25 text-[#f5c68b] border border-[#e09d49]/40 rounded-md tracking-wide shrink-0">Syiar Portal</span>
                </div>
                <p class="text-[10px] sm:text-[11px] text-slate-300 font-medium tracking-normal hidden sm:block">SISTEM KEMITRAAN & TRAVEL UMROH/HAJI TERPERCAYA</p>
              </div>
            </div>

            <span class="text-[#e09d49]/40 hidden lg:inline text-lg font-light">•</span>

            <!-- Slogan Sisi Kiri -->
            <div class="hidden lg:flex items-center gap-2.5 px-3.5 py-1 bg-white/10 border border-[#e09d49]/30 rounded-full text-xs font-serif italic text-slate-100 backdrop-blur-xs shadow-xs">
              <span class="w-2 h-2 rounded-full bg-[#e98318] animate-pulse shadow-[0_0_8px_#e98318]"></span>
              <span>Bersama Menjadi Tamu Allah</span>
            </div>
          </div>

          <!-- Sisi Kanan (Anak Panah 2: Fungsi Notifikasi & Dropdown Menu) -->
          <div class="flex items-center gap-3 sm:gap-4 text-xs font-semibold">
            
            <!-- Notification Bell Dropdown -->
            <div class="relative">
              <button 
                @click="isNotificationsOpen = !isNotificationsOpen"
                class="relative p-2 rounded-full bg-[#e98318]/20 hover:bg-[#e98318]/35 text-[#e09d49] transition-all cursor-pointer border border-[#e09d49]/30"
                title="Notifikasi"
              >
                <Bell class="w-4 h-4" />
                <span v-if="unreadNotificationsCount > 0" class="absolute -top-1 -right-1 min-w-4 h-4 px-1 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border border-[#2e395d] animate-pulse">
                  {{ unreadNotificationsCount }}
                </span>
              </button>

              <!-- Notifications Dropdown Popover -->
              <div v-if="isNotificationsOpen" @click="isNotificationsOpen = false" class="fixed inset-0 z-20"></div>
              <div 
                v-if="isNotificationsOpen" 
                class="absolute right-0 mt-2 w-80 sm:w-96 bg-white text-[#5c2c24] border border-[#e09d49]/40 rounded-2xl shadow-2xl z-30 overflow-hidden animate-in fade-in slide-in-from-top-2"
              >
                <div class="flex items-center justify-between px-4 py-3 bg-[#5c2c24] text-white border-b border-[#e09d49]/30">
                  <div class="flex items-center gap-2">
                    <Bell class="w-4 h-4 text-[#e09d49]" />
                    <span class="font-bold text-xs">Notifikasi Sistem</span>
                    <span v-if="unreadNotificationsCount > 0" class="px-1.5 py-0.5 text-[9px] bg-[#e98318] rounded-full font-black text-white">{{ unreadNotificationsCount }} Baru</span>
                  </div>
                  <button @click="markAllRead" class="text-[10px] text-[#e09d49] hover:underline font-semibold">Tandai Dibaca</button>
                </div>

                <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                  <div 
                    v-for="item in notifications" 
                    :key="item.id"
                    :class="['p-3.5 hover:bg-[#fffaf2] transition-colors cursor-pointer flex gap-3 items-start', !item.read ? 'bg-[#fffaf2]/80' : '']"
                  >
                    <div class="w-2 h-2 rounded-full bg-[#e98318] mt-1.5 shrink-0" v-if="!item.read"></div>
                    <div class="flex-1 space-y-0.5">
                      <div class="flex justify-between items-center">
                        <span class="font-bold text-xs text-[#5c2c24]">{{ item.title }}</span>
                        <span class="text-[9px] text-[#9d7c64] font-medium">{{ item.time }}</span>
                      </div>
                      <p class="text-[11px] text-[#9d7c64] leading-relaxed">{{ item.desc }}</p>
                    </div>
                  </div>
                </div>

                <div class="p-2.5 bg-gray-50 border-t border-gray-100 text-center">
                  <Link :href="route('admin.activities.index')" class="text-xs text-[#e98318] font-bold hover:underline">
                    Lihat Semua Riwayat Aktivitas →
                  </Link>
                </div>
              </div>
            </div>

            <!-- User Avatar & AKUN info -->
            <div class="hidden sm:flex items-center gap-2.5 bg-white/5 border border-white/10 px-3 py-1 rounded-full">
              <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-[#e98318] to-[#5c2c24] border border-[#e09d49] text-white font-bold flex items-center justify-center text-xs shadow-xs">
                {{ user.name ? user.name.charAt(0).toUpperCase() : 'M' }}
              </div>
              <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-300 font-semibold">AKUN:</span>
                <span class="font-bold text-white text-xs max-w-[120px] truncate">{{ user.name }}</span>
                <span class="px-2 py-0.5 text-[9px] font-bold bg-[#e98318]/25 text-[#e09d49] border border-[#e09d49]/30 rounded-md">
                  {{ user.roles?.[0]?.name === 'admin' ? 'Administrator' : 'Mitra Syiar' }}
                </span>
              </div>
            </div>

            <!-- Menu Dropdown Pill Button -->
            <div class="relative">
              <button 
                @click="isUserMenuOpen = !isUserMenuOpen"
                class="px-3.5 py-1.5 rounded-full bg-[#5c2c24] hover:bg-[#6e352b] border border-[#e09d49]/50 text-white text-xs font-bold flex items-center gap-2 transition-all shadow-md cursor-pointer"
              >
                <Users class="w-3.5 h-3.5 text-[#e09d49]" />
                <span>Menu</span>
                <ChevronDown :class="['w-3.5 h-3.5 text-slate-300 transition-transform', isUserMenuOpen ? 'rotate-180' : '']" />
              </button>

              <div v-if="isUserMenuOpen" @click="isUserMenuOpen = false" class="fixed inset-0 z-20"></div>
              <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-52 bg-white text-[#5c2c24] border border-[#e09d49]/30 rounded-2xl shadow-2xl py-1.5 z-30 overflow-hidden animate-in fade-in slide-in-from-top-2">
                <div class="px-4 py-2 bg-[#fffaf2] border-b border-[#e09d49]/20">
                  <p class="font-bold text-xs text-[#5c2c24] truncate">{{ user.name }}</p>
                  <p class="text-[10px] text-[#9d7c64] truncate">@{{ user.username || 'mitra' }}</p>
                </div>
                <Link :href="route('profile.edit')" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold hover:bg-[#fffaf2] transition-colors">
                  <UserCheck class="w-3.5 h-3.5 text-[#e98318]" />
                  <span>Pengaturan Profil</span>
                </Link>
                <Link :href="route('admin.voucher-wallet.index')" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold hover:bg-[#fffaf2] transition-colors">
                  <KeyRound class="w-3.5 h-3.5 text-[#e98318]" />
                  <span>Voucher / PIN Wallet</span>
                </Link>
                <Link :href="route('admin.finance.index')" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold hover:bg-[#fffaf2] transition-colors">
                  <Wallet class="w-3.5 h-3.5 text-[#e98318]" />
                  <span>Keuangan & Saldo</span>
                </Link>
                <button @click="logout" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 border-t border-gray-100 transition-colors cursor-pointer">
                  <LogOut class="w-3.5 h-3.5 text-rose-500" />
                  <span>Keluar</span>
                </button>
              </div>
            </div>

          </div>
        </header>

        <!-- Main Dashboard Content Area -->
        <main class="flex-1 p-4 md:p-8 bg-[#f8efdf]/50">
          <slot />
        </main>

        <!-- Main Footer -->
        <footer class="p-4 text-center text-[11px] text-[#9d7c64] border-t border-[#e09d49]/30 bg-[#fffaf2]">
          <p>© 2026 Mitra Syiar Baitullah. Hak Cipta Dilindungi Undang-Undang. Bersama Menjadi Tamu Allah.</p>
        </footer>
      </div>
    </div>

    <!-- Bottom Banner Disclaimer -->
    <div class="w-full py-2 bg-[#2e395d] text-slate-300 text-center text-[11px] font-medium border-t border-[#e09d49]/20">
      <span>Mitra Syiar Baitullah — Portal Kemitraan & Travel Umroh Terpercaya</span>
    </div>

  </div>
</template>ate>
