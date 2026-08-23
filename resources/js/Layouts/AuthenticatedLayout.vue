<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Bell, Users, ChevronDown, UserCheck, KeyRound, Wallet, LogOut } from '@lucide/vue';

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'Member' });

const isUserMenuOpen = ref(false);
const isNotificationsOpen = ref(false);

const notifications = ref([
  { id: 1, title: 'Bonus Sponsor Masuk', desc: 'Rp 250.000 dari pendaftaran member baru', time: '10 min lalu', read: false },
  { id: 2, title: 'Pembayaran Voucher Terverifikasi', desc: 'Order pembelian voucher telah disetujui admin', time: '1 jam lalu', read: false },
  { id: 3, title: 'Komisi Team Generasi 2', desc: 'Rp 5.000 dikreditkan ke e-Wallet Syiar Anda', time: '3 jam lalu', read: true },
]);
const unreadNotificationsCount = computed(() => notifications.value.filter(n => !n.read).length);
const markAllRead = () => { notifications.value.forEach(n => n.read = true); };

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-[#f8efdf]/60 text-[#5c2c24] font-sans antialiased flex flex-col justify-between">
        <div>
            <!-- Header Navbar -->
            <header class="flex items-center justify-between min-h-16 py-2 px-4 md:px-8 bg-[#2e395d] text-white sticky top-0 z-30 shadow-md border-b border-[#e09d49]/40">
                <!-- Sisi Kiri (Anak Panah 1: Renggangkan & Kebaca) -->
                <div class="flex items-center gap-4 lg:gap-8">
                    <!-- Branding Logo & Titles -->
                    <Link :href="route('dashboard')" class="flex items-center gap-3.5 sm:gap-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-[#e98318] to-[#e09d49] flex items-center justify-center text-white font-black text-base shadow-md border border-[#e09d49]/50 shrink-0">
                            <span>M</span>
                        </div>
                        <div class="space-y-0.5 text-left">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-xs sm:text-sm font-black tracking-wider text-[#e09d49] uppercase leading-tight">MITRA SYIAR BAITULLAH</h1>
                                <span class="px-2.5 py-0.5 text-[9.5px] font-extrabold bg-[#e98318]/25 text-[#f5c68b] border border-[#e09d49]/40 rounded-md tracking-wide shrink-0">Syiar Portal</span>
                            </div>
                            <p class="text-[10px] sm:text-[11px] text-slate-300 font-medium tracking-normal hidden sm:block">SISTEM KEMITRAAN & TRAVEL UMROH/HAJI TERPERCAYA</p>
                        </div>
                    </Link>

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

            <!-- Page Heading -->
            <header class="bg-white/80 backdrop-blur-md shadow-xs border-b border-[#e09d49]/30" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <slot />
            </main>
        </div>

        <footer class="p-4 text-center text-[11px] text-[#9d7c64] border-t border-[#e09d49]/30 bg-[#fffaf2]">
            <p>© 2026 Mitra Syiar Baitullah. Hak Cipta Dilindungi Undang-Undang. Bersama Menjadi Tamu Allah.</p>
        </footer>
    </div>
</template>
