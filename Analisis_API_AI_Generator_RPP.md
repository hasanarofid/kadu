# Analisis Provider API AI & Estimasi Biaya Operasional

## Generator RPP Deep Learning Vokasi SMK (Proyek KADU)

Dokumen ini disusun untuk membantu pengembang dan pemilik proyek dalam mempresentasikan analisis pemilihan **API Artificial Intelligence (AI)**, estimasi biaya pengeluaran API, rincian biaya per tab output, strategi penerapan sistem token bisnis, serta panduan cara mendapatkan API Key kepada klien.

---

## 1. Ringkasan Eksekutif

Aplikasi **KADU (Karsa Edukasi Vokasi)** menyediakan fitur utama berupa **AI Generator RPP & Modul Ajar Deep Learning Vokasi SMK**. Dalam 1 kali eksekusi generate, sistem memproses data input (Mata Pelajaran, Jurusan SMK, Capaian Pembelajaran, Kemitraan DU/DI, Model Pembelajaran PBL) dan menghasilkan 4 output dokumen utuh:

1. **[Tab 1] RPP / Modul Ajar Deep Learning** (Langkah Mindful, Meaningful, Joyful).
2. **[Tab 2] Draft Media Pembelajaran Vokasi** (Slide & LKPD Interactive).
3. **[Tab 3] Script Video & Prompt AI Video Tutorial**.
4. **[Tab 4] Materi Literasi & Numerasi Terapan SMK**.

Untuk menjalankan fungsi ini secara real-time, sistem memanfaatkan layanan **Google Gemini API** (atau alternatif OpenAI).

---

## 2. Analisis Rincian Biaya per Tab Output (Model Gemini 1.5 / 2.0 Flash)

Setiap kali tombol **"Generate RPP AI"** diklik, sistem akan mengirim 1 kali request prompt kontekstual dan menerima respon terstruktur yang dibagi ke dalam 4 tab output:

| Tab Output Dokumen                        | Estimasi Output Token | Porsi Dokumen | Estimasi Biaya API (USD) | Estimasi Biaya API (IDR) |
| ----------------------------------------- | --------------------- | ------------- | ------------------------ | ------------------------ |
| 📥 **Input Prompt (Request Context)**     | ± 800 Token           | -             | $0.00006                 | **Rp 0,96**              |
| 📜 **[Tab 1] RPP / Modul Ajar Utuh**      | ± 1.000 Token         | ~60%          | $0.00030                 | **Rp 4,80**              |
| 🖼️ **[Tab 2] Draft Media Pembelajaran**   | ± 200 Token           | ~13%          | $0.00006                 | **Rp 0,96**              |
| 🎬 **[Tab 3] Script Video & Prompt AI**   | ± 200 Token           | ~13%          | $0.00006                 | **Rp 0,96**              |
| 📚 **[Tab 4] Materi Literasi & Numerasi** | ± 200 Token           | ~13%          | $0.00006                 | **Rp 0,96**              |
| **TOTAL 1 KALI GENERATE (ALL 4 TABS)**    | **± 2.400 Token**     | **100%**      | **$0.00054**             | **~Rp 8,64 / RPP**       |

> **Kesimpulan Biaya per Tab:** Keempat tab dokumen tersebut dihasilkan sekaligus dalam 1 kali panggilan API dengan total biaya hanya **~Rp 8,64 (kurang dari Rp 10,- per seluruh paket RPP)**.

---

## 3. Strategi & Penerapan Sistem Token untuk Bisnis KADU

### A. Skema Pemotongan Token (User Experience Terbaik)

1. **Aturan 1 Token:**
    - 1 Token berhak digunakan untuk memproses 1 RPP Vokasi dari Step 1 hingga Step 4 (menghasilkan RPP, Media, Video Script, dan Materi).
    - Setelah eksekusi berhasil, saldo token dikurangi `-1` dan dicatat pada `token_logs`.
2. **Perlindungan Token (Fail-Safe):**
    - Jika koneksi API gagal atau terjadi error server, token **TIDAK dipotong** (sistem menggunakan DB Transaction).

---

