<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { 
  LayoutDashboard, 
  Users, 
  Coins, 
  Brain, 
  LogOut, 
  FileText, 
  Menu, 
  X,
  ChevronRight,
  UserCheck,
  ShieldAlert
} from 'lucide-vue-next';

const page = usePage();
const currentUser = computed(() => page.props.auth?.user || { name: 'Administrator', email: 'admin@kadu.com' });

const isMobileSidebarOpen = ref(false);

const navigation = computed(() => [
  { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard, current: route().current('admin.dashboard') },
  { name: 'List User', href: route('admin.users.index'), icon: Users, current: route().current('admin.users.index') },
  { name: 'Paket Token', href: route('admin.packages.index'), icon: Coins, current: route().current('admin.packages.index') },
]);

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white flex flex-col relative">
    <!-- Ambient Lighting -->
    <div class="fixed top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-indigo-600/10 blur-[140px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-violet-600/10 blur-[140px] pointer-events-none"></div>

    <!-- Mobile Top Navigation Header -->
    <header class="lg:hidden bg-slate-900/90 backdrop-blur-xl border-b border-slate-800 h-16 px-4 flex items-center justify-between sticky top-0 z-40">
      <Link href="/" class="flex items-center gap-2.5">
        <div class="p-2 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl text-white shadow">
          <Brain class="w-5 h-5" />
        </div>
        <span class="font-black text-base text-white tracking-tight">KADU Admin</span>
      </Link>
      <button @click="isMobileSidebarOpen = !isMobileSidebarOpen" class="p-2 text-slate-400 hover:text-white">
        <Menu v-if="!isMobileSidebarOpen" class="w-6 h-6" />
        <X v-else class="w-6 h-6" />
      </button>
    </header>

    <!-- Mobile Backdrop -->
    <div v-if="isMobileSidebarOpen" @click="isMobileSidebarOpen = false" class="fixed inset-0 bg-slate-950/80 z-40 lg:hidden"></div>

    <div class="flex-1 flex overflow-hidden">
      <!-- Sidebar Navigation Panel -->
      <aside 
        :class="[
          isMobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
          'fixed lg:static inset-y-0 left-0 z-50 w-64 bg-slate-900/95 border-r border-slate-800/80 flex flex-col justify-between transition-transform duration-300 backdrop-blur-xl'
        ]"
      >
        <div class="space-y-6 p-6">
          <!-- Sidebar Brand logo -->
          <Link href="/" class="flex items-center gap-3">
            <div class="p-2.5 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl text-white shadow-lg shadow-indigo-600/30">
              <Brain class="w-6 h-6" />
            </div>
            <div>
              <span class="font-black text-lg text-white tracking-tight">KADU</span>
              <span class="text-xxs px-2 py-0.5 ml-1.5 rounded-md bg-indigo-500/20 text-indigo-300 font-bold border border-indigo-500/30 uppercase">ADMIN</span>
              <p class="text-xxs text-slate-400 font-semibold">Karsa Edukasi Vokasi</p>
            </div>
          </Link>

          <!-- Admin User Badge -->
          <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow">
                {{ currentUser.name ? currentUser.name.charAt(0).toUpperCase() : 'A' }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ currentUser.name }}</p>
                <p class="text-xxs text-indigo-400 font-semibold truncate">{{ currentUser.email }}</p>
              </div>
            </div>
          </div>

          <!-- Main Admin Navigation Menu -->
          <nav class="space-y-1.5 pt-2">
            <span class="text-xxs font-extrabold text-slate-400 uppercase tracking-widest block px-3 mb-2">MENU ADMIN</span>
            
            <Link 
              v-for="item in navigation" 
              :key="item.name" 
              :href="item.href"
              :class="[
                item.current 
                  ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-bold shadow-lg shadow-indigo-600/20' 
                  : 'text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium',
                'flex items-center gap-3 px-4 py-3 rounded-2xl text-xs transition-all'
              ]"
            >
              <component :is="item.icon" class="w-4 h-4 shrink-0" />
              <span>{{ item.name }}</span>
            </Link>

            <span class="text-xxs font-extrabold text-slate-400 uppercase tracking-widest block px-3 pt-4 mb-2">AKSES USER</span>

            <Link 
              :href="route('rpps.index')" 
              class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs text-slate-400 hover:text-white hover:bg-slate-800/60 font-medium transition-all"
            >
              <FileText class="w-4 h-4 shrink-0 text-indigo-400" />
              <span>Daftar RPP Vokasi</span>
            </Link>
          </nav>
        </div>

        <!-- Sidebar Footer Logout Button -->
        <div class="p-6 border-t border-slate-800/80">
          <button 
            @click="logout" 
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-500/10 hover:bg-rose-600 text-rose-400 hover:text-white rounded-xl text-xs font-bold transition-all border border-rose-500/20"
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
