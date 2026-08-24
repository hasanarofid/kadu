# KADU - Karsa Edukasi Vokasi (EduDeep SMK)

![KADU Logo](public/favicon.ico)

**KADU (Karsa Edukasi Vokasi)** adalah platform aplikasi berbasis web untuk pembuatan **RPP Deep Learning, Literasi & Numerasi Vokasi SMK** secara otomatis menggunakan AI, dilengkapi dengan sistem integrasi **Payment Gateway** dan arsitektur modern berbasis **Laravel 11**, **Vue 3 (Inertia.js)**, **Tailwind CSS**, dan **MySQL**.

- **Official Live Website:** [https://kadu.andibakhtiar.com/](https://kadu.andibakhtiar.com/)
- **Repository Git:** `git@github.com:hasanarofid/kadu.git`
- **Owner & Developer:** [@hasanarofid.site](https://hasanarofid.site)

---

## 🚀 Fitur Utama & Layout Workflow (EduDeep SMK)

Sistem **KADU** menyediakan alur kerja 4-langkah (Multi-Step Form Wizard) yang interaktif dan intuitif:

```
[1. IDENTITAS & SISWA] ➔ [2. KERANGKA & LINGKUNGAN] ➔ [3. PROFIL & GENERATOR] ➔ [4. OUTPUT & CETAK]
```

### 1. Identitas, Konteks & Karakteristik Peserta Didik
- **Mata Pelajaran, Kelas / Semester, & Alokasi Waktu (JP)**
- **Konsentrasi Keahlian (Jurusan SMK):** Contoh: *Teknik Kendaraan Ringan (TKR)*
- **Capaian Pembelajaran (CP)**
- **Gaya Belajar Peserta Didik:** Visual, Auditori, Kinestetik
- **Karakteristik Fisik Siswa:** Non-Inklusi (Reguler) / Inklusi (Kebutuhan Khusus)

### 2. Kerangka Pembelajaran, Kemitraan & Lingkungan
- **Praktik Pedagogik (Model Pembelajaran):** Project-Based Learning (PBL), dsb.
- **Metode & Strategi Pembelajaran:** Diskusi Kelompok, Simulasi, Praktik Bengkel.
- **Kemitraan Pembelajaran (DU/DI / Komunitas):** Industri Pasangan DU/DI & Guru Tamu Praktisi.
- **Lingkungan Pembelajaran:** Ruang Fisik (Bengkel / Teori) & Ruang Virtual (LMS Google Classroom, WhatsApp Group).
- **Pemanfaatan Digital / Software:** Platform Merdeka Mengajar (PMM), Simulator Engine Scan, Canva.

### 3. Target Dimensi Profil Lulusan & Generasi AI
- **Dimensi Profil Lulusan (Profil Pelajar Pancasila & Vokasi):**
  - Bernalar Kritis *(Critical Thinking)*
  - Kreatif & Inovatif *(Creativity)*
  - Gotong Royong & Kolaboratif *(Collaboration)*
  - Mandiri & Perilaku Adaptif
  - Berkebinekaan Global *(Citizenship)*
  - Kompeten & Berstandar Industri *(Vokasi)*
- **Generator Dokumen AI:** Memproses seluruh masukan menjadi dokumen pembelajaran utuh.

### 4. Output & Cetak PDF
- **Multi-Tab Preview:**
  - 📄 RPP / Modul Ajar
  - 🖼️ Media Pembelajaran
  - 🎬 Video Script & Prompt
  - 📚 Materi Pembelajaran
- **Export & Action:** Cetak Dokumen PDF & Reset/Buat RPP Baru.

---

## 💳 Arsitektur Integrasi Payment Gateway

*Payment Gateway* bertindak sebagai jembatan yang mengamankan data transaksi dari aplikasi ke jaringan bank / e-wallet.

```
[ Frontend ] ──(1. Tokenisasi)──> [ Payment Gateway ]
     │                                    │
(2. Charge/Invoice)              (3. Otorisasi Finansial)
     ▼                                    ▼
[ Backend ]  <──(4. Webhook Callback)── [ Bank / E-Wallet ]
```

### Urutan Teknis Integrasi:
1. **Checkout & Tokenisasi (Frontend ke Gateway):**
   - Data sensitif ditokenisasi di frontend oleh SDK Payment Gateway untuk keamanan PCI-DSS.
2. **Otentikasi & Request API (Backend ke Gateway):**
   - Backend memvalidasi cart dan membuat transaksi via API menggunakan *Secret Key*.
3. **Proses Otorisasi Bank (Gateway ke Jaringan Finansial):**
   - Payment Gateway meneruskan informasi ke bank/e-wallet untuk pemeriksaan saldo dan anti-fraud.
4. **Respon Asinkron & Webhook (Gateway ke Backend):**
   - Gateway mengirimkan HTTP POST Callback/Webhook ke endpoint backend. Controller backend mengubah status pesanan di DB menjadi "Lunas" dan memicu notifikasi email resi.
   > **Key Insight:** Penanganan *Webhook* adalah titik rawan *bug* paling krusial. Server backend dibuat idempoten untuk menangani respon asinkron kapan saja.
5. **Settlement (Pencairan Dana):**
   - Pencairan dana dari gateway ke rekening bank utama merchant/klien.

---

## 🛠️ Instalasi & Pengoperasian Lokal

### 1. Clone & Install Dependensi
```bash
git clone git@github.com:hasanarofid/kadu.git
cd kadu
composer install
npm install
```

### 2. Environment & Database Migration
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### 3. Jalankan Development Server
```bash
# Terminal 1: Vite Frontend Development Server
npm run dev

# Terminal 2: Laravel Backend Server
php artisan serve
```

---

## 🔄 Alur Git & Production Auto-Deploy (Niagahoster)

1. Build frontend assets:
   ```bash
   npm run build
   ```
2. Commit dan Push ke GitHub:
   ```bash
   git add .
   git commit -m "feat: update fitur RPP & payment gateway"
   git push origin master
   ```
3. Webhook Niagahoster akan secara otomatis mengunduh (*git pull*) perubahan terbaru di server live: [https://kadu.andibakhtiar.com/](https://kadu.andibakhtiar.com/).

---

## 👨‍💻 Owner & Tim Pengembang
- **Owner / Developer:** [@hasanarofid.site](https://hasanarofid.site)
- **Live URL:** [https://kadu.andibakhtiar.com/](https://kadu.andibakhtiar.com/)
