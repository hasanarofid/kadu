<script setup>
import { ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
  Brain, 
  Sparkles, 
  BookOpen, 
  Layers, 
  Zap, 
  ShieldCheck, 
  CreditCard, 
  CheckCircle, 
  ChevronRight, 
  ArrowRight, 
  Printer, 
  FileText, 
  Video, 
  GraduationCap, 
  Wrench, 
  Cpu, 
  Lock, 
  RefreshCw, 
  Check, 
  HelpCircle,
  Phone
} from 'lucide-vue-next';

const props = defineProps({
  settings: {
    type: Object,
    default: () => ({})
  },
  navigation: {
    type: Array,
    default: () => []
  }
});

const pageData = usePage();
const user = pageData.props.auth?.user;

// Active Step state for Interactive Wizard Showcase
const activeWizardStep = ref(1);

const wizardSteps = [
  {
    id: 1,
    title: '1. Identitas & Siswa',
    desc: 'Input Mapel, Jurusan SMK (mis. TKR), Capaian Pembelajaran, & Gaya Belajar Siswa',
    icon: BookOpen,
    badge: 'Karakteristik Vokasi'
  },
  {
    id: 2,
    title: '2. Kerangka & Lingkungan',
    desc: 'Model Pembelajaran (PBL), DU/DI Industri Pasangan, Bengkel & Software Simulator',
    icon: Wrench,
    badge: 'Ekosistem Bengkel'
  },
  {
    id: 3,
    title: '3. Profil & Generator AI',
    desc: 'Target Dimensi Profil Pelajar Pancasila & Vokasi + Sintesis AI Otomatis',
    icon: Sparkles,
    badge: 'Deep Learning Engine'
  },
  {
    id: 4,
    title: '4. Output & Cetak PDF',
    desc: 'Export RPP / Modul Ajar, Media Pembelajaran, Video Script Prompt & PDF',
    icon: Printer,
    badge: 'Dokumen Ready-to-Print'
  }
];

// Payment Gateway Flow Steps
const pgSteps = [
  {
    step: '01',
    title: 'Checkout & Tokenisasi',
    role: 'Frontend ke Gateway',
    desc: 'Pelanggan memilih paket voucher RPP. Data sensitif ditokenisasi di client-side demi standar keamanan PCI-DSS.'
  },
  {
    step: '02',
    title: 'Otentikasi & Request API',
    role: 'Backend ke Gateway',
    desc: 'Backend Laravel memvalidasi pesanan dan mengirimkan request transaksi (Create Invoice) menggunakan Secret Key.'
  },
  {
    step: '03',
    title: 'Otorisasi Finansial',
    role: 'Gateway ke Bank / E-Wallet',
    desc: 'Payment Gateway mengeksekusi otorisasi ke bank/e-wallet (QRIS, VA, OVO, GoPay) & pengecekan anti-penipuan.'
  },
  {
    step: '04',
    title: 'Respon Asinkron & Webhook',
    role: 'Gateway ke Backend',
    desc: 'Webhook otomatis menembak callback ke endpoint Laravel. Controller idempoten mengubah status pesanan "Lunas" & kirim resi email.'
  },
  {
    step: '05',
    title: 'Settlement Pencairan',
    role: 'Pencairan Merchant',
    desc: 'Dana pelanggan dicairkan otomatis oleh payment gateway ke rekening bank utama merchant sesuai jadwal.'
  }
];

