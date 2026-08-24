<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { 
  Brain, 
  Plus, 
  Search, 
  FileText, 
  Eye, 
  Edit3, 
  Trash2, 
  Sparkles, 
  BookOpen, 
  GraduationCap, 
  Wrench, 
  Printer, 
  LogOut,
  User,
  Shield,
  LayoutDashboard
} from 'lucide-vue-next';

const props = defineProps({
  rpps: {
    type: Object,
    required: true
  }
});

const pageData = usePage();
const currentUser = pageData.props.auth?.user;

const searchQuery = ref('');

const deleteRpp = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus RPP ini?')) {
    router.delete(route('rpps.destroy', id));
  }
};
</script>

<template>
  <Head title="Dashboard RPP Saya - KADU (Karsa Edukasi Vokasi)" />

  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white relative">
    <!-- Ambient Lights -->
    <div class="fixed top-[-10%] left-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-600/10 blur-[140px] pointer-events-none"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-violet-600/10 blur-[140px] pointer-events-none"></div>

    <!-- Top Navigation Bar -->
    <nav class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
        <Link href="/" class="flex items-center gap-3">
          <div class="p-2 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-xl text-white shadow-md">
            <Brain class="w-5 h-5" />
          </div>
          <span class="font-black text-lg text-white tracking-tight flex items-center gap-2">
            KADU <span class="text-xxs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 font-bold border border-indigo-500/30">VOKASI</span>
          </span>
        </Link>

        <div class="flex items-center gap-4">
          <Link 
            v-if="currentUser?.roles?.some(r => r.name === 'admin')"
            :href="route('admin.dashboard')"
            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-300 rounded-xl border border-slate-700 transition-colors"
          >
            <Shield class="w-3.5 h-3.5 text-indigo-400" />
            Panel Admin CMS
          </Link>

          <div class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-slate-950/80 border border-slate-800 text-xs">
            <div class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xxs">
              {{ currentUser?.name?.charAt(0) || 'U' }}
            </div>
            <span class="font-bold text-white max-w-[120px] truncate">{{ currentUser?.name }}</span>
          </div>

          <Link 
            :href="route('logout')" 
            method="post" 
            as="button" 
            class="p-2 text-slate-400 hover:text-rose-400 transition-colors rounded-xl hover:bg-slate-900"
            title="Keluar"
          >
            <LogOut class="w-4 h-4" />
          </Link>
        </div>
      </div>
    </nav>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
      <!-- Header Banner -->
      <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-indigo-950/80 via-slate-900 to-slate-950 border border-indigo-500/30 shadow-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2 z-10 text-left">
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/15 rounded-full text-indigo-300 text-xxs font-bold border border-indigo-500/30">
            <Sparkles class="w-3.5 h-3.5 text-indigo-400" />
            Generator RPP Deep Learning Vokasi SMK
          </div>
          <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
            Manajemen & Generator RPP Saya
          </h1>
          <p class="text-xs sm:text-sm text-slate-300 font-medium max-w-xl">
            Kelola RPP & Modul Ajar Vokasi berbasis Kurikulum Merdeka, Literasi & Numerasi Terapan, dan standar DU/DI secara instan.
          </p>
        </div>

        <Link 
          :href="route('rpps.create')" 
          class="z-10 inline-flex items-center justify-center px-6 py-3.5 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 text-xs font-extrabold text-white rounded-2xl shadow-xl shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4 mr-2 stroke-[3]" />
          Buat RPP Baru (AI Wizard)
        </Link>
      </div>

      <!-- Stats Cards Row -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
          <div class="space-y-1 text-left">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Total RPP Tersimpan</span>
            <p class="text-2xl font-black text-white">{{ rpps.total }} RPP</p>
          </div>
          <div class="p-3 rounded-xl bg-indigo-600/10 text-indigo-400 border border-indigo-500/20">
            <FileText class="w-6 h-6" />
          </div>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
          <div class="space-y-1 text-left">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Kurikulum</span>
            <p class="text-base font-bold text-indigo-300">Merdeka & Deep Learning</p>
          </div>
          <div class="p-3 rounded-xl bg-violet-600/10 text-violet-400 border border-violet-500/20">
            <GraduationCap class="w-6 h-6" />
          </div>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/80 border border-slate-800 flex items-center justify-between">
          <div class="space-y-1 text-left">
            <span class="text-xxs font-bold text-slate-400 uppercase tracking-wider">Standar Vokasi</span>
            <p class="text-base font-bold text-purple-300">DU/DI Industri Pasangan</p>
          </div>
          <div class="p-3 rounded-xl bg-purple-600/10 text-purple-400 border border-purple-500/20">
            <Wrench class="w-6 h-6" />
          </div>
        </div>
      </div>

      <!-- RPP List Table Card -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h3 class="text-lg font-bold text-white text-left flex items-center gap-2">
            <BookOpen class="w-5 h-5 text-indigo-400" />
            Daftar RPP & Modul Ajar Vokasi
          </h3>
        </div>

        <!-- RPP Table / Empty State -->
        <div v-if="rpps.data.length === 0" class="py-16 text-center space-y-4 bg-slate-950/60 rounded-2xl border border-slate-800/80">
          <div class="w-16 h-16 rounded-full bg-indigo-600/10 text-indigo-400 flex items-center justify-center mx-auto border border-indigo-500/20">
            <FileText class="w-8 h-8" />
          </div>
          <div class="space-y-1">
            <h4 class="text-base font-bold text-white">Belum Ada RPP Vokasi</h4>
            <p class="text-xs text-slate-400">Klik tombol di bawah ini untuk membuat RPP berbasis AI pertama Anda.</p>
          </div>
          <Link 
            :href="route('rpps.create')" 
            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-xl shadow"
          >
            <Plus class="w-4 h-4 mr-1.5" />
            Buat RPP Sekarang
          </Link>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="border-b border-slate-800 text-xxs font-bold text-slate-400 uppercase tracking-wider">
                <th class="py-3.5 px-4">Judul RPP / Modul Ajar</th>
                <th class="py-3.5 px-4">Jurusan SMK</th>
                <th class="py-3.5 px-4">Kelas & Waktu</th>
                <th class="py-3.5 px-4">Model Pembelajaran</th>
                <th class="py-3.5 px-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60 text-xs">
              <tr 
                v-for="rpp in rpps.data" 
                :key="rpp.id"
                class="hover:bg-slate-850/50 transition-colors group"
              >
                <td class="py-4 px-4 font-bold text-white group-hover:text-indigo-300 transition-colors max-w-xs">
                  <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-indigo-600/10 text-indigo-400 shrink-0 border border-indigo-500/20">
                      <FileText class="w-4 h-4" />
                    </div>
                    <div>
                      <p class="line-clamp-1">{{ rpp.title }}</p>
                      <span class="text-xxs font-semibold text-slate-400">{{ rpp.mata_pelajaran }}</span>
                    </div>
                  </div>
                </td>

                <td class="py-4 px-4 font-semibold text-slate-300">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-950 border border-slate-800 text-xxs text-indigo-300 font-bold">
                    {{ rpp.jurusan_smk }}
                  </span>
                </td>

                <td class="py-4 px-4 text-slate-300">
                  <p class="font-bold">{{ rpp.kelas_semester }}</p>
                  <p class="text-xxs text-slate-400">{{ rpp.alokasi_waktu }}</p>
                </td>

                <td class="py-4 px-4 font-medium text-slate-300">
                  <span class="px-2.5 py-1 rounded-full bg-purple-500/10 text-purple-300 border border-purple-500/20 text-xxs">
                    {{ rpp.model_pembelajaran }}
                  </span>
                </td>

                <td class="py-4 px-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <Link 
                      :href="route('rpps.show', rpp.id)" 
                      class="p-2 bg-indigo-600/20 hover:bg-indigo-600 text-indigo-300 hover:text-white rounded-xl transition-all border border-indigo-500/30"
                      title="Lihat & Cetak RPP"
                    >
                      <Eye class="w-3.5 h-3.5" />
                    </Link>

                    <Link 
                      :href="route('rpps.edit', rpp.id)" 
                      class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl transition-all border border-slate-700"
                      title="Edit RPP"
                    >
                      <Edit3 class="w-3.5 h-3.5" />
                    </Link>

                    <button 
                      @click="deleteRpp(rpp.id)" 
                      class="p-2 bg-rose-500/10 hover:bg-rose-600 text-rose-400 hover:text-white rounded-xl transition-all border border-rose-500/20"
                      title="Hapus RPP"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>
