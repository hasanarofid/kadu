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
  Presentation,
  Play,
  Pause,
  Maximize2,
  X,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next';

const props = defineProps({
  rpp: {
    type: Object,
    required: true
  }
});

const activeTab = ref('rpp'); // 'rpp', 'media', 'video', 'materi'

// Video Audio-Visual Interactive Simulation Player State
const isPlayingVideo = ref(false);
const videoProgress = ref(0);
let progressTimer = null;

const togglePlayVideo = () => {
  if (isPlayingVideo.value) {
    if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel();
    }
    clearInterval(progressTimer);
    isPlayingVideo.value = false;
    videoProgress.value = 0;
  } else {
    isPlayingVideo.value = true;
    videoProgress.value = 0;
    
    // Simulate Video Timeline Progress
    progressTimer = setInterval(() => {
      if (videoProgress.value < 100) {
        videoProgress.value += 2;
      } else {
        clearInterval(progressTimer);
        isPlayingVideo.value = false;
        videoProgress.value = 0;
      }
    }, 150);

    // Native Web Speech Synthesis Audio Narration for Teacher Demo
    if ('speechSynthesis' in window) {
      window.speechSynthesis.cancel();
      const text = `Selamat datang di modul praktik vokasi ${props.rpp.mata_pelajaran}. Konsentrasi keahlian ${props.rpp.jurusan_smk}. Selalu utamakan keselamatan kerja K3LH dan pengamatan presisi standar industri ${props.rpp.kemitraan_dudi}.`;
      const utterance = new SpeechSynthesisUtterance(text);
      utterance.lang = 'id-ID';
      utterance.rate = 0.95;
      utterance.onend = () => {
        isPlayingVideo.value = false;
        clearInterval(progressTimer);
        videoProgress.value = 0;
      };
      window.speechSynthesis.speak(utterance);
    }
  }
};

// Fullscreen PPT Slide Presenter State
const isPresentationMode = ref(false);
const currentSlide = ref(0);

const slides = [
  {
    title: `Penerapan Deep Learning ${props.rpp.mata_pelajaran}`,
    subtitle: `Konsentrasi Keahlian: ${props.rpp.jurusan_smk}`,
    meta: `Kemitraan DU/DI: ${props.rpp.kemitraan_dudi}`,
    badge: "SLIDE 1 • COVER PRESENTASI",
    type: "cover"
  },
  {
    title: "Pertanyaan Pemantik & Langkah Berkesadaran (Mindful)",
    subtitle: `"Bagaimana kalkulasi presisi pada ${props.rpp.mata_pelajaran} mencegah kegagalan teknis di industri pasangan?"`,
    meta: "⚡ Hening 1 Menit & Safety Briefing APD K3LH",
    badge: "SLIDE 2 • MINDFUL & APERSEPSI",
    type: "mindful"
  },
  {
    title: "Konsep Utama & Stimulus Numerasi Terapan",
    subtitle: props.rpp.capaian_pembelajaran,
    meta: "Standar Operasional Prosedur (SOP) Industri Pasangan",
    badge: "SLIDE 3 • NUMERASI & BENGKEL",
    type: "numerasi"
  },
  {
    title: "Simulasi Digital & Unjuk Kerja Siswa",
    subtitle: `Software & Tools: ${props.rpp.software_digital}`,
    meta: `Ruang Virtual: ${props.rpp.ruang_virtual}`,
    badge: "SLIDE 4 • SIMULASI DIGITAL & LKPD",
    type: "simulasi"
  },
  {
    title: "Refleksi Metakognisi & Evaluasi Mandiri",
    subtitle: "Diskusi Kelompok Vokasi & Penilaian Rubrik Unjuk Kerja Praktik",
    meta: "Standar Kurikulum Merdeka Vokasi",
    badge: "SLIDE 5 • REFLEKSI & PENUTUP",
    type: "refleksi"
  }
];

const startPresentation = (idx = 0) => {
  currentSlide.value = idx;
  isPresentationMode.value = true;
};

const prevSlide = () => {
  if (currentSlide.value > 0) currentSlide.value--;
};

const nextSlide = () => {
  if (currentSlide.value < slides.length - 1) currentSlide.value++;
};

