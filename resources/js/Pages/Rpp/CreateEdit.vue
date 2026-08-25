<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
  Brain, 
  ArrowLeft, 
  ArrowRight, 
  BookOpen, 
  Wrench, 
  Sparkles, 
  Check, 
  CheckCircle,
  Cpu,
  Printer,
  Loader2,
  Coins,
  CreditCard,
  AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
  rpp: {
    type: Object,
    default: null
  },
  userTokens: {
    type: Number,
    default: 0
  }
});

const isEditing = computed(() => !!props.rpp);
const currentStep = ref(1);

const form = useForm({
  mata_pelajaran: props.rpp?.mata_pelajaran || 'Matematika',
  kelas_semester: props.rpp?.kelas_semester || 'X / Ganjil',
  alokasi_waktu: props.rpp?.alokasi_waktu || '3 JP (3 x 45 Menit)',
  jurusan_smk: props.rpp?.jurusan_smk || 'Teknik Kendaraan Ringan (TKR)',
  capaian_pembelajaran: props.rpp?.capaian_pembelajaran || 'Peserta didik mampu menerapkan sistem persamaan linier dan kalkulasi rasio presisi untuk memecahkan masalah teknis otomotif.',
  gaya_belajar: props.rpp?.gaya_belajar || ['Visual', 'Kinestetik'],
  karakteristik_fisik: props.rpp?.karakteristik_fisik || 'Non-Inklusi (Reguler)',
  model_pembelajaran: props.rpp?.model_pembelajaran || 'Project-Based Learning (PBL)',
  metode_pembelajaran: props.rpp?.metode_pembelajaran || 'Diskusi Kelompok, Simulasi, dan Praktik Bengkel',
  kemitraan_dudi: props.rpp?.kemitraan_dudi || 'Industri Pasangan DU/DI (PT. Astra Otoparts) & Guru Tamu Praktisi',
  ruang_fisik: props.rpp?.ruang_fisik || 'Bengkel Otomotif / Ruang Teori SMK',
  ruang_virtual: props.rpp?.ruang_virtual || 'LMS Google Classroom & WhatsApp Group Class',
  software_digital: props.rpp?.software_digital || 'Platform Merdeka Mengajar (PMM), Simulator Engine Scan, Canva',
  dimensi_profil: props.rpp?.dimensi_profil || [
    'Bernalar Kritis (Critical Thinking)',
    'Kreatif & Inovatif (Creativity)',
    'Gotong Royong & Kolaboratif (Collaboration)'
  ],
});

const toggleGayaBelajar = (val) => {
  const idx = form.gaya_belajar.indexOf(val);
  if (idx > -1) {
    form.gaya_belajar.splice(idx, 1);
  } else {
    form.gaya_belajar.push(val);
  }
};

const toggleDimensi = (val) => {
  const idx = form.dimensi_profil.indexOf(val);
  if (idx > -1) {
    form.dimensi_profil.splice(idx, 1);
  } else {
    form.dimensi_profil.push(val);
  }
};

const nextStep = () => {
  if (currentStep.value < 3) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
};

const submitForm = () => {
  if (isEditing.value) {
    form.put(route('rpps.update', props.rpp.id));
  } else {
    form.post(route('rpps.store'));
  }
};
</script>

