<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
  Users, 
  Search, 
  Coins, 
  Shield, 
  User as UserIcon, 
  PlusCircle, 
  X,
  CheckCircle2
} from 'lucide-vue-next';

const props = defineProps({
  users: Object,
  filters: Object,
});

const searchQuery = ref(props.filters.search || '');

const handleSearch = () => {
  router.get(route('admin.users.index'), { search: searchQuery.value }, { preserveState: true, replace: true });
};

// Modal Topup State
const isTopupModalOpen = ref(false);
const selectedUser = ref(null);

const topupForm = useForm({
  tokens: 10,
  reason: 'Bonus Top-up Admin',
});

const openTopupModal = (user) => {
  selectedUser.value = user;
  topupForm.tokens = 10;
  topupForm.reason = 'Bonus Top-up Admin';
  isTopupModalOpen.value = true;
};

const submitTopup = () => {
  if (!selectedUser.value) return;

  topupForm.post(route('admin.users.topup', selectedUser.value.id), {
    onSuccess: () => {
      isTopupModalOpen.value = false;
      selectedUser.value = null;
    }
  });
};

const changeRole = (user, newRole) => {
  if (confirm(`Apakah Anda yakin ingin mengubah role ${user.name} menjadi ${newRole}?`)) {
    router.post(route('admin.users.role', user.id), { role: newRole });
  }
};
</script>

<template>
  <AdminLayout>
    <Head title="List User & Topup Token - Admin KADU" />

    <div class="space-y-6 text-left">
      <!-- Header Banner -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 p-6 rounded-3xl shadow-xl">
        <div>
          <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <Users class="w-6 h-6 text-indigo-400" />
            Manajemen User & Kuota Token
          </h1>
          <p class="text-xs text-slate-400 mt-1">Daftar pengguna terdaftar, sisa kuota token, dan alokasi top-up manual.</p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full sm:w-72">
          <input 
            v-model="searchQuery" 
            @keyup.enter="handleSearch"
            type="text" 
            placeholder="Cari nama, email, username..." 
            class="w-full h-11 bg-slate-950 border border-slate-800 rounded-2xl pl-10 pr-4 text-xs font-semibold text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-0"
          />
          <Search class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5" />
        </div>
      </div>

      <!-- Users Table Card -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-800 text-xxs font-bold text-slate-400 uppercase tracking-wider">
                <th class="py-3.5 px-4">Pengguna</th>
                <th class="py-3.5 px-4">Role</th>
                <th class="py-3.5 px-4 text-center">Sisa Token RPP</th>
                <th class="py-3.5 px-4 text-center">Aksi Administrator</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-xs">
              <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-850/50 transition-colors">
                <td class="py-4 px-4 font-bold text-white">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white font-extrabold flex items-center justify-center text-sm shadow shrink-0">
                      {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                    </div>
                    <div>
                      <p class="text-sm font-bold text-white">{{ user.name }}</p>
                      <p class="text-xxs text-slate-400 font-semibold">{{ user.email }}</p>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-4">
                  <span 
                    :class="[
                      'px-3 py-1 rounded-full text-xxs font-extrabold uppercase border',
                      user.roles?.some(r => r.name === 'admin') 
                        ? 'bg-amber-500/10 text-amber-300 border-amber-500/20' 
                        : 'bg-indigo-500/10 text-indigo-300 border-indigo-500/20'
                    ]"
                  >
                    {{ user.roles?.[0]?.name || 'user' }}
                  </span>
                </td>

                <td class="py-4 px-4 text-center">
                  <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-950 border border-slate-800 rounded-xl">
                    <Coins class="w-4 h-4 text-amber-400" />
                    <span class="font-black text-sm text-white">{{ user.tokens ?? 0 }} Token</span>
                  </div>
                </td>

                <td class="py-4 px-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button 
                      @click="openTopupModal(user)" 
                      class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow flex items-center gap-1 cursor-pointer"
                    >
                      <PlusCircle class="w-3.5 h-3.5" />
                      Top-Up Token
                    </button>

                    <button 
                      v-if="!user.roles?.some(r => r.name === 'admin')"
                      @click="changeRole(user, 'admin')" 
                      class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl border border-slate-700 cursor-pointer"
                      title="Jadikan Admin"
                    >
                      Jadi Admin
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Top-up Token Manual -->
      <div v-if="isTopupModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6">
          <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
              <Coins class="w-5 h-5 text-amber-400" />
              Top-Up Token Manual
            </h3>
            <button @click="isTopupModalOpen = false" class="text-slate-400 hover:text-white">
              <X class="w-5 h-5" />
            </button>
          </div>

          <div class="space-y-4 text-xs">
            <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800 space-y-1">
              <span class="text-xxs font-bold text-slate-400 uppercase">Penerima Top-Up:</span>
              <p class="font-bold text-white text-sm">{{ selectedUser?.name }}</p>
              <p class="text-slate-400">{{ selectedUser?.email }}</p>
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1.5">Jumlah Tambahan Token</label>
              <input 
                v-model="topupForm.tokens" 
                type="number" 
                min="1" 
                class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-sm font-bold text-white" 
              />
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1.5">Catatan / Alasan Top-up</label>
              <input 
                v-model="topupForm.reason" 
                type="text" 
                placeholder="Bonus / Pembelian Manual" 
                class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" 
              />
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button 
              type="button" 
              @click="isTopupModalOpen = false" 
              class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 rounded-xl"
            >
              Batal
            </button>
            <button 
              type="button" 
              @click="submitTopup" 
              :disabled="topupForm.processing"
              class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-xl shadow"
            >
              Simpan Top-Up ✨
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
