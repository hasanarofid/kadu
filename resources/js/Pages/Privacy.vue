<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
  Brain, 
  ShieldCheck, 
  Lock, 
  Eye, 
  ArrowLeft, 
  CheckCircle2, 
  Database,
  Server
} from 'lucide-vue-next';

defineProps({
  settings: {
    type: Object,
    default: () => ({})
  }
});
</script>

<template>
  <Head title="Kebijakan Privasi (Privacy Policy) - KADU (Karsa Edukasi Vokasi)" />

  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-indigo-500 selection:text-white relative overflow-x-hidden">
    <!-- Ambient Background Lighting -->
    <div class="absolute top-[-10%] left-[-10%] w-[700px] h-[700px] rounded-full bg-indigo-600/15 blur-[140px] pointer-events-none"></div>
    <div class="absolute top-[40%] right-[-10%] w-[600px] h-[600px] rounded-full bg-violet-600/15 blur-[140px] pointer-events-none"></div>

    <!-- Header Navigation -->
    <nav class="sticky top-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-indigo-900/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        <Link href="/" class="flex items-center gap-3 group">
          <div class="p-2.5 bg-gradient-to-tr from-indigo-600 via-violet-600 to-purple-500 rounded-2xl shadow-lg shadow-indigo-600/30 group-hover:scale-105 transition-transform duration-300">
            <Brain class="w-6 h-6 text-white" />
          </div>
          <div class="flex flex-col text-left">
            <span class="font-black text-xl tracking-tight text-white group-hover:text-indigo-300 transition-colors">
              KADU
            </span>
            <span class="text-xxs text-slate-400 font-medium tracking-wide">Karsa Edukasi Vokasi</span>
          </div>
        </Link>

        <Link 
          href="/" 
          class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-850 text-slate-300 hover:text-white border border-slate-800 rounded-xl text-xs font-bold transition-all"
        >
          <ArrowLeft class="w-4 h-4" />
          <span>Kembali ke Beranda</span>
        </Link>
      </div>
    </nav>

    <!-- Main Content Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-12 md:py-16 text-left space-y-8">
      <!-- Title Card -->
      <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-2xl space-y-3 relative overflow-hidden backdrop-blur-md">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-purple-500/10 rounded-full text-purple-300 text-xs font-bold border border-purple-500/20 uppercase tracking-wider">
          <ShieldCheck class="w-4 h-4 text-purple-400" />
          Data Protection & Privacy Policy
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Kebijakan Privasi (Privacy Policy)</h1>
        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
          KADU (Karsa Edukasi Vokasi) menghargai privasi dan berkomitmen penuh melindungi seluruh data pribadi serta keamanan transaksi Pengguna.
        </p>
      </div>

      <!-- Privacy Content Body -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8 backdrop-blur-md leading-relaxed text-slate-300 text-xs sm:text-sm">
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <Database class="w-4 h-4 text-indigo-400" /> 1. Pengumpulan Informasi Pribadi
          </h2>
          <p>
            Kami mengumpulkan data yang Anda berikan secara sukarela saat mendaftar akun atau menggunakan layanan kami, antara lain:
          </p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Data Akun: Nama Lengkap, Username, Alamat Email, No. Telepon / WhatsApp.</li>
            <li>Data OAuth SSO: Nama dan Foto Profil yang diberikan oleh otentikasi Google Login.</li>
            <li>Data Transaksi: Riwayat mutasi kuota token dan status transaksi pembayaran via Midtrans.</li>
          </ul>
        </section>

        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <Lock class="w-4 h-4 text-violet-400" /> 2. Keamanan Transaksi Finansial (Midtrans Integration)
          </h2>
          <p>
            Platform KADU **TIDAK PERNAH** menyimpan informasi kartu kredit, PIN, atau kredensial perbankan Anda secara lokal di server kami. Seluruh proses checkout pembayaran diproses secara terenkripsi langsung oleh SDK **Midtrans Payment Gateway (PT. Midtrans)** sesuai standar keamanan industri finansial PCI-DSS.
          </p>
        </section>

        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <Server class="w-4 h-4 text-purple-400" /> 3. Penggunaan & Perlindungan Data
          </h2>
          <p>
            Data Pengguna digunakan khusus untuk kepentingan:
          </p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Memproses pembuatan RPP / Modul Ajar Vokasi berbasis AI.</li>
            <li>Mengirimkan email konfirmasi transaksi, notifikasi verifikasi akun, dan receipt bukti pembayaran.</li>
            <li>Menjaga keamanan akun dan mencegah akses tidak sah atau tindakan pembobolan.</li>
          </ul>
          <p class="pt-1">
            Kami menjamin **TIDAK AKAN MENJUAL, MENYEWAKAN, ATAU MEMBAGIKAN** data pribadi Pengguna kepada pihak ketiga mana pun tanpa persetujuan tertulis dari Pengguna, kecuali diwajibkan oleh ketentuan hukum Republik Indonesia yang sah.
          </p>
        </section>

        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <Eye class="w-4 h-4 text-emerald-400" /> 4. Kontak Pengelola & Hak Pengguna
          </h2>
          <p>
            Pengguna berhak untuk memperbarui data profil, mengubah kata sandi, atau meminta pembaruan data kapan saja melalui halaman Pengaturan Profil atau menghubungi tim pengelola KADU via email: 
            <a href="mailto:kadu@andibakhtiar.com" class="text-indigo-400 font-bold underline">kadu@andibakhtiar.com</a>.
          </p>
        </section>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-8 text-slate-500 text-xs text-center">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p>&copy; {{ new Date().getFullYear() }} KADU (Karsa Edukasi Vokasi). All rights reserved.</p>
        <div class="flex items-center gap-6">
          <Link href="/terms" class="text-slate-400 hover:text-white transition-colors">Syarat & Ketentuan</Link>
          <Link href="/privacy" class="text-indigo-400 font-bold hover:underline">Kebijakan Privasi</Link>
        </div>
      </div>
    </footer>
  </div>
</template>