const faqs = [
  {
    q: 'Apa itu KADU (Karsa Edukasi Vokasi) - EduDeep SMK?',
    a: 'KADU adalah platform generator RPP dan Modul Ajar SMK berbasis kecerdasan buatan (AI) yang disesuaikan dengan pendekatan Deep Learning, Literasi & Numerasi Terapan Vokasi, serta Kurikulum Merdeka.'
  },
  {
    q: 'Apakah RPP yang dihasilkan sesuai dengan standar Industri dan SMK?',
    a: 'Sangat sesuai! KADU mengintegrasikan model Pembelajaran Berbasis Proyek (PBL), Kemitraan DU/DI (Dunia Usaha & Dunia Industri), serta penggunaan alat simulator & bengkel vokasi secara otomatis.'
  },
  {
    q: 'Metode pembayaran apa saja yang didukung oleh Payment Gateway KADU?',
    a: 'Sistem mendukung QRIS otomatis (GoPay, OVO, ShopeePay, DANA), Virtual Account seluruh bank nasional (BCA, Mandiri, BRI, BNI), serta transfer manual terverifikasi.'
  },
  {
    q: 'Bagaimana keandalan Webhook Payment Gateway saat koneksi pelanggan terputus?',
    a: 'Backend Laravel KADU menggunakan sistem Webhook Handler idempoten. Status pembayaran tetap terproses otomatis menjadi Lunas walaupun pelanggan menutup browser sebelum diarahkan kembali ke aplikasi.'
  }
];
</script>