### B. Paket Harga Token ke Pelanggan (Pricing & Revenue Model)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                       PAKET TOKEN APLIKASI KADU                             │
├───────────────────┬───────────────────┬───────────────────┬─────────────────┤
│ Nama Paket        │ Jumlah Token      │ Harga Jual        │ Harga / RPP     │
├───────────────────┼───────────────────┼───────────────────┼─────────────────┤
│ 📦 Paket Starter  │ 10 Token RPP      │ Rp 30.000         │ Rp 3.000 / RPP  │
│ 🚀 Paket Guru Pro │ 35 Token RPP      │ Rp 100.000        │ Rp 2.857 / RPP  │
│ 🏫 Paket Sekolah  │ 200 Token RPP     │ Rp 500.000        │ Rp 2.500 / RPP  │
└───────────────────┴───────────────────┴───────────────────┴─────────────────┘
```

---

### C. Profit Margin Klien (Margin Keuntungan Bersih)

Contoh simulasi jika sekolah membeli **Paket Sekolah (200 Token = Rp 500.000)**:

- **Pendapatan Klien (Omzet):** **Rp 500.000,-**
- **Modal Operasional API Google Gemini (200 × Rp 8,64):** **Rp 1.728,-**
- **Keuntungan Kotor (Gross Profit):** **Rp 498.272,- (Profit Margin 99.65%)**

---

## 4. Perbandingan Provider API AI

| Provider & Model AI                | Biaya Input / 1M Token | Biaya Output / 1M Token | Total Modal / 1.000 RPP  | Modal API per 1 RPP  |
| ---------------------------------- | ---------------------- | ----------------------- | ------------------------ | -------------------- |
| 🥇 **Google Gemini 1.5/2.0 Flash** | $0.075                 | $0.30                   | **$0.51 (~Rp 8.160)**    | **~Rp 8,16 / RPP**   |
| 🥈 **OpenAI GPT-4o-mini**          | $0.150                 | $0.60                   | **$1.02 (~Rp 16.320)**   | **~Rp 16,32 / RPP**  |
| 🥉 **DeepSeek-V3**                 | $0.140                 | $0.28                   | **$0.53 (~Rp 8.480)**    | **~Rp 8,48 / RPP**   |
| ❌ **OpenAI GPT-4o**               | $2.500                 | $10.00                  | **$17.00 (~Rp 272.000)** | **~Rp 272,00 / RPP** |

---

## 5. Rekomendasi Arsitektur Teknis Sistem

Disarankan menggunakan arsitektur **Primary + Fallback** di backend Laravel:

```
[User Klik "Generate RPP AI"]
           │
           ▼
[Check Saldo User (Tokens > 0?)] ──(Tidak)──➔ Redirect ke Checkout Midtrans
           │ (Ya)
           ▼
[Call Google Gemini 2.0 Flash API] ──(Timeout/Error)──➔ Fallback ke OpenAI GPT-4o-mini
           │
           ▼
[Simpan RPP + Potong 1 Token + Log Mutasi]
```

---

## 6. Panduan Cara Mendapatkan Google Gemini API Key (Untuk Klien)

Berikut adalah panduan langkah demi langkah bagi Klien untuk mendapatkan API Key Google Gemini menggunakan akun Google mereka sendiri:

1. **Buka Google AI Studio:**
   Akses portal resmi di: **[https://aistudio.google.com/](https://aistudio.google.com/)**
2. **Login Akun Google:**
   Masuk menggunakan akun Gmail atau Google Workspace resmi milik Klien/Instansi.
3. **Klik Tombol "Get API key":**
   Pada dashboard utama, klik menu/tombol **"Get API key"** (atau **"Create API key"**).
4. **Buat API Key Baru:**
   Klik **"Create API key in new project"**. Sistem Google akan membuatkan proyek baru dan menerbitkan string API Key (berawalan `AIzaSy...`).
5. **Salin & Amankan API Key:**
   Salin kunci tersebut dan berikan kepada pengembang untuk diisikan ke variabel file `.env` server:
    ```env
    GEMINI_API_KEY="AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
    GEMINI_MODEL="gemini-1.5-flash"
    ```
6. **(Opsional) Menghubungkan Kartu Kredit/Billing:**
    - **Free Tier Default:** Bebas biaya hingga 15 Request per Menit (cocok untuk testing & awal rilis).
    - **Pay-As-You-Go:** Jika penggunaan sudah ribuan RPP per hari, Klien dapat mengklik **"Set up billing"** di Google AI Studio untuk menghubungkan Kartu Debit/Kredit (Visa/Mastercard) agar tidak terkena limit kuota gratisan.

---

_Dokumen ini dibuat otomatis untuk Proyek KADU Edukasi Vokasi (2026)._