<template>
  <AppLayout>
    <Head :title="isEditing ? 'Edit RPP Vokasi - KADU' : 'Generator RPP Deep Learning - KADU'" />

    <div class="space-y-8 text-left max-w-5xl mx-auto">
      <!-- Header Banner Card -->
      <div class="p-6 sm:p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="space-y-1">
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 rounded-full text-indigo-300 text-xxs font-bold border border-indigo-500/20 uppercase">
            <Sparkles class="w-3.5 h-3.5 text-indigo-400" />
            {{ isEditing ? 'Edit RPP Vokasi' : 'Wizard AI Generator RPP' }}
          </div>
          <h1 class="text-2xl font-black text-white">
            {{ isEditing ? 'Edit Perangkat Ajar & RPP' : 'Buat RPP & Modul Ajar Baru' }}
          </h1>
          <p class="text-xs text-slate-400">Isi formulir 4-langkah di bawah untuk merancang RPP utuh berbasis Deep Learning, Literasi & Numerasi Terapan.</p>
        </div>

        <Link :href="route('rpps.index')" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold rounded-2xl border border-slate-700 flex items-center gap-1.5 shrink-0">
          <ArrowLeft class="w-4 h-4" />
          Kembali ke Dashboard
        </Link>
      </div>
      <!-- 4-Step Wizard Indicator Bar -->
      <div class="grid grid-cols-4 gap-2 sm:gap-4">
        <button 
          @click="currentStep = 1"
          :class="['p-3 rounded-2xl border text-center transition-all flex flex-col items-center gap-1 cursor-pointer', currentStep === 1 ? 'bg-indigo-600 border-indigo-400 text-white shadow-lg' : 'bg-slate-900 border-slate-800 text-slate-400']"
        >
          <div :class="['w-7 h-7 rounded-full flex items-center justify-center font-black text-xs', currentStep === 1 ? 'bg-white text-indigo-700' : 'bg-slate-800 text-slate-400']">1</div>
          <span class="text-xxs font-bold uppercase tracking-wider hidden sm:block">Identitas & Siswa</span>
        </button>

        <button 
          @click="currentStep = 2"
          :class="['p-3 rounded-2xl border text-center transition-all flex flex-col items-center gap-1 cursor-pointer', currentStep === 2 ? 'bg-indigo-600 border-indigo-400 text-white shadow-lg' : 'bg-slate-900 border-slate-800 text-slate-400']"
        >
          <div :class="['w-7 h-7 rounded-full flex items-center justify-center font-black text-xs', currentStep === 2 ? 'bg-white text-indigo-700' : 'bg-slate-800 text-slate-400']">2</div>
          <span class="text-xxs font-bold uppercase tracking-wider hidden sm:block">Kerangka & Lingkungan</span>
        </button>

        <button 
          @click="currentStep = 3"
          :class="['p-3 rounded-2xl border text-center transition-all flex flex-col items-center gap-1 cursor-pointer', currentStep === 3 ? 'bg-indigo-600 border-indigo-400 text-white shadow-lg' : 'bg-slate-900 border-slate-800 text-slate-400']"
        >
          <div :class="['w-7 h-7 rounded-full flex items-center justify-center font-black text-xs', currentStep === 3 ? 'bg-white text-indigo-700' : 'bg-slate-800 text-slate-400']">3</div>
          <span class="text-xxs font-bold uppercase tracking-wider hidden sm:block">Profil & Generator</span>
        </button>

        <div class="p-3 rounded-2xl border border-slate-800 bg-slate-900/50 text-slate-500 text-center flex flex-col items-center gap-1">
          <div class="w-7 h-7 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center font-black text-xs">4</div>
          <span class="text-xxs font-bold uppercase tracking-wider hidden sm:block">Output & Cetak</span>
        </div>
      </div>

      <!-- Form Card Container -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8 backdrop-blur-xl">
        <!-- STEP 1: Identitas & Siswa -->
        <div v-if="currentStep === 1" class="space-y-6">
          <div class="border-b border-slate-800 pb-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
              <BookOpen class="w-5 h-5 text-indigo-400" />
              1. Identitas, Konteks & Karakteristik Peserta Didik
            </h2>
            <p class="text-xs text-slate-400 mt-1">Masukkan data mata pelajaran normatif/adaptif dan karakteristik awal siswa SMK Anda.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Mata Pelajaran</label>
              <select v-model="form.mata_pelajaran" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-3 text-xs font-semibold text-white">
                <option value="Matematika">Matematika Vokasi</option>
                <option value="Bahasa Indonesia">Bahasa Indonesia Vokasi</option>
                <option value="Bahasa Inggris">Bahasa Inggris Teknik</option>
                <option value="Fisika Terapan">Fisika Terapan Vokasi</option>
                <option value="Kimia Industri">Kimia Industri</option>
                <option value="Pemeliharaan Mesin">Pemeliharaan Mesin Otomotif</option>
                <option value="Pemrograman Web">Pemrograman Web & Perangkat Bergerak</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Kelas / Semester</label>
              <input v-model="form.kelas_semester" type="text" placeholder="X / Ganjil" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Alokasi Waktu (JP)</label>
              <select v-model="form.alokasi_waktu" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-3 text-xs font-semibold text-white">
                <option value="2 JP (2 x 45 Menit)">2 JP (2 x 45 Menit)</option>
                <option value="3 JP (3 x 45 Menit)">3 JP (3 x 45 Menit)</option>
                <option value="4 JP (4 x 45 Menit)">4 JP (4 x 45 Menit)</option>
                <option value="6 JP Blok Praktik">6 JP Blok Praktik Bengkel</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1.5">Konsentrasi Keahlian (Jurusan SMK)</label>
            <input v-model="form.jurusan_smk" type="text" placeholder="Teknik Kendaraan Ringan (TKR)" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1.5">Capaian Pembelajaran (CP)</label>
            <textarea v-model="form.capaian_pembelajaran" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs font-medium text-white"></textarea>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-2xl bg-slate-950 border border-slate-800">
            <div>
              <label class="block text-xs font-bold text-indigo-300 mb-2">Gaya Belajar Peserta Didik</label>
              <div class="space-y-2">
                <label v-for="gaya in ['Visual', 'Auditori', 'Kinestetik']" :key="gaya" class="flex items-center gap-2 cursor-pointer text-xs text-slate-200">
                  <input type="checkbox" :checked="form.gaya_belajar.includes(gaya)" @change="toggleGayaBelajar(gaya)" class="rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0" />
                  <span>{{ gaya }}</span>
                </label>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-indigo-300 mb-2">Karakteristik Fisik Siswa</label>
              <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-200">
                  <input type="radio" value="Non-Inklusi (Reguler)" v-model="form.karakteristik_fisik" class="text-indigo-600 bg-slate-900 border-slate-700" />
                  <span>Non-Inklusi (Reguler)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-200">
                  <input type="radio" value="Inklusi (Kebutuhan Khusus / Akomodatif)" v-model="form.karakteristik_fisik" class="text-indigo-600 bg-slate-900 border-slate-700" />
                  <span>Inklusi (Kebutuhan Khusus / Akomodatif)</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- STEP 2: Kerangka & Lingkungan -->
        <div v-if="currentStep === 2" class="space-y-6">
          <div class="border-b border-slate-800 pb-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
              <Wrench class="w-5 h-5 text-indigo-400" />
              2. Kerangka Pembelajaran, Kemitraan & Lingkungan
            </h2>
            <p class="text-xs text-slate-400 mt-1">Konfigurasikan metode pedagogik, mitra industri, dan pemanfaatan sarana digital Anda.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Praktik Pedagogik (Model Pembelajaran)</label>
              <input v-model="form.model_pembelajaran" type="text" placeholder="Project-Based Learning (PBL)" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Metode & Strategi Pembelajaran</label>
              <input v-model="form.metode_pembelajaran" type="text" placeholder="Diskusi Kelompok, Simulasi, dan Praktik Bengkel" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1.5">Kemitraan Pembelajaran (DU/DI / Komunitas)</label>
            <input v-model="form.kemitraan_dudi" type="text" placeholder="Industri Pasangan DU/DI (PT. Astra Otoparts) & Guru Tamu Praktisi" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Lingkungan Pembelajaran (Ruang Fisik)</label>
              <input v-model="form.ruang_fisik" type="text" placeholder="Bengkel Otomotif / Ruang Teori SMK" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 mb-1.5">Lingkungan Pembelajaran (Ruang Virtual)</label>
              <input v-model="form.ruang_virtual" type="text" placeholder="LMS Google Classroom & WhatsApp Group Class" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-300 mb-1.5">Pemanfaatan Digital / Software</label>
            <input v-model="form.software_digital" type="text" placeholder="Platform Merdeka Mengajar (PMM), Simulator Engine Scan, Canva" class="w-full h-11 bg-slate-950 border border-slate-800 rounded-xl px-4 text-xs font-semibold text-white" />
          </div>
        </div>

        <!-- STEP 3: Profil & Generator -->
        <div v-if="currentStep === 3" class="space-y-6">
          <div class="border-b border-slate-800 pb-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
              <Sparkles class="w-5 h-5 text-indigo-400" />
              3. Target Dimensi Profil Lulusan & Generasi AI
            </h2>
            <p class="text-xs text-slate-400 mt-1">Pilih dimensi karakter dan kompetensi kerja yang ingin ditumbuhkan pada siswa.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div 
              v-for="dimensi in [
                'Bernalar Kritis (Critical Thinking)',
                'Kreatif & Inovatif (Creativity)',
                'Gotong Royong & Kolaboratif (Collaboration)',
                'Mandiri & Perilaku Adaptif',
                'Berkebinekaan Global (Citizenship)',
                'Kompeten & Berstandar Industri (Vokasi)'
              ]"
              :key="dimensi"
              @click="toggleDimensi(dimensi)"
              :class="[
                'p-4 rounded-2xl border transition-all cursor-pointer flex items-center justify-between',
                form.dimensi_profil.includes(dimensi)
                  ? 'bg-indigo-950/60 border-indigo-500 text-white font-bold'
                  : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'
              ]"
            >
              <span class="text-xs">{{ dimensi }}</span>
              <div :class="['w-5 h-5 rounded-full border flex items-center justify-center text-xs', form.dimensi_profil.includes(dimensi) ? 'bg-indigo-600 border-indigo-400 text-white' : 'border-slate-700']">
                <Check v-if="form.dimensi_profil.includes(dimensi)" class="w-3.5 h-3.5" />
              </div>
            </div>
          </div>

          <!-- Warning Banner if User Tokens is 0 -->
          <div v-if="userTokens <= 0 && !$page.props.auth.user?.roles?.some(r => r.name === 'admin')" class="p-5 rounded-2xl bg-amber-500/15 border border-amber-500/40 space-y-3">
            <div class="flex items-center gap-2 text-amber-300 font-bold text-sm">
              <Coins class="w-5 h-5 text-amber-400" />
              <span>Kuota Token RPP Anda Kosong (0 Token)</span>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Anda membutuhkan minimal 1 Token RPP untuk meng-generate dokumen RPP AI Vokasi. Silakan isi paket token terlebih dahulu.
            </p>
            <Link :href="route('tokens.index')" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs rounded-xl shadow transition-all">
              <CreditCard class="w-4 h-4" />
              <span>Beli Paket Token Sekarang (Midtrans)</span>
            </Link>
          </div>

          <!-- Siap Menerapkan Banner -->
          <div v-else class="p-5 rounded-2xl bg-gradient-to-r from-indigo-950 via-slate-900 to-violet-950 border border-indigo-500/40 space-y-2">
            <div class="flex items-center gap-2 text-indigo-300 font-bold text-sm">
              <Cpu class="w-5 h-5 text-indigo-400 animate-pulse" />
              <span>Siap Menerapkan Deep Learning AI?</span>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Sistem akan memproses seluruh inputan Anda untuk menghasilkan RPP Utuh, Draft Media Pembelajaran, Script Video Pembelajaran, dan Ringkasan Materi yang kaya Literasi & Numerasi Terapan SMK.
            </p>
          </div>
        </div>

        <!-- Bottom Step Control Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-slate-800">
          <button 
            type="button" 
            @click="prevStep" 
            :disabled="currentStep === 1 || form.processing"
            class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 disabled:opacity-40 text-xs font-bold text-slate-200 rounded-xl transition-all"
          >
            ← Sebelumnya
          </button>

          <button 
            v-if="currentStep < 3"
            type="button" 
            @click="nextStep" 
            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-xs font-bold text-white rounded-xl shadow transition-all"
          >
            Selanjutnya →
          </button>

          <button 
            v-else
            type="button"
            @click="submitForm"
            :disabled="form.processing || (userTokens <= 0 && !$page.props.auth.user?.roles?.some(r => r.name === 'admin'))"
            class="px-7 py-3 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover:brightness-110 disabled:opacity-50 text-xs font-extrabold text-white rounded-xl shadow-lg shadow-indigo-600/30 active:scale-[0.98] transition-all cursor-pointer flex items-center gap-2"
          >
            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin text-white" />
            <Sparkles v-else class="w-4 h-4 text-amber-300" />
            <span>{{ form.processing ? 'Sedang Merancang RPP (AI)...' : 'Generate Dokumen AI ✨' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Fullscreen AI Synthesis Loading Overlay Modal -->
    <Teleport to="body">
      <div 
        v-if="form.processing" 
        class="fixed inset-0 z-[100] bg-slate-950/90 backdrop-blur-2xl flex flex-col items-center justify-center p-6 text-center space-y-6 animate-fade-in"
      >
        <!-- Glowing Animated AI Aura -->
        <div class="relative flex items-center justify-center">
          <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-500 via-violet-500 to-purple-500 animate-spin blur-md opacity-60"></div>
          <div class="absolute inset-2 bg-slate-950 rounded-full flex items-center justify-center border border-indigo-500/50 shadow-2xl">
            <Brain class="w-10 h-10 text-indigo-400 animate-pulse" />
          </div>
        </div>

        <div class="space-y-2 max-w-md">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-indigo-500/10 rounded-full text-indigo-300 text-xs font-bold border border-indigo-500/30 uppercase tracking-wider">
            <Sparkles class="w-3.5 h-3.5 text-amber-400 animate-spin" />
            Google Gemini AI Sintesis Deep Learning
          </div>
          <h3 class="text-2xl font-black text-white tracking-tight">Merancang Perangkat Ajar & RPP Vokasi...</h3>
          <p class="text-xs text-slate-300 leading-relaxed">
            Mohon tunggu sebentar. AI sedang menyusun RPP Utuh, Draft Media Pembelajaran Visual, Script Video 3D, dan Ringkasan Materi Literasi & Numerasi Terapan SMK.
          </p>
        </div>

        <!-- Animated Progress Pill -->
        <div class="w-full max-w-xs bg-slate-900 border border-slate-800 rounded-full h-2 overflow-hidden relative">
          <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 rounded-full w-full animate-pulse"></div>
        </div>

        <span class="text-xxs text-slate-400 font-semibold tracking-wide uppercase">⚡ Memerlukan waktu ~5-10 detik • Mohon jangan menutup halaman</span>
      </div>
    </Teleport>
  </AppLayout>
</template>