<template>
  <Head title="KADU - Generator RPP Deep Learning Vokasi SMK & Payment Gateway" />

  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">
    <!-- Ambient Background Lighting -->
    <div class="absolute top-[-10%] left-[-10%] w-[700px] h-[700px] rounded-full bg-indigo-600/15 blur-[140px] pointer-events-none"></div>
    <div class="absolute top-[30%] right-[-10%] w-[600px] h-[600px] rounded-full bg-violet-600/15 blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-[-5%] left-[20%] w-[800px] h-[500px] rounded-full bg-purple-600/10 blur-[160px] pointer-events-none"></div>

    <!-- Glassmorphic Navbar -->
    <nav class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-indigo-900/30 transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        <!-- Logo -->
        <Link href="/" class="flex items-center gap-3 group">
          <div class="p-2.5 bg-gradient-to-tr from-indigo-600 via-violet-600 to-purple-500 rounded-2xl shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform duration-300">
            <Brain class="w-6 h-6 text-white" />
          </div>
          <div class="flex flex-col">
            <span class="font-black text-xl tracking-tight text-white group-hover:text-indigo-300 transition-colors">
              KADU
            </span>
            <span class="text-xxs text-slate-400 font-medium tracking-wide">Karsa Edukasi</span>
          </div>
        </Link>

        <!-- Nav Links -->
        <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-300">
          <a href="#fitur" class="hover:text-indigo-400 transition-colors">Fitur Utama</a>
          <a href="#wizard" class="hover:text-indigo-400 transition-colors">Wizard RPP AI</a>
          <a href="#payment-gateway" class="hover:text-indigo-400 transition-colors">Payment Gateway</a>
          <a href="#faq" class="hover:text-indigo-400 transition-colors">FAQ</a>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
          <Link 
            v-if="user" 
            :href="route('dashboard')" 
            class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-xs font-extrabold text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all"
          >
            <Sparkles class="w-4 h-4 mr-1.5" />
            Dashboard RPP
          </Link>
          <template v-else>
            <Link 
              :href="route('login')" 
              class="px-4 py-2 text-xs font-bold text-slate-300 hover:text-white transition-colors"
            >
              Masuk
            </Link>
            <Link 
              :href="route('login')" 
              class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 text-xs font-extrabold text-white rounded-xl shadow-lg shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all"
            >
              Coba Generator RPP
              <ArrowRight class="w-3.5 h-3.5 ml-1.5" />
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-12 pb-20 md:pt-20 md:pb-28">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <!-- Text Column -->
        <div class="lg:col-span-7 space-y-7 text-left">
          <div class="inline-flex items-center gap-2.5 px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/25 rounded-full text-indigo-300 text-xs font-bold shadow-inner">
            <Sparkles class="w-4 h-4 text-indigo-400 animate-spin" style="animation-duration: 6s;" />
            <span>AI Generator RPP Deep Learning, Literasi & Numerasi SMK</span>
          </div>

          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.12]">
            Revolusi Penyusunan <br />
            <span class="bg-gradient-to-r from-indigo-300 via-violet-300 to-purple-400 bg-clip-text text-transparent">
              RPP & Modul Ajar Vokasi
            </span>
          </h1>

          <p class="text-base sm:text-lg text-slate-300 font-normal leading-relaxed max-w-2xl">
            Sistem cerdas **KADU (Karsa Edukasi Vokasi)** membantu guru dan konsentrasi keahlian SMK menyusun RPP utuh, skrip media pembelajaran, dan modul berbasis **Deep Learning** serta integrasi **Payment Gateway** otomatis.
          </p>

          <!-- Badges Grid -->
          <div class="grid grid-cols-3 gap-3 max-w-xl pt-1">
            <div class="p-3 rounded-2xl bg-slate-900/80 border border-indigo-900/50 flex flex-col items-center text-center">
              <GraduationCap class="w-5 h-5 text-indigo-400 mb-1" />
              <span class="text-xs font-bold text-white">Standar Vokasi</span>
              <span class="text-xxs text-slate-400">Kurikulum Merdeka</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-900/80 border border-indigo-900/50 flex flex-col items-center text-center">
              <Wrench class="w-5 h-5 text-violet-400 mb-1" />
              <span class="text-xs font-bold text-white">Praktik Bengkel</span>
              <span class="text-xxs text-slate-400">DU/DI & Guru Tamu</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-900/80 border border-indigo-900/50 flex flex-col items-center text-center">
              <ShieldCheck class="w-5 h-5 text-purple-400 mb-1" />
              <span class="text-xs font-bold text-white">Payment Webhook</span>
              <span class="text-xxs text-slate-400">Otomatis & Safe</span>
            </div>
          </div>

          <!-- CTAs -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
            <Link 
              :href="route('login')" 
              class="inline-flex items-center justify-center px-7 py-4 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 text-sm font-extrabold text-white rounded-2xl shadow-xl shadow-indigo-600/35 hover:scale-[1.02] active:scale-[0.98] transition-all"
            >
              <Zap class="w-4 h-4 mr-2 text-yellow-300" />
              Mulai Buat RPP Vokasi
            </Link>

            <a 
              href="#wizard" 
              class="inline-flex items-center justify-center px-6 py-4 bg-slate-900/90 hover:bg-slate-850 border border-indigo-900/60 hover:border-indigo-500/50 text-sm font-bold text-slate-200 rounded-2xl hover:scale-[1.02] transition-all"
            >
              <BookOpen class="w-4 h-4 mr-2 text-indigo-400" />
              Lihat Demo 4-Step Wizard
            </a>
          </div>
        </div>

        <!-- Interactive Visual / Preview Mockup -->
        <div class="lg:col-span-5 relative flex justify-center">
          <!-- Outer Neon Border Card -->
          <div class="w-full max-w-md rounded-3xl bg-slate-900/90 border border-indigo-500/30 p-5 shadow-2xl shadow-indigo-950/80 relative backdrop-blur-md">
            <!-- Card Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                  <Brain class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="text-xs font-bold text-white">KADU (Karsa Edukasi Vokasi)</h4>
                  <p class="text-xxs text-indigo-300">Normatif & Adaptif Edition</p>
                </div>
              </div>
              <span class="text-xxs px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold">
                ✓ AI Ready
              </span>
            </div>

            <!-- Step Indicator Mini Bar -->
            <div class="grid grid-cols-4 gap-1.5 my-4">
              <div class="h-1.5 rounded-full bg-indigo-500"></div>
              <div class="h-1.5 rounded-full bg-indigo-500"></div>
              <div class="h-1.5 rounded-full bg-violet-500"></div>
              <div class="h-1.5 rounded-full bg-purple-500"></div>
            </div>

            <!-- Mockup Form Container -->
            <div class="space-y-3.5 bg-slate-950/80 p-4 rounded-2xl border border-slate-800 text-left">
              <div class="space-y-1">
                <label class="text-xxs font-bold text-indigo-300 uppercase tracking-wider">Mata Pelajaran</label>
                <div class="px-3 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs font-semibold text-white">
                  Matematika Vokasi (3 JP x 45 Menit)
                </div>
              </div>

              <div class="space-y-1">
                <label class="text-xxs font-bold text-indigo-300 uppercase tracking-wider">Konsentrasi Keahlian (Jurusan SMK)</label>
                <div class="px-3 py-2 bg-indigo-950/50 border border-indigo-800/60 rounded-xl text-xs font-bold text-indigo-200">
                  Teknik Kendaraan Ringan (TKR)
                </div>
              </div>

              <div class="space-y-1">
                <label class="text-xxs font-bold text-indigo-300 uppercase tracking-wider">Capaian Pembelajaran (CP)</label>
                <div class="p-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xxs font-medium text-slate-300 leading-relaxed">
                  Peserta didik mampu menerapkan sistem persamaan linier dan kalkulasi rasio presisi untuk memecahkan masalah teknis otomotif.
                </div>
              </div>

              <!-- Styles / Tags -->
              <div class="flex items-center justify-between pt-1">
                <span class="text-xxs font-bold text-slate-400">Gaya Belajar:</span>
                <div class="flex items-center gap-1.5">
                  <span class="text-xxs px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Visual</span>
                  <span class="text-xxs px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 border border-purple-500/30">Kinestetik</span>
                </div>
              </div>
            </div>

            <!-- Action Mockup Button -->
            <div class="mt-4 flex items-center justify-between">
              <span class="text-xxs text-slate-400 font-medium">Model: Project-Based Learning (PBL)</span>
              <button class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 text-xs font-bold text-white rounded-xl shadow-md">
                Generate AI ✨
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Interactive 4-Step RPP Wizard Showcase -->
    <section id="wizard" class="py-24 border-t border-slate-900 bg-slate-950/60 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
          <span class="text-xs font-extrabold text-indigo-400 uppercase tracking-widest px-3 py-1 bg-indigo-500/10 rounded-full border border-indigo-500/20">
            Alur Kerja Sistem Generator
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Wizard 4 Langkah Pembuatan RPP Vokasi
          </h2>
          <p class="text-sm text-slate-400 leading-relaxed">
            Didesain presisi sesuai struktur Kurikulum Merdeka SMK untuk menghasilkan perangkat ajar utuh berbasis Deep Learning.
          </p>
        </div>

        <!-- Wizard Nav Tabs -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
          <button 
            v-for="step in wizardSteps" 
            :key="step.id"
            @click="activeWizardStep = step.id"
            :class="[
              'p-5 rounded-2xl border transition-all text-left flex flex-col justify-between cursor-pointer',
              activeWizardStep === step.id 
                ? 'bg-gradient-to-br from-indigo-900/60 via-slate-900 to-slate-950 border-indigo-500 shadow-xl shadow-indigo-950/50 scale-[1.02]' 
                : 'bg-slate-900/40 border-slate-800 hover:border-slate-700 hover:bg-slate-900/70'
            ]"
          >
            <div class="flex items-center justify-between mb-3">
              <div :class="['p-2.5 rounded-xl', activeWizardStep === step.id ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400']">
                <component :is="step.icon" class="w-5 h-5" />
              </div>
              <span class="text-xxs font-bold px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                {{ step.badge }}
              </span>
            </div>
            <div>
              <h4 :class="['text-sm font-bold', activeWizardStep === step.id ? 'text-white' : 'text-slate-300']">
                {{ step.title }}
              </h4>
              <p class="text-xxs text-slate-400 line-clamp-2 mt-1">
                {{ step.desc }}
              </p>
            </div>
          </button>
        </div>

        <!-- Active Step Details Card -->
        <div class="bg-slate-900/90 border border-indigo-900/50 rounded-3xl p-6 sm:p-10 shadow-2xl text-left backdrop-blur-md">
          <!-- Step 1 Details -->
          <div v-if="activeWizardStep === 1" class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
              <div>
                <span class="text-xs font-bold text-indigo-400">Langkah Pertama</span>
                <h3 class="text-2xl font-bold text-white mt-1">1. Identitas, Konteks & Karakteristik Peserta Didik</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-bold">
                Input Awal Guru
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                  <h4 class="text-xs font-bold text-indigo-300">Form Data Pembelajaran</h4>
                  <ul class="space-y-2 text-xs text-slate-300">
                    <li class="flex items-center gap-2"><CheckCircle class="w-4 h-4 text-indigo-400" /> Mata Pelajaran (mis. Matematika, Pemeliharaan Mesin)</li>
                    <li class="flex items-center gap-2"><CheckCircle class="w-4 h-4 text-indigo-400" /> Kelas / Semester & Alokasi Waktu (JP)</li>
                    <li class="flex items-center gap-2"><CheckCircle class="w-4 h-4 text-indigo-400" /> Konsentrasi Keahlian (Teknik Kendaraan Ringan / TKR)</li>
                  </ul>
                </div>
              </div>

              <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                  <h4 class="text-xs font-bold text-indigo-300">Karakteristik & Gaya Belajar</h4>
                  <ul class="space-y-2 text-xs text-slate-300">
                    <li class="flex items-center gap-2"><CheckCircle class="w-4 h-4 text-violet-400" /> Deskripsi Capaian Pembelajaran (CP) Spek Vokasi</li>
                    <li class="flex items-center gap-2"><CheckCircle class="w-4 h-4 text-violet-400" /> Gaya Belajar: Visual, Auditori, Kinestetik</li>
                    <li class="flex items-center gap-2"><CheckCircle class="w-4 h-4 text-violet-400" /> Karakteristik Fisik: Reguler / Inklusi Akomodatif</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 2 Details -->
          <div v-if="activeWizardStep === 2" class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
              <div>
                <span class="text-xs font-bold text-violet-400">Langkah Kedua</span>
                <h3 class="text-2xl font-bold text-white mt-1">2. Kerangka Pembelajaran, Kemitraan & Lingkungan</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-violet-500/20 text-violet-300 border border-violet-500/30 text-xs font-bold">
                Konstruksi Ekosistem
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                <h4 class="text-xs font-bold text-violet-300">Model & Strategi</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                  Project-Based Learning (PBL), Diskusi Kelompok, Simulasi Praktik, dan Problem-Solving Bengkel Otomotif.
                </p>
              </div>

              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                <h4 class="text-xs font-bold text-violet-300">Kemitraan DU/DI</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                  Integrasi Industri Pasangan DU/DI (PT Astra Otoparts, dll) dan jadwal Guru Tamu Praktisi Industri.
                </p>
              </div>

              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                <h4 class="text-xs font-bold text-violet-300">Ruang & Software</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                  Ruang Teori & Bengkel SMK, LMS Google Classroom, Simulator Engine Scan, Canva, & PMM.
                </p>
              </div>
            </div>
          </div>

          <!-- Step 3 Details -->
          <div v-if="activeWizardStep === 3" class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
              <div>
                <span class="text-xs font-bold text-purple-400">Langkah Ketiga</span>
                <h3 class="text-2xl font-bold text-white mt-1">3. Target Dimensi Profil Lulusan & Generasi AI</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 border border-purple-500/30 text-xs font-bold">
                Deep Learning Engine
              </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-indigo-400 shrink-0" />
                <span class="text-xs font-semibold text-slate-200">Bernalar Kritis (Critical Thinking)</span>
              </div>
              <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-indigo-400 shrink-0" />
                <span class="text-xs font-semibold text-slate-200">Kreatif & Inovatif (Creativity)</span>
              </div>
              <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-indigo-400 shrink-0" />
                <span class="text-xs font-semibold text-slate-200">Gotong Royong & Kolaboratif</span>
              </div>
              <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-indigo-400 shrink-0" />
                <span class="text-xs font-semibold text-slate-200">Mandiri & Adaptif</span>
              </div>
              <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-indigo-400 shrink-0" />
                <span class="text-xs font-semibold text-slate-200">Berkebinekaan Global</span>
              </div>
              <div class="p-3.5 rounded-2xl bg-indigo-950/60 border border-indigo-700/60 flex items-center gap-3">
                <CheckCircle class="w-5 h-5 text-purple-400 shrink-0" />
                <span class="text-xs font-bold text-white">Kompeten Vokasi Industri</span>
              </div>
            </div>
          </div>

          <!-- Step 4 Details -->
          <div v-if="activeWizardStep === 4" class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
              <div>
                <span class="text-xs font-bold text-emerald-400">Langkah Keempat</span>
                <h3 class="text-2xl font-bold text-white mt-1">4. Output Multiformat & Cetak Dokumen PDF</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold">
                Dokumen SIAP PAKAI
              </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                <FileText class="w-6 h-6 text-indigo-400 mx-auto" />
                <h5 class="text-xs font-bold text-white">RPP / Modul Ajar</h5>
                <p class="text-xxs text-slate-400">Mindful, Meaningful, Joyful</p>
              </div>
              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                <Layers class="w-6 h-6 text-violet-400 mx-auto" />
                <h5 class="text-xs font-bold text-white">Media Pembelajaran</h5>
                <p class="text-xxs text-slate-400">Slide & Infografis Praktik</p>
              </div>
              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                <Video class="w-6 h-6 text-purple-400 mx-auto" />
                <h5 class="text-xs font-bold text-white">Video Script & Prompt</h5>
                <p class="text-xxs text-slate-400">Script AI & Video Tutorial</p>
              </div>
              <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-2">
                <Printer class="w-6 h-6 text-emerald-400 mx-auto" />
                <h5 class="text-xs font-bold text-white">Cetak Dokumen PDF</h5>
                <p class="text-xxs text-slate-400">Export PDF Standar Resmi</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Payment Gateway Integration PRD Section -->
    <section id="payment-gateway" class="py-24 border-t border-slate-900 relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
          <span class="text-xs font-extrabold text-purple-400 uppercase tracking-widest px-3 py-1 bg-purple-500/10 rounded-full border border-purple-500/20">
            Arsitektur Payment Gateway
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Alur Transaksi & Webhook Handshake
          </h2>
          <p class="text-sm text-slate-400 leading-relaxed">
            Integrasi pembayaran aman antara Frontend, Backend Laravel, dan Jaringan Finansial Pihak Ketiga.
          </p>
        </div>

        <!-- 5 Steps Flow Horizontal Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-12">
          <div 
            v-for="item in pgSteps" 
            :key="item.step"
            class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 space-y-3 hover:border-purple-500/50 hover:bg-slate-900 transition-all text-left flex flex-col justify-between group"
          >
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <span class="text-xs font-black text-purple-400 font-mono">{{ item.step }}</span>
                <CreditCard class="w-4 h-4 text-slate-500 group-hover:text-purple-400 transition-colors" />
              </div>
              <h4 class="text-sm font-bold text-white group-hover:text-purple-300 transition-colors">
                {{ item.title }}
              </h4>
              <span class="inline-block text-xxs font-bold text-indigo-300 px-2 py-0.5 rounded bg-indigo-500/10 border border-indigo-500/20">
                {{ item.role }}
              </span>
              <p class="text-xxs text-slate-400 leading-relaxed">
                {{ item.desc }}
              </p>
            </div>
          </div>
        </div>

        <!-- Key Insight Box -->
        <div class="p-6 rounded-3xl bg-gradient-to-r from-purple-950/80 via-slate-900 to-indigo-950/80 border border-purple-500/30 text-left flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-2xl">
          <div class="flex items-start gap-4">
            <div class="p-3 bg-purple-600/20 text-purple-400 rounded-2xl border border-purple-500/30 shrink-0">
              <Lock class="w-6 h-6" />
            </div>
            <div class="space-y-1">
              <h4 class="text-sm font-bold text-white flex items-center gap-2">
                <span>Key Insight: Penanganan Webhook Asinkron</span>
                <span class="text-xxs px-2 py-0.5 rounded bg-yellow-500/20 text-yellow-300 border border-yellow-500/30 font-bold">Krusial</span>
              </h4>
              <p class="text-xs text-slate-300 leading-relaxed max-w-3xl">
                Server backend Laravel KADU dirancang idempoten. Penanganan Webhook (Langkah 4) memastikan pesanan langsung aktif menjadi **"Lunas"** walaupun pelanggan mematikan aplikasi sebelum halaman sukses terbuka.
              </p>
            </div>
          </div>

          <Link 
            :href="route('login')" 
            class="px-5 py-3 bg-purple-600 hover:bg-purple-500 text-xs font-bold text-white rounded-xl shadow-lg shrink-0"
          >
            Uji Coba Pembayaran
          </Link>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 border-t border-slate-900 bg-slate-950/40 relative">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 text-left">
        <div class="text-center space-y-3 mb-16">
          <span class="text-xs font-extrabold text-indigo-400 uppercase tracking-widest px-3 py-1 bg-indigo-500/10 rounded-full border border-indigo-500/20">
            Pertanyaan Umum
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            Frequently Asked Questions
          </h2>
        </div>

        <div class="space-y-4">
          <div 
            v-for="(faq, idx) in faqs" 
            :key="idx"
            class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-indigo-500/40 transition-all space-y-2"
          >
            <h4 class="text-base font-bold text-white flex items-center gap-2">
              <HelpCircle class="w-4 h-4 text-indigo-400 shrink-0" />
              <span>{{ faq.q }}</span>
            </h4>
            <p class="text-xs text-slate-300 leading-relaxed pl-6">
              {{ faq.a }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-12 text-slate-500 text-sm relative z-10 text-left">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
          <!-- Col 1 -->
          <div class="md:col-span-2 space-y-3">
            <div class="flex items-center gap-2.5">
              <div class="p-2 bg-indigo-600 rounded-xl text-white">
                <Brain class="w-5 h-5" />
              </div>
              <span class="font-black text-lg text-white">KADU (Karsa Edukasi Vokasi)</span>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
              Platform AI Generator RPP Deep Learning, Literasi & Numerasi Vokasi SMK Terintegrasi Payment Gateway.
            </p>
          </div>

          <!-- Col 2 -->
          <div class="space-y-3">
            <h5 class="text-xs font-bold text-slate-300 uppercase tracking-wider">Tautan Cepat</h5>
            <ul class="space-y-2 text-xs text-slate-400">
              <li><a href="#fitur" class="hover:text-white">Fitur Utama</a></li>
              <li><a href="#wizard" class="hover:text-white">Wizard RPP AI</a></li>
              <li><a href="#payment-gateway" class="hover:text-white">Payment Gateway</a></li>
              <li><Link :href="route('login')" class="hover:text-white">Login Dashboard</Link></li>
            </ul>
          </div>

          <!-- Col 3 -->
          <div class="space-y-3">
            <h5 class="text-xs font-bold text-slate-300 uppercase tracking-wider">Kontak & Domain</h5>
            <ul class="space-y-2 text-xs text-slate-400">
              <li>Domain Live: <a href="https://kadu.andibakhtiar.com/" target="_blank" class="text-indigo-400 underline">kadu.andibakhtiar.com</a></li>
              <li class="flex items-center gap-1.5"><Phone class="w-3.5 h-3.5 text-indigo-400" /> +62 812-3456-7890</li>
            </ul>
          </div>
        </div>

        <div class="pt-8 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
          <p>&copy; {{ new Date().getFullYear() }} KADU (Karsa Edukasi Vokasi). All rights reserved.</p>
          <p>
            Developed & Owned by: 
            <a href="https://hasanarofid.site" target="_blank" class="text-indigo-400 font-bold hover:underline">
              @hasanarofid.site
            </a>
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
