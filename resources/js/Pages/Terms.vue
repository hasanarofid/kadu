<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { 
  Brain, 
  ShieldCheck, 
  FileText, 
  Lock, 
  Coins, 
  HelpCircle, 
  ArrowLeft, 
  CheckCircle2, 
  AlertTriangle,
  Scale
} from 'lucide-vue-next';

defineProps({
  settings: {
    type: Object,
    default: () => ({})
  }
});
</script>

<template>
  <Head title="Syarat & Ketentuan Layanan (Terms & Conditions) - KADU (Karsa Edukasi Vokasi)" />

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
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-indigo-500/10 rounded-full text-indigo-300 text-xs font-bold border border-indigo-500/20 uppercase tracking-wider">
          <Scale class="w-4 h-4 text-indigo-400" />
          Legal Notice & Compliance Standard (Midtrans Verified)
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Syarat & Ketentuan Layanan (Terms & Conditions)</h1>
        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
          Terakhir Diperbarui: 25 Agustus 2026 • Berlaku untuk seluruh pengguna platform KADU (Karsa Edukasi Vokasi) dan Transaksi Digital Midtrans Payment Gateway.
        </p>
      </div>

      <!-- Terms Sections Container -->
      <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl space-y-8 backdrop-blur-md leading-relaxed text-slate-300 text-xs sm:text-sm">
        <!-- Section 1 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">1.</span> Ketentuan Penggunaan (Conditions of Use)
          </h2>
          <p>
            Selamat datang di platform **KADU (Karsa Edukasi Vokasi)** yang beralamat resmi di <a href="https://kadu.andibakhtiar.com/" class="text-indigo-400 font-bold underline" target="_blank">https://kadu.andibakhtiar.com/</a>. Dengan mengakses, mendaftar, atau menggunakan layanan pembuatan RPP/Modul Ajar AI dan pembelian Token Vokasi pada situs ini, Anda menyatakan setuju untuk terikat oleh Syarat dan Ketentuan berikut, serta seluruh aturan hukum yang berlaku di Republik Indonesia.
          </p>
        </section>

        <!-- Section 2 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">2.</span> Deskripsi Layanan & Digital Goods (Overview & Scope)
          </h2>
          <p>
            KADU menyediakan produk digital berupa **Generator RPP Deep Learning Vokasi SMK berbasis AI** dan **Sistem Token Kuota RPP**. Pengguna dapat membeli voucher/paket token kuota untuk memproses perangkat ajar utuh (RPP, Media Pembelajaran, Video Script Prompt AI, & Materi Literasi-Numerasi Terapan).
          </p>
        </section>

        <!-- Section 3 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">3.</span> Registrasi Akun & Keamanan (Sign Up & Account Security)
          </h2>
          <ul class="list-disc pl-5 space-y-1.5">
            <li>Pengguna wajib memberikan data identitas diri (Nama, Email, Username, No. Telepon) yang akurat dan sah saat melakukan pendaftaran akun atau SSO Google Login.</li>
            <li>Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi (password) dan seluruh aktivitas transaksi yang terjadi di bawah akun pribadi milik Pengguna.</li>
            <li>Pengguna dilarang menyalahgunakan akun untuk tindakan ilegal, manipulasi data transaksi, atau tindakan pembobolan sistem.</li>
          </ul>
        </section>

        <!-- Section 4 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">4.</span> Lisensi & Hak Cipta (Grant of License & Copyrights)
          </h2>
          <p>
            Seluruh dokumen RPP, modul ajar, dan media pembelajaran yang berhasil di-generate melalui akun Pengguna menjadi hak guna penuh Pengguna untuk kepentingan akademik/pendidikan SMK. Seluruh elemen sistem, logo KADU, source code, dan arsitektur platform dilindungi oleh hak cipta di bawah kepemilikan resmi <strong>@hasanarofid.site</strong> dan pengelola platform.
          </p>
        </section>

        <!-- Section 5 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">5.</span> Ketentuan Pembayaran Payment Gateway (Midtrans Integration & Fees)
          </h2>
          <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
            <div class="flex items-center gap-2 text-indigo-300 font-bold">
              <Coins class="w-4 h-4 text-amber-400" />
              <span>Metode Pembayaran Resmi via Midtrans Payment Gateway</span>
            </div>
            <p class="text-xs text-slate-400">
              Setiap pembelian Paket Token RPP diproses secara aman melalui jaringan <strong>Midtrans Payment Gateway (PT. Midtrans)</strong>. Sistem mendukung pembayaran via QRIS (GoPay, OVO, ShopeePay, DANA), Virtual Account Seluruh Bank Nasional (BCA, Mandiri, BRI, BNI), serta E-Wallet resmi.
            </p>
          </div>
          <ul class="list-disc pl-5 space-y-1.5">
            <li>Transaksi bersifat **Seketika (Real-time)** dan otomatis diverifikasi oleh Webhook Notification Midtrans.</li>
            <li>Harga paket token yang tercantum sudah bersifat final dan nett (termasuk biaya otorisasi gateway & pajak applicable).</li>
          </ul>
        </section>

        <!-- Section 6 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">6.</span> Kebijakan Pengembalian & Garansi (Returns & Refund Policy)
          </h2>
          <p>
            Mengingat produk yang dijual di KADU merupakan **Produk Digital Instant (Digital Goods & Token Kuota AI)** yang langsung ditambahkan ke saldo akun pengguna begitu transaksi diverifikasi lunas oleh Midtrans:
          </p>
          <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-200 text-xs space-y-1.5">
            <div class="flex items-center gap-2 font-bold text-amber-300">
              <AlertTriangle class="w-4 h-4" />
              <span>Ketentuan Pengembalian Dana (No Cash Refund Policy)</span>
            </div>
            <p>
              Pembelian Token RPP yang sudah berstatus <strong>Lunas (Settlement)</strong> bersifat final dan tidak dapat dibatalkan atau dikembalikan dalam bentuk uang tunai (Non-refundable), kecuali terjadi kesalahan fatal pemotongan ganda akibat kendala teknis gateway yang terbukti secara catatan mutasi sistem.
            </p>
          </div>
        </section>

        <!-- Section 7 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">7.</span> Keamanan & Kerahasiaan Data (Security & Privacy)
          </h2>
          <p>
            KADU menjaga kerahasiaan data sensitif pengguna. Informasi finansial seperti nomor kartu kredit/pin e-wallet ditokenisasi langsung di client-side oleh SDK Midtrans tanpa disimpan pada database lokal KADU demi memenuhi standar PCI-DSS.
          </p>
        </section>

        <!-- Section 8 -->
        <section class="space-y-3">
          <h2 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
            <span class="text-indigo-400 font-mono">8.</span> Hukum yang Berlaku & Kontak Support (Applicable Laws)
          </h2>
          <p>
            Syarat dan Ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum yang berlaku di Republik Indonesia. Apabila Anda memiliki pertanyaan, kendala pembayaran, atau klaim bantuan teknis, silakan hubungi tim pengelola resmi KADU melalui:
          </p>
          <ul class="list-disc pl-5 space-y-1 font-semibold text-indigo-300">
            <li>Email Support: <a href="mailto:kadu@andibakhtiar.com" class="underline">kadu@andibakhtiar.com</a></li>
            <li>Website Pengembang: <a href="https://hasanarofid.site" target="_blank" class="underline">https://hasanarofid.site</a></li>
            <li>Domain Resmi: <a href="https://kadu.andibakhtiar.com/" target="_blank" class="underline">https://kadu.andibakhtiar.com/</a></li>
          </ul>
        </section>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950 py-8 text-slate-500 text-xs text-center">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p>&copy; {{ new Date().getFullYear() }} KADU (Karsa Edukasi Vokasi). All rights reserved.</p>
        <div class="flex items-center gap-6">
          <Link href="/terms" class="text-indigo-400 font-bold hover:underline">Syarat & Ketentuan</Link>
          <Link href="/privacy" class="text-slate-400 hover:text-white transition-colors">Kebijakan Privasi</Link>
        </div>
      </div>
    </footer>
  </div>
</template>
