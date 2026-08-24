<script setup>
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
  Users, 
  Coins, 
  Search, 
  Shield, 
  Plus, 
  CheckCircle2, 
  X, 
  History, 
  FileText, 
  Clock, 
  Eye,
  BookOpen
} from 'lucide-vue-next';

const props = defineProps({
  users: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.search || '');

// Search debounce / handler
const handleSearch = () => {
  router.get(route('admin.users.index'), { search: searchQuery.value }, { preserveState: true, replace: true });
};

// Modal Topup State
const selectedUserForTopup = ref(null);
const topupForm = useForm({
  tokens: 10,
  reason: 'Topup Manual Admin',
});

const openTopupModal = (user) => {
  selectedUserForTopup.value = user;
  topupForm.tokens = 10;
  topupForm.reason = 'Topup Manual Admin';
};

const submitTopup = () => {
  if (!selectedUserForTopup.value) return;
  topupForm.post(route('admin.users.topup', selectedUserForTopup.value.id), {
    onSuccess: () => {
      selectedUserForTopup.value = null;
    }
  });
};

// Modal User History State
const isHistoryModalOpen = ref(false);
const historyUser = ref(null);
const historyLogs = ref([]);
const historyRpps = ref([]);
const historyTab = ref('logs'); // 'logs' or 'rpps'
const isLoadingHistory = ref(false);

const openHistoryModal = async (user) => {
  historyUser.value = user;
  isHistoryModalOpen.value = true;
  isLoadingHistory.value = true;
  historyTab.value = 'logs';

  try {
    const res = await fetch(route('admin.users.history', user.id));
    const data = await res.json();
    historyLogs.value = data.token_logs || [];
    historyRpps.value = data.rpps || [];
  } catch (err) {
    console.error(err);
  } finally {
    isLoadingHistory.value = false;
  }
};

// Role switch handler
const toggleRole = (user) => {
  const newRole = user.roles.some(r => r.name === 'admin') ? 'user' : 'admin';
  if (confirm(`Apakah Anda yakin ingin merubah role ${user.name} menjadi ${newRole.toUpperCase()}?`)) {
    router.post(route('admin.users.role', user.id), { role: newRole });
  }
};
</script>

<template>
  <AdminLayout>
    <Head title="Manajemen User & Token - Admin KADU" />

    <div class="space-y-6 text-left">
      <!-- Header Banner & Search -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 p-6 rounded-3xl shadow-xl">
        <div>
          <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <Users class="w-6 h-6 text-indigo-400" />
            Manajemen User & Kuota Token
          </h1>
          <p class="text-xs text-slate-400 mt-1">Daftar pengguna terdaftar, sisa kuota token, jumlah RPP, dan alokasi top-up manual.</p>
        </div>

        <div class="relative w-full md:w-72">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
          <input 
            v-model="searchQuery" 
            @keyup.enter="handleSearch"
            type="text" 
            placeholder="Cari nama, email, username..." 
            class="w-full h-11 bg-slate-950 border border-slate-800 rounded-2xl pl-10 pr-4 text-xs font-semibold text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-0"
          />
        </div>
      </div>

      <!-- Users Table Card -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-800 text-xxs font-bold text-slate-400 uppercase tracking-wider">
                <th class="py-4 px-4">Pengguna</th>
                <th class="py-4 px-4">Role</th>
                <th class="py-4 px-4">RPP Dibuat</th>
                <th class="py-4 px-4">Sisa Token RPP</th>
                <th class="py-4 px-4 text-center">Aksi Administrator</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-xs">
              <tr 
                v-for="user in users.data" 
                :key="user.id"
                class="hover:bg-slate-850/50 transition-colors"
              >
                <td class="py-4 px-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow shrink-0 overflow-hidden">
                      <img v-if="user.avatar_url || user.avatar" :src="user.avatar_url || user.avatar" alt="Avatar" class="w-full h-full object-cover" />
                      <span v-else>{{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}</span>
                    </div>
                    <div>
                      <p class="font-bold text-white">{{ user.name }}</p>
                      <p class="text-xxs text-slate-400">{{ user.email }}</p>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-4">
                  <span 
                    :class="[
                      'px-2.5 py-1 rounded-full text-xxs font-black uppercase tracking-wider border',
                      user.roles?.some(r => r.name === 'admin') 
                        ? 'bg-amber-500/10 text-amber-300 border-amber-500/30' 
                        : 'bg-indigo-500/10 text-indigo-300 border-indigo-500/30'
                    ]"
                  >
                    {{ user.roles?.some(r => r.name === 'admin') ? 'ADMIN' : 'USER' }}
                  </span>
                </td>

                <td class="py-4 px-4">
                  <span class="px-3 py-1 rounded-full bg-slate-950 border border-slate-800 text-slate-200 text-xs font-bold flex items-center gap-1.5 w-fit">
                    <FileText class="w-3.5 h-3.5 text-indigo-400" />
                    {{ user.rpps_count || 0 }} RPP
                  </span>
                </td>

                <td class="py-4 px-4">
                  <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-bold flex items-center gap-1.5 w-fit">
                    <Coins class="w-3.5 h-3.5 text-amber-400" />
                    {{ user.tokens }} Token
                  </span>
                </td>

                <td class="py-4 px-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button 
                      @click="openHistoryModal(user)" 
                      class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xxs font-bold rounded-xl flex items-center gap-1 border border-slate-700 cursor-pointer"
                      title="Lihat Histori Token & RPP"
                    >
                      <History class="w-3.5 h-3.5 text-indigo-400" />
                      Histori & Data
                    </button>

                    <button 
                      @click="openTopupModal(user)" 
                      class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white text-xxs font-bold rounded-xl flex items-center gap-1 border border-indigo-500/30 cursor-pointer"
                    >
                      <Coins class="w-3.5 h-3.5 text-amber-400" />
                      Top-Up Token
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- MODAL TOPUP MANUAL TOKEN -->
      <div v-if="selectedUserForTopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6">
          <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
              <Coins class="w-5 h-5 text-amber-400" />
              Top-Up Token Manual
            </h3>
            <button @click="selectedUserForTopup = null" class="text-slate-400 hover:text-white">
              <X class="w-5 h-5" />
            </button>
          </div>

          <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center shrink-0 overflow-hidden">
              <img v-if="selectedUserForTopup.avatar_url || selectedUserForTopup.avatar" :src="selectedUserForTopup.avatar_url || selectedUserForTopup.avatar" alt="Avatar" class="w-full h-full object-cover" />
              <span v-else>{{ selectedUserForTopup.name ? selectedUserForTopup.name.charAt(0).toUpperCase() : 'U' }}</span>
            </div>
            <div>
              <p class="text-xs font-bold text-white">{{ selectedUserForTopup.name }}</p>
              <p class="text-xxs text-slate-400">Sisa Kuota: {{ selectedUserForTopup.tokens }} Token</p>
            </div>
          </div>

          <form @submit.prevent="submitTopup" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 mb-1.5">Jumlah Tambahan Token</label>
              <input v-model="topupForm.tokens" type="number" min="1" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-sm font-bold text-white" />
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1.5">Alasan / Catatan Top-Up</label>
              <input v-model="topupForm.reason" type="text" placeholder="Bonus Promosi / Manual Topup" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
              <button type="button" @click="selectedUserForTopup = null" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 rounded-xl">
                Batal
              </button>
              <button type="submit" :disabled="topupForm.processing" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-xl shadow">
                Proses Top-Up Token ✨
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- MODAL DETAIL HISTORI TOKEN & RPP USER -->
      <div v-if="isHistoryModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-3xl w-full shadow-2xl space-y-6 max-h-[90vh] flex flex-col">
          <!-- Modal Header -->
          <div class="flex items-center justify-between border-b border-slate-800 pb-4 shrink-0">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow shrink-0 overflow-hidden">
                <img v-if="historyUser?.avatar_url || historyUser?.avatar" :src="historyUser?.avatar_url || historyUser?.avatar" alt="Avatar" class="w-full h-full object-cover" />
                <span v-else>{{ historyUser?.name ? historyUser.name.charAt(0).toUpperCase() : 'U' }}</span>
              </div>
              <div>
                <h3 class="text-base font-bold text-white">Histori Token & Data RPP: {{ historyUser?.name }}</h3>
                <p class="text-xxs text-slate-400">{{ historyUser?.email }} • Sisa Token: <span class="text-amber-400 font-bold">{{ historyUser?.tokens }} Token</span></p>
              </div>
            </div>

            <button @click="isHistoryModalOpen = false" class="text-slate-400 hover:text-white">
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Modal Tabs Header -->
          <div class="flex items-center gap-3 border-b border-slate-800 pb-3 shrink-0">
            <button 
              @click="historyTab = 'logs'" 
              :class="[
                'px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 cursor-pointer transition-all',
                historyTab === 'logs' ? 'bg-indigo-600 text-white shadow' : 'bg-slate-950 text-slate-400 border border-slate-800'
              ]"
            >
              <History class="w-4 h-4" />
              Histori Penggunaan Token ({{ historyLogs.length }})
            </button>

            <button 
              @click="historyTab = 'rpps'" 
              :class="[
                'px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 cursor-pointer transition-all',
                historyTab === 'rpps' ? 'bg-indigo-600 text-white shadow' : 'bg-slate-950 text-slate-400 border border-slate-800'
              ]"
            >
              <FileText class="w-4 h-4" />
              Data RPP yang Dibuat ({{ historyRpps.length }})
            </button>
          </div>

          <!-- Modal Body Content -->
          <div class="flex-1 overflow-y-auto pr-1">
            <div v-if="isLoadingHistory" class="py-12 text-center text-xs text-slate-400">
              Memuat data histori user...
            </div>

            <!-- TAB 1: LOGS TOKEN -->
            <div v-else-if="historyTab === 'logs'" class="space-y-3">
              <div v-if="historyLogs.length === 0" class="py-12 text-center text-xs text-slate-500">
                Belum ada riwayat penggunaan atau top-up token.
              </div>

              <div v-else class="divide-y divide-slate-800/80 text-xs">
                <div v-for="log in historyLogs" :key="log.id" class="py-3 flex items-center justify-between">
                  <div class="space-y-1">
                    <p class="font-bold text-white">{{ log.description }}</p>
                    <div class="flex items-center gap-2 text-xxs text-slate-400">
                      <Clock class="w-3 h-3 text-indigo-400" />
                      <span>{{ new Date(log.created_at).toLocaleString('id-ID') }}</span>
                    </div>
                  </div>

                  <div class="text-right">
                    <span :class="['font-bold text-xs', log.tokens < 0 || log.type === 'rpp_generate' ? 'text-rose-400' : 'text-emerald-400']">
                      {{ log.type === 'rpp_generate' ? '-' : '+' }}{{ Math.abs(log.tokens) }} Token
                    </span>
                    <span class="block text-xxs text-slate-400">Balance: {{ log.balance_after }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- TAB 2: DATA RPP USER -->
            <div v-else-if="historyTab === 'rpps'" class="space-y-3">
              <div v-if="historyRpps.length === 0" class="py-12 text-center text-xs text-slate-500">
                User ini belum pernah membuat RPP.
              </div>

              <div v-else class="divide-y divide-slate-800/80 text-xs">
                <div v-for="rpp in historyRpps" :key="rpp.id" class="py-3.5 flex items-center justify-between">
                  <div class="space-y-1">
                    <p class="font-bold text-white text-sm">{{ rpp.title }}</p>
                    <div class="flex items-center gap-2 text-xxs text-slate-400">
                      <span class="px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-300 font-bold border border-indigo-500/20">
                        {{ rpp.jurusan_smk }}
                      </span>
                      <span>{{ rpp.kelas_semester }} • {{ rpp.alokasi_waktu }}</span>
                    </div>
                  </div>

                  <Link 
                    :href="route('rpps.show', rpp.id)" 
                    target="_blank"
                    class="px-3 py-1.5 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white rounded-xl text-xxs font-bold flex items-center gap-1 border border-indigo-500/30 shrink-0"
                  >
                    <Eye class="w-3.5 h-3.5" />
                    Lihat RPP
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
