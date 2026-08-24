<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { 
  Coins, 
  Plus, 
  Edit3, 
  Trash2, 
  Check, 
  X, 
  Sparkles,
  CheckCircle2
} from 'lucide-vue-next';

const props = defineProps({
  packages: Array,
});

const isModalOpen = ref(false);
const editingPackage = ref(null);

const form = useForm({
  name: '',
  tokens: 10,
  price: 50000,
  description: '',
  is_active: true,
});

const openCreateModal = () => {
  editingPackage.value = null;
  form.reset();
  form.name = '';
  form.tokens = 10;
  form.price = 50000;
  form.description = '';
  form.is_active = true;
  isModalOpen.value = true;
};

const openEditModal = (pkg) => {
  editingPackage.value = pkg;
  form.name = pkg.name;
  form.tokens = pkg.tokens;
  form.price = pkg.price;
  form.description = pkg.description;
  form.is_active = pkg.is_active;
  isModalOpen.value = true;
};

const submitForm = () => {
  if (editingPackage.value) {
    form.put(route('admin.packages.update', editingPackage.value.id), {
      onSuccess: () => {
        isModalOpen.value = false;
      }
    });
  } else {
    form.post(route('admin.packages.store'), {
      onSuccess: () => {
        isModalOpen.value = false;
      }
    });
  }
};

const deletePackage = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus Paket Token ini?')) {
    router.delete(route('admin.packages.destroy', id));
  }
};
</script>

<template>
  <AdminLayout>
    <Head title="Manajemen Paket Token - Admin KADU" />

    <div class="space-y-6 text-left">
      <!-- Header Banner -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900/90 border border-slate-800 p-6 rounded-3xl shadow-xl">
        <div>
          <h1 class="text-2xl font-black text-white flex items-center gap-2">
            <Coins class="w-6 h-6 text-amber-400" />
            Manajemen Paket Token RPP
          </h1>
          <p class="text-xs text-slate-400 mt-1">Konfigurasi paket kuota token dan harga pembelian via Payment Gateway.</p>
        </div>

        <button 
          @click="openCreateModal" 
          class="px-5 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 hover:brightness-110 text-white text-xs font-bold rounded-2xl shadow flex items-center gap-1.5 cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4" />
          Tambah Paket Token Baru
        </button>
      </div>

      <!-- Packages Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div 
          v-for="pkg in packages" 
          :key="pkg.id" 
          class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-4 relative flex flex-col justify-between"
        >
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-300 text-xxs font-extrabold border border-indigo-500/20 uppercase">
                {{ pkg.is_active ? 'Aktif' : 'Non-Aktif' }}
              </span>

              <div class="flex items-center gap-1">
                <button @click="openEditModal(pkg)" class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800">
                  <Edit3 class="w-4 h-4" />
                </button>
                <button @click="deletePackage(pkg.id)" class="p-2 text-slate-400 hover:text-rose-400 rounded-xl hover:bg-slate-800">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>

            <div>
              <h3 class="text-xl font-black text-white">{{ pkg.name }}</h3>
              <p class="text-xs text-slate-400 leading-relaxed mt-1">{{ pkg.description }}</p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
              <span class="text-xxs font-bold text-slate-400 uppercase">Kuota Token RPP:</span>
              <p class="text-2xl font-black text-amber-400">{{ pkg.tokens }} Token</p>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
            <span class="text-xs text-slate-400 font-bold">Harga Paket:</span>
            <span class="text-lg font-black text-emerald-400">Rp {{ Number(pkg.price).toLocaleString('id-ID') }}</span>
          </div>
        </div>
      </div>

      <!-- Modal Create/Edit Package -->
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6">
          <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
              <Coins class="w-5 h-5 text-indigo-400" />
              {{ editingPackage ? 'Edit Paket Token' : 'Tambah Paket Token Baru' }}
            </h3>
            <button @click="isModalOpen = false" class="text-slate-400 hover:text-white">
              <X class="w-5 h-5" />
            </button>
          </div>

          <form @submit.prevent="submitForm" class="space-y-4 text-xs">
            <div>
              <label class="block font-bold text-slate-300 mb-1.5">Nama Paket</label>
              <input v-model="form.name" type="text" placeholder="Paket Professional" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-300 mb-1.5">Jumlah Token</label>
                <input v-model="form.tokens" type="number" min="1" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-sm font-bold text-white" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1.5">Harga (Rp)</label>
                <input v-model="form.price" type="number" min="0" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-sm font-bold text-white" />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-300 mb-1.5">Deskripsi Paket</label>
              <textarea v-model="form.description" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs font-medium text-white"></textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
              <input type="checkbox" v-model="form.is_active" id="is_active" class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-0" />
              <label for="is_active" class="text-xs font-bold text-slate-300">Aktifkan Paket Ini untuk Pembelian User</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
              <button type="button" @click="isModalOpen = false" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 rounded-xl">
                Batal
              </button>
              <button type="submit" :disabled="form.processing" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-xl shadow">
                Simpan Paket ✨
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