// Download HTML PPT Slide Deck File
const downloadPpt = () => {
  const pptHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Slide PPT - ${props.rpp.mata_pelajaran}</title>
  <style>
    body { font-family: sans-serif; background: #0f172a; color: white; margin: 0; padding: 40px; display: flex; flex-direction: column; gap: 40px; align-items: center; }
    .slide { width: 900px; height: 500px; background: #1e293b; border-radius: 24px; padding: 40px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between; border: 2px solid #3b82f6; }
    h1 { color: #60a5fa; margin: 0; font-size: 28px; }
    p { font-size: 18px; line-height: 1.6; color: #cbd5e1; }
    .badge { font-weight: bold; font-size: 12px; color: #f59e0b; letter-spacing: 1px; }
  </style>
</head>
<body>
  ${slides.map((s, i) => `
    <div class="slide">
      <div class="badge">${s.badge}</div>
      <div>
        <h1>${s.title}</h1>
        <p>${s.subtitle}</p>
      </div>
      <div style="font-size: 14px; color: #94a3b8;">${s.meta}</div>
    </div>
  `).join('')}
</body>
</html>`;

  const blob = new Blob([pptHtml], { type: 'text/html' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `PPT_Slide_${props.rpp.mata_pelajaran.replace(/[^a-zA-Z0-9]/g, '_')}.html`;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
};

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

          <!-- TAB 2: Media Pembelajaran (PPT Slide Deck Viewer & Presenter) -->
          <div v-if="activeTab === 'media'" class="space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-indigo-50 p-4 rounded-2xl border border-indigo-100">
              <div>
                <h3 class="font-black text-sm text-indigo-950 flex items-center gap-2">
                  <Presentation class="w-5 h-5 text-indigo-600" />
                  SLIDE PRESENTASI (PPT) & MEDIA INTERAKTIF VOKASI
                </h3>
                <p class="text-xxs text-indigo-700 font-medium">Struktur Slide Deck 16:9 Siap Diimpor ke Canva, PowerPoint, atau Gamma AI</p>
              </div>

              <div class="flex items-center gap-2">
                <button 
                  @click="startPresentation(0)" 
                  class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-extrabold flex items-center gap-1.5 shadow transition-all"
                >
                  <Maximize2 class="w-3.5 h-3.5" />
                  <span>Mulai Presentasi (Fullscreen)</span>
                </button>
                <button 
                  @click="downloadPpt" 
                  class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-extrabold flex items-center gap-1.5 transition-all"
                >
                  <Download class="w-3.5 h-3.5 text-amber-400" />
                  <span>Unduh File PPT</span>
                </button>
              </div>
            </div>

            <!-- Visual 16:9 Aspect Ratio Slide Deck Cards (Click to Present) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Slide 1 -->
              <div @click="startPresentation(0)" class="aspect-video bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 rounded-2xl shadow-lg border border-indigo-500/30 flex flex-col justify-between relative overflow-hidden cursor-pointer hover:border-indigo-400 group transition-all">
                <div class="flex items-center justify-between text-xxs font-bold text-indigo-300">
                  <span>SLIDE 1 • COVER PRESENTASI</span>
                  <span class="px-2 py-0.5 rounded bg-indigo-500/20 border border-indigo-400/30 flex items-center gap-1">
                    <Maximize2 class="w-3 h-3 group-hover:scale-110" /> Klik Presentasi
                  </span>
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
              <div @click="startPresentation(1)" class="aspect-video bg-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 flex flex-col justify-between cursor-pointer hover:border-amber-400 group transition-all">
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
              <div @click="startPresentation(2)" class="aspect-video bg-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 flex flex-col justify-between cursor-pointer hover:border-emerald-400 group transition-all">
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
              <div @click="startPresentation(3)" class="aspect-video bg-slate-900 text-white p-6 rounded-2xl shadow-lg border border-slate-800 flex flex-col justify-between cursor-pointer hover:border-violet-400 group transition-all">
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
          </div>

          <!-- TAB 3: Video Pembelajaran (Interactive Audio-Visual Player Simulation) -->
          <div v-if="activeTab === 'video'" class="space-y-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-violet-50 p-4 rounded-2xl border border-violet-100">
              <div>
                <h3 class="font-black text-sm text-violet-950 flex items-center gap-2">
                  <Video class="w-5 h-5 text-violet-600" />
                  PLAYER DEMO & NASKAH VIDEO PEMBELAJARAN VOKASI 3D
                </h3>
                <p class="text-xxs text-violet-700 font-medium">Klik tombol Play di bawah untuk mendengarkan Narasi Suara AI & Alur Demokrasi Video</p>
              </div>

              <button 
                @click="togglePlayVideo" 
                :class="['px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-2 shadow transition-all cursor-pointer', isPlayingVideo ? 'bg-rose-600 text-white' : 'bg-violet-600 hover:bg-violet-500 text-white']"
              >
                <Pause v-if="isPlayingVideo" class="w-4 h-4" />
                <Play v-else class="w-4 h-4" />
                <span>{{ isPlayingVideo ? 'Hentikan Narasi (Stop)' : 'Putar Video & Audio Narasi (Play)' }}</span>
              </button>
            </div>

            <!-- Simulated Interactive Video Player Preview -->
            <div class="aspect-video bg-gradient-to-br from-indigo-950 via-slate-900 to-violet-950 rounded-3xl border-2 border-violet-500/40 p-6 flex flex-col justify-between relative shadow-2xl overflow-hidden group">
              <!-- Animated Background Gradient Mesh Glow -->
              <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(124,58,237,0.25),transparent_70%)] pointer-events-none"></div>
              
              <!-- Video Header -->
              <div class="relative z-20 flex items-center justify-between text-xs text-white">
                <span class="px-3 py-1 rounded-full bg-violet-600/80 backdrop-blur-md text-xxs font-bold uppercase tracking-wider flex items-center gap-2 shadow">
                  <span v-if="isPlayingVideo" class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                  <span>Video Tutorial Vokasi Deep Learning</span>
                </span>
                
                <div class="flex items-center gap-2">
                  <span class="text-xxs px-2.5 py-1 rounded-full bg-slate-900/80 border border-slate-700 text-amber-300 font-extrabold uppercase tracking-wider">
                    {{ videoProgress < 30 ? 'Scene 1 • APD K3LH' : (videoProgress < 70 ? 'Scene 2 • SOP Presisi' : 'Scene 3 • Refleksi') }}
                  </span>
                  <span class="text-xxs text-slate-300 font-semibold">Durasi: 03:00</span>
                </div>
              </div>

              <!-- Center Interactive Visual Scene Canvas -->
              <div class="relative z-20 self-center flex flex-col items-center gap-3 my-auto text-center w-full max-w-xl">
                <!-- Scene Visual Graphics & Animation Container -->
                <div class="p-5 rounded-2xl bg-slate-900/90 border border-indigo-500/30 backdrop-blur-xl shadow-2xl w-full flex flex-col items-center gap-3 transition-all duration-500">
                  
                  <!-- Equalizer Sound Wave Animation when Playing -->
                  <div v-if="isPlayingVideo" class="flex items-center justify-center gap-1 h-6">
                    <span class="w-1.5 bg-emerald-400 rounded-full animate-[bounce_1s_infinite_100ms] h-4"></span>
                    <span class="w-1.5 bg-emerald-400 rounded-full animate-[bounce_1s_infinite_300ms] h-6"></span>
                    <span class="w-1.5 bg-emerald-400 rounded-full animate-[bounce_1s_infinite_200ms] h-3"></span>
                    <span class="w-1.5 bg-emerald-400 rounded-full animate-[bounce_1s_infinite_400ms] h-5"></span>
                    <span class="w-1.5 bg-emerald-400 rounded-full animate-[bounce_1s_infinite_150ms] h-6"></span>
                  </div>

                  <!-- Play/Pause Button Circle -->
                  <button 
                    @click="togglePlayVideo"
                    :class="['w-16 h-16 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30 transition-all cursor-pointer', isPlayingVideo ? 'bg-rose-600 text-white scale-105' : 'bg-violet-600 hover:scale-110 text-white']"
                  >
                    <Pause v-if="isPlayingVideo" class="w-8 h-8" />
                    <Play v-else class="w-8 h-8 ml-1" />
                  </button>

                  <div class="space-y-1">
                    <h4 class="text-sm font-black text-white leading-snug">
                      {{ videoProgress < 30 ? 'SCENE 1: APD & K3LH BENGKEL VOKASI' : (videoProgress < 70 ? 'SCENE 2: DEMONSTRASI KALKULASI SOP PRESISI' : 'SCENE 3: REFLEKSI MANDIRI & UNJUK KERJA') }}
                    </h4>
                    <p class="text-xxs text-indigo-300 font-bold">
                      {{ rpp.mata_pelajaran }} • {{ rpp.jurusan_smk }}
                    </p>
                  </div>
                </div>

                <!-- Real-time Subtitle CC Box Overlay -->
                <div class="px-4 py-2 rounded-xl bg-slate-950/90 border border-slate-800 text-xxs font-semibold text-emerald-300 shadow-xl max-w-lg">
                  <span class="text-slate-400 uppercase tracking-widest mr-1 font-bold">[CC SUBTITLE]:</span>
                  <span>
                    {{ videoProgress < 30 
                      ? `"Selamat datang di modul praktik vokasi ${rpp.mata_pelajaran}. Pastikan kelengkapan APD dan keselamatan kerja K3LH Anda."` 
                      : (videoProgress < 70 
                        ? `"Perhatikan alur kalkulasi presisi berikut. Akurasi pengukuran adalah standar utama industri ${rpp.kemitraan_dudi}."` 
                        : `"Diskusikan hasil analisismu bersama kelompok. Sampai jumpa di modul vokasi berikutnya!"`) 
                    }}
                  </span>
                </div>
              </div>

              <!-- Simulated Video Timeline Bar -->
              <div class="relative z-20 space-y-2">
                <div class="w-full bg-slate-900 border border-slate-800 rounded-full h-2 overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-violet-500 via-indigo-500 to-emerald-400 rounded-full transition-all duration-300" :style="{ width: videoProgress + '%' }"></div>
                </div>

                <div class="flex items-center justify-between text-xxs text-slate-400 pt-1 border-t border-white/10">
                  <span>Mitra DU/DI: {{ rpp.kemitraan_dudi }}</span>
                  <span>Digital Software: {{ rpp.software_digital }}</span>
                </div>
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

    <!-- Fullscreen Interactive PPT Presentation Modal -->
    <Teleport to="body">
      <div 
        v-if="isPresentationMode" 
        class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-2xl flex flex-col items-center justify-between p-6 sm:p-10 animate-fade-in text-white"
      >
        <!-- Modal Navbar Header -->
        <div class="w-full max-w-6xl flex items-center justify-between border-b border-slate-800 pb-4">
          <div class="flex items-center gap-3">
            <Presentation class="w-6 h-6 text-indigo-400" />
            <div>
              <h3 class="font-extrabold text-sm text-white">Mode Presentasi Slide PPT Vokasi</h3>
              <p class="text-xxs text-slate-400">{{ rpp.mata_pelajaran }} • {{ rpp.jurusan_smk }}</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-indigo-500/20 rounded-full text-indigo-300 text-xs font-bold border border-indigo-500/30">
              Slide {{ currentSlide + 1 }} dari {{ slides.length }}
            </span>
            <button @click="isPresentationMode = false" class="p-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white transition-all cursor-pointer">
              <X class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Center Slide View Container -->
        <div class="w-full max-w-5xl aspect-video bg-slate-900 border-2 border-indigo-500/40 rounded-3xl p-8 sm:p-12 shadow-2xl flex flex-col justify-between my-auto relative overflow-hidden">
          <div class="flex items-center justify-between text-xs font-bold text-amber-400">
            <span>{{ slides[currentSlide].badge }}</span>
            <span class="px-2.5 py-1 rounded bg-slate-950 border border-slate-800 text-slate-300 text-xxs font-mono">
              16:9 HD Presenter Mode
            </span>
          </div>

          <div class="space-y-4 my-auto">
            <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight tracking-tight uppercase">
              {{ slides[currentSlide].title }}
            </h2>
            <p class="text-sm sm:text-base text-indigo-200 font-medium leading-relaxed max-w-3xl">
              {{ slides[currentSlide].subtitle }}
            </p>
            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-xs text-emerald-300 font-bold max-w-xl">
              📌 {{ slides[currentSlide].meta }}
            </div>
          </div>

          <div class="flex items-center justify-between text-xxs text-slate-400 border-t border-slate-800 pt-3">
            <span>Industri Pasangan: {{ rpp.kemitraan_dudi }}</span>
            <span>KADU Deep Learning Engine</span>
          </div>
        </div>

        <!-- Modal Controls Navigation Bar -->
        <div class="w-full max-w-6xl flex items-center justify-between pt-4 border-t border-slate-800">
          <button 
            @click="prevSlide" 
            :disabled="currentSlide === 0"
            :class="['px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all', currentSlide === 0 ? 'bg-slate-900 text-slate-600 cursor-not-allowed' : 'bg-slate-800 hover:bg-slate-700 text-white cursor-pointer']"
          >
            <ChevronLeft class="w-4 h-4" /> Slide Sebelumnya
          </button>

          <div class="flex items-center gap-2">
            <button 
              v-for="(s, idx) in slides" 
              :key="idx" 
              @click="currentSlide = idx"
              :class="['w-3 h-3 rounded-full transition-all cursor-pointer', currentSlide === idx ? 'bg-indigo-500 w-8' : 'bg-slate-800 hover:bg-slate-700']"
            ></button>
          </div>

          <button 
            @click="nextSlide" 
            :disabled="currentSlide === slides.length - 1"
            :class="['px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all', currentSlide === slides.length - 1 ? 'bg-slate-900 text-slate-600 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-500 text-white cursor-pointer']"
          >
            Slide Selanjutnya <ChevronRight class="w-4 h-4" />
          </button>
        </div>
      </div>
    </Teleport>
  </div>
</template>
