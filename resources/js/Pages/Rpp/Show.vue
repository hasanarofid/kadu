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
  Download,
  Presentation
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

          <!-- TAB 2: Media Pembelajaran (PPT Slide Deck Viewer) -->
          <div v-if="activeTab === 'media'" class="space-y-6">
            <div class="flex items-center justify-between bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
              <div>
                <h3 class="font-black text-sm text-indigo-950 flex items-center gap-2">
                  <Presentation class="w-5 h-5 text-indigo-600" />
                  SLIDE PRESENTASI (PPT) & MEDIA INTERAKTIF VOKASI
                </h3>
                <p class="text-xxs text-indigo-700 font-medium">Struktur Slide Deck 16:9 Siap Diimpor ke Canva, PowerPoint, atau Gamma AI</p>
              </div>
            </div>

            <!-- Visual 16:9 Aspect Ratio Slide Deck Carousel / Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Slide 1 -->
              <div class="aspect-video bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 rounded-2xl shadow-lg border border-indigo-500/30 flex flex-col justify-between relative overflow-hidden">
                <div class="flex items-center justify-between text-xxs font-bold text-indigo-300">
                  <span>SLIDE 1 • COVER PRESENTASI</span>
                  <span class="px-2 py-0.5 rounded bg-indigo-500/20 border border-indigo-400/30">16:9</span>
                </div>
                <div class="space-y-1">
                  <h4 class="text-sm font-black text-white leading-tight uppercase">Penerapan Deep Learning {{ rpp.mata_pelajaran }}</h4>
                  <p class="text-xxs text-indigo-200">Konsentrasi Keahlian: {{ rpp.jurusan_smk }}</p>
                  <p class="text-xxs text-slate-300">Kemitraan DU/DI: {{ rpp.kemitraan_dudi }}</p>
                </div>
                <div class="text-xxs text-slate-400 border-t border-indigo-500/20 pt-2 flex items-center justify-between">
                  <span>Modul Vokasi Merdeka</span>
                  <span>KADU AI Engine</span>
                </div>
              </div>

              <!-- Slide 2 -->
              <div class="aspect-video bg-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 flex flex-col justify-between">
                <div class="flex items-center justify-between text-xxs font-bold text-amber-400">
                  <span>SLIDE 2 • MINDFUL & APERSEPSI</span>
                  <span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/30">Langkah 1</span>
                </div>
                <div class="space-y-2">
                  <h5 class="text-xs font-bold text-white">Pertanyaan Pemantik Vokasi:</h5>
                  <p class="text-xxs text-slate-300 italic leading-relaxed">"Bagaimana kalkulasi presisi pada {{ rpp.mata_pelajaran }} mencegah kegagalan teknis di industri pasangan?"</p>
                  <div class="p-2 rounded-lg bg-slate-950 border border-slate-800 text-xxs text-amber-300 font-semibold">
                    ⚡ Hening 1 Menit & Safety Briefing APD K3LH
                  </div>
                </div>
                <div class="text-xxs text-slate-400 pt-2 border-t border-slate-800">Kegiatan Awal Pembelajaran</div>
              </div>

              <!-- Slide 3 -->
              <div class="aspect-video bg-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 flex flex-col justify-between">
                <div class="flex items-center justify-between text-xxs font-bold text-emerald-400">
                  <span>SLIDE 3 • NUMERASI & KONSEP BENGKEL</span>
                  <span class="px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/30">Langkah 2</span>
                </div>
                <div class="space-y-1.5">
                  <h5 class="text-xs font-bold text-white">Formula Presisi & SOP Industri:</h5>
                  <p class="text-xxs text-slate-300 leading-relaxed">{{ rpp.capaian_pembelajaran }}</p>
                </div>
                <div class="text-xxs text-emerald-300 font-bold border-t border-slate-800 pt-2">
                  Stimulus Literasi & Numerasi Terapan SMK
                </div>
              </div>

              <!-- Slide 4 -->
              <div class="aspect-video bg-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 flex flex-col justify-between">
                <div class="flex items-center justify-between text-xxs font-bold text-violet-400">
                  <span>SLIDE 4 • SIMULASI DIGITAL & LKPD</span>
                  <span class="px-2 py-0.5 rounded bg-violet-500/10 border border-violet-500/30">Langkah 3</span>
                </div>
                <div class="space-y-2">
                  <h5 class="text-xs font-bold text-white">Software & Tools Digital:</h5>
                  <p class="text-xxs text-violet-300 font-semibold">{{ rpp.software_digital }}</p>
                  <p class="text-xxs text-slate-400">Ruang Virtual: {{ rpp.ruang_virtual }}</p>
                </div>
                <div class="text-xxs text-slate-400 border-t border-slate-800 pt-2">Unjuk Kerja Praktik Siswa</div>
              </div>
            </div>

            <!-- Full Raw Slide Text -->
            <div class="whitespace-pre-wrap font-mono text-xs bg-slate-50 p-5 rounded-xl border border-slate-200">
              {{ rpp.content_media }}
            </div>
          </div>

          <!-- TAB 3: Video Pembelajaran (Naskah & Simulasi Player) -->
          <div v-if="activeTab === 'video'" class="space-y-6">
            <div class="flex items-center justify-between bg-violet-50 p-4 rounded-2xl border border-violet-100">
              <div>
                <h3 class="font-black text-sm text-violet-950 flex items-center gap-2">
                  <Video class="w-5 h-5 text-violet-600" />
                  NASKAH & STRUKTUR VIDEO PEMBELAJARAN VOKASI 3D
                </h3>
                <p class="text-xxs text-violet-700 font-medium">Alur Visual & Narasi Suara Video Pembelajaran Siap Didemokan & Diproduksi</p>
              </div>
            </div>

            <!-- Simulated Interactive Video Player Preview -->
            <div class="aspect-video bg-slate-950 rounded-3xl border border-violet-500/40 p-6 flex flex-col justify-between relative shadow-2xl overflow-hidden group">
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent z-10"></div>
              
              <!-- Video Header -->
              <div class="relative z-20 flex items-center justify-between text-xs text-white">
                <span class="px-3 py-1 rounded-full bg-violet-600/80 backdrop-blur-md text-xxs font-bold uppercase tracking-wider">
                  Video Tutorial Vokasi Deep Learning
                </span>
                <span class="text-xxs text-slate-300 font-semibold">Durasi Ideal: 03:00 Menit</span>
              </div>

              <!-- Center Play Icon Placeholder -->
              <div class="relative z-20 self-center flex flex-col items-center gap-2 my-auto text-center">
                <div class="w-16 h-16 rounded-full bg-violet-600 text-white flex items-center justify-center shadow-2xl border-2 border-white/20 group-hover:scale-110 transition-all cursor-pointer">
                  <Video class="w-8 h-8 ml-1" />
                </div>
                <h4 class="text-sm font-black text-white max-w-md">Video Pembelajaran Vokasi: {{ rpp.mata_pelajaran }}</h4>
                <p class="text-xxs text-slate-300">Konsentrasi Keahlian: {{ rpp.jurusan_smk }}</p>
              </div>

              <!-- Video Footer Bar -->
              <div class="relative z-20 flex items-center justify-between text-xxs text-slate-400 border-t border-white/10 pt-3">
                <span>Mitra DU/DI: {{ rpp.kemitraan_dudi }}</span>
                <span>Standar Kurikulum Merdeka</span>
              </div>
            </div>

            <!-- Video Timeline & Narasi Suara Cards -->
            <div class="space-y-3">
              <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Rincian Narasi Suara & Alur Scene Video:</h4>

              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-4">
                <div class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xxs font-black shrink-0">SCENE 1 (00:00 - 00:30)</div>
                <div class="space-y-1">
                  <h5 class="text-xs font-bold text-slate-900">Pembukaan & Pengenalan K3LH</h5>
                  <p class="text-xxs text-slate-600">Deskripsi Visual: Tampilan 3D suasana bengkel {{ rpp.ruang_fisik }} dengan siswa mengenakan APD lengkap.</p>
                  <p class="text-xxs text-indigo-700 font-bold bg-indigo-50 p-2 rounded-lg border border-indigo-100 italic">
                    Narasi Suara (Voiceover): "Selamat datang di modul praktik vokasi {{ rpp.mata_pelajaran }}. Sebelum memulai unjuk kerja, pastikan kelengkapan APD dan keselamatan kerja Anda."
                  </p>
                </div>
              </div>

              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-4">
                <div class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xxs font-black shrink-0">SCENE 2 (00:30 - 01:45)</div>
                <div class="space-y-1">
                  <h5 class="text-xs font-bold text-slate-900">Demonstrasi Pengukuran Presisi & SOP</h5>
                  <p class="text-xxs text-slate-600">Deskripsi Visual: Demostrasi instrumen kerja presisi dan software {{ rpp.software_digital }}.</p>
                  <p class="text-xxs text-indigo-700 font-bold bg-indigo-50 p-2 rounded-lg border border-indigo-100 italic">
                    Narasi Suara (Voiceover): "Perhatikan alur pengukuran presisi berikut. Pengukuran akurat adalah jaminan kualitas standar industri {{ rpp.kemitraan_dudi }}."
                  </p>
                </div>
              </div>

              <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-start gap-4">
                <div class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xxs font-black shrink-0">SCENE 3 (01:45 - 03:00)</div>
                <div class="space-y-1">
                  <h5 class="text-xs font-bold text-slate-900">Refleksi Mandiri & Penutup</h5>
                  <p class="text-xxs text-slate-600">Deskripsi Visual: Ringkasan poin utama dan tampilan Kode QR LKPD Digital.</p>
                  <p class="text-xxs text-indigo-700 font-bold bg-indigo-50 p-2 rounded-lg border border-indigo-100 italic">
                    Narasi Suara (Voiceover): "Diskusikan hasil analisismu bersama tim kelompok. Sampai jumpa di modul vokasi berikutnya!"
                  </p>
                </div>
              </div>
            </div>

            <!-- Full Raw Video Script Text -->
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
