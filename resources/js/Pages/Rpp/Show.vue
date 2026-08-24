<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
  Brain, 
  Printer, 
  ArrowLeft, 
  FileText, 
  Layers, 
  Video, 
  BookOpen, 
  Plus, 
  Sparkles, 
  CheckCircle,
  Download
} from 'lucide-vue-next';

const props = defineProps({
  rpp: {
    type: Object,
    required: true
  }
});

const activeTab = ref('rpp'); // 'rpp', 'media', 'video', 'materi'

const printRpp = () => {
  window.print();
};
</script>

<template>
  <Head :title="rpp.title + ' - KADU (Karsa Edukasi Vokasi)'" />

  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white relative pb-16 print:bg-white print:text-black">
    <!-- Navbar (Hidden on Print) -->
    <nav class="bg-indigo-900/90 backdrop-blur-xl border-b border-indigo-700/50 sticky top-0 z-50 print:hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
        <Link href="/" class="flex items-center gap-3">
          <div class="p-2 bg-white text-indigo-700 rounded-xl font-bold shadow">
            <Brain class="w-5 h-5" />
          </div>
          <div>
            <span class="font-black text-lg text-white tracking-tight">
              KADU
            </span>
            <p class="text-xxs text-indigo-200 font-medium">Karsa Edukasi - RPP Deep Learning</p>
          </div>
        </Link>

        <div class="flex items-center gap-3">
          <button 
            @click="printRpp" 
            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold flex items-center gap-1.5 shadow"
          >
            <Printer class="w-4 h-4 text-emerald-400" />
            <span>Cetak Dokumen PDF</span>
          </button>

          <Link 
            :href="route('rpps.index')" 
            class="p-2 bg-indigo-800 text-white rounded-xl hover:bg-indigo-700 text-xs font-bold"
          >
            <ArrowLeft class="w-4 h-4 inline mr-1" /> Dashboard
          </Link>
        </div>
      </div>
    </nav>

    <!-- Main Output Area -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 pt-8 space-y-6 text-left print:p-0 print:max-w-full">
      <!-- Output Tabs Navigation (Hidden on Print) -->
      <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-900/90 p-2.5 rounded-2xl border border-slate-800 print:hidden">
        <div class="flex items-center gap-2">
          <button 
            @click="activeTab = 'rpp'"
            :class="['px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-2 transition-all cursor-pointer', activeTab === 'rpp' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white']"
          >
            <FileText class="w-4 h-4" />
            RPP / Modul Ajar
          </button>

          <button 
            @click="activeTab = 'media'"
            :class="['px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-2 transition-all cursor-pointer', activeTab === 'media' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white']"
          >
            <Layers class="w-4 h-4" />
            Media Pembelajaran
          </button>

          <button 
            @click="activeTab = 'video'"
            :class="['px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-2 transition-all cursor-pointer', activeTab === 'video' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white']"
          >
            <Video class="w-4 h-4" />
            Video Script & Prompt
          </button>

          <button 
            @click="activeTab = 'materi'"
            :class="['px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-2 transition-all cursor-pointer', activeTab === 'materi' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white']"
          >
            <BookOpen class="w-4 h-4" />
            Materi Pembelajaran
          </button>
        </div>

        <button 
          @click="printRpp" 
          class="px-4 py-2 bg-slate-950 hover:bg-slate-900 border border-slate-800 text-white text-xs font-bold rounded-xl flex items-center gap-1.5"
        >
          <Printer class="w-3.5 h-3.5 text-emerald-400" />
          Cetak PDF
        </button>
      </div>

      <!-- Output Document Render Paper Card -->
      <div class="bg-white text-slate-900 p-8 sm:p-12 rounded-3xl shadow-2xl space-y-8 font-sans border border-slate-200 print:shadow-none print:p-0 print:border-none">
        <!-- Header Document -->
        <div class="text-center space-y-2 border-b-2 border-slate-900 pb-6">
          <h1 class="text-xl sm:text-2xl font-black uppercase tracking-tight text-slate-900">
            RENCANA PROGRAM PEMBELAJARAN (RPP) / MODUL AJAR
          </h1>
          <p class="text-xs font-bold text-slate-700 uppercase tracking-widest">
            Pendekatan Pembelajaran Mendalam (Deep Learning) - SMK Vokasi
          </p>
        </div>

        <!-- Metadata Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium bg-slate-50 p-4 rounded-xl border border-slate-200">
          <div><span class="font-bold text-slate-700">Mata Pelajaran:</span> {{ rpp.mata_pelajaran }}</div>
          <div><span class="font-bold text-slate-700">Konsentrasi Keahlian:</span> {{ rpp.jurusan_smk }}</div>
          <div><span class="font-bold text-slate-700">Kelas / Semester / Alokasi Waktu:</span> {{ rpp.kelas_semester }} ({{ rpp.alokasi_waktu }})</div>
          <div><span class="font-bold text-slate-700">Karakteristik Siswa:</span> {{ rpp.karakteristik_fisik }}</div>
          <div class="sm:col-span-2"><span class="font-bold text-slate-700">Capaian Pembelajaran (CP):</span> {{ rpp.capaian_pembelajaran }}</div>
        </div>

        <!-- Dynamic Active Tab Content -->
        <div class="space-y-6 text-xs text-slate-800 leading-relaxed font-sans">
          <!-- TAB 1: RPP Utama -->
          <div v-if="activeTab === 'rpp'" class="space-y-6">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
              <h3 class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                ⚙️ I. KERANGKA PEMBELAJARAN & EKOSISTEM
              </h3>
              <ul class="space-y-1 list-disc list-inside">
                <li><span class="font-bold">Praktik Pedagogik:</span> {{ rpp.model_pembelajaran }}</li>
                <li><span class="font-bold">Metode & Strategi:</span> {{ rpp.metode_pembelajaran }}</li>
                <li><span class="font-bold">Kemitraan DU/DI:</span> {{ rpp.kemitraan_dudi }}</li>
                <li><span class="font-bold">Ruang Fisik & Virtual:</span> {{ rpp.ruang_fisik }} | {{ rpp.ruang_virtual }}</li>
                <li><span class="font-bold">Software Digital:</span> {{ rpp.software_digital }}</li>
              </ul>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
              <h3 class="font-bold text-sm text-slate-900 flex items-center gap-1.5">
                🎯 II. DIMENSI PROFIL LULUSAN VOKASI
              </h3>
              <ul class="space-y-1 list-disc list-inside">
                <li v-for="(dimensi, idx) in rpp.dimensi_profil" :key="idx">{{ dimensi }}</li>
              </ul>
            </div>

            <div class="whitespace-pre-wrap font-mono text-xs bg-slate-50 p-5 rounded-xl border border-slate-200 leading-relaxed">
              {{ rpp.content_rpp }}
            </div>
          </div>

          <!-- TAB 2: Media Pembelajaran -->
          <div v-if="activeTab === 'media'" class="space-y-4">
            <h3 class="font-bold text-sm text-slate-900">🖼️ DRAFT MEDIA PEMBELAJARAN VOKASI</h3>
            <div class="whitespace-pre-wrap font-mono text-xs bg-slate-50 p-5 rounded-xl border border-slate-200">
              {{ rpp.content_media }}
            </div>
          </div>

          <!-- TAB 3: Video Script & Prompt -->
          <div v-if="activeTab === 'video'" class="space-y-4">
            <h3 class="font-bold text-sm text-slate-900">🎬 PROMPT & SCRIPT VIDEO AI</h3>
            <div class="whitespace-pre-wrap font-mono text-xs bg-slate-50 p-5 rounded-xl border border-slate-200">
              {{ rpp.content_video_script }}
            </div>
          </div>

          <!-- TAB 4: Materi Pembelajaran -->
          <div v-if="activeTab === 'materi'" class="space-y-4">
            <h3 class="font-bold text-sm text-slate-900">📚 RINGKASAN MATERI LITERASI & NUMERASI</h3>
            <div class="whitespace-pre-wrap font-mono text-xs bg-slate-50 p-5 rounded-xl border border-slate-200">
              {{ rpp.content_materi }}
            </div>
          </div>

          <!-- Signature Box -->
          <div class="pt-12 flex justify-end text-center text-xs">
            <div class="space-y-12">
              <p>Guru Mata Pelajaran,</p>
              <p class="font-bold underline">({{ rpp.user?.name || '____________________' }})</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Bar (Hidden on Print) -->
      <div class="flex items-center justify-between pt-4 print:hidden">
        <Link :href="route('rpps.index')" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-xs font-bold text-slate-200 rounded-xl">
          ← Kembali ke Daftar RPP
        </Link>

        <Link :href="route('rpps.create')" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-xs font-bold text-white rounded-xl shadow flex items-center gap-1.5">
          <Plus class="w-4 h-4" />
          <span>Buat RPP Baru ✨</span>
        </Link>
      </div>
    </main>
  </div>
</template>
