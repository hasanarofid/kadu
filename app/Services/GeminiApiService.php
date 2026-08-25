<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiApiService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
        $this->model = env('GEMINI_MODEL', 'gemini-flash-latest');
    }

    /**
     * Generate RPP Deep Learning content using Google Gemini API or intelligent fallback.
     */
    public function generateRppContent(array $inputData): array
    {
        $mapel            = $inputData['mata_pelajaran'] ?? 'Matematika Vokasi';
        $jurusan          = $inputData['jurusan_smk'] ?? 'Teknik Kendaraan Ringan (TKR)';
        $alokasiWaktu     = $inputData['alokasi_waktu'] ?? '3 JP (3 x 45 Menit)';
        $cp               = $inputData['capaian_pembelajaran'] ?? '';
        $gaya             = is_array($inputData['gaya_belajar'] ?? null) ? implode(', ', $inputData['gaya_belajar']) : ($inputData['gaya_belajar'] ?? 'Visual, Kinestetik, Auditori');
        $karakteristikFisik = $inputData['karakteristik_fisik'] ?? 'Non-Inklusi (Reguler)';
        $model            = $inputData['model_pembelajaran'] ?? 'Project-Based Learning (PBL)';
        $metode           = $inputData['metode_pembelajaran'] ?? 'Diskusi Kelompok, Simulasi, dan Praktik Bengkel';
        $strategi         = $inputData['strategi_pembelajaran'] ?? 'Pembelajaran Berdiferensiasi & Collaborative Learning';
        $media            = $inputData['media_pembelajaran'] ?? 'Slide Interactive, Infografis Bengkel, LKPD Digital';
        $materi           = $inputData['materi_pembelajaran'] ?? 'Literasi & Numerasi Terapan SMK';
        $dudi             = $inputData['kemitraan_dudi'] ?? 'Industri Pasangan DU/DI & Guru Tamu Praktisi';
        $ruangFisik       = $inputData['ruang_fisik'] ?? 'Bengkel Otomotif / Ruang Teori SMK';
        $ruangVirtual     = $inputData['ruang_virtual'] ?? 'LMS Google Classroom & WhatsApp Group';
        $softwareDigital  = $inputData['software_digital'] ?? 'Platform Merdeka Mengajar (PMM), Simulator Engine Scan, Canva';
        $profil           = is_array($inputData['dimensi_profil'] ?? null) ? implode(', ', $inputData['dimensi_profil']) : ($inputData['dimensi_profil'] ?? 'Bernalar Kritis, Kreatif, Gotong Royong, Vokasi');

        if (!empty($this->apiKey)) {
            try {
                $prompt = "Anda adalah pakar Kurikulum Merdeka Vokasi SMK & Pembelajaran Mendalam (Deep Learning Engine). Buatkan Rencana Program Pembelajaran (RPP) / Modul Ajar Deep Learning Vokasi utuh dan presisi dengan spesifikasi lengkap:\n\n" .
                          "1. Mata Pelajaran: {$mapel}\n" .
                          "2. Alokasi Waktu: {$alokasiWaktu}\n" .
                          "3. Capaian Pembelajaran (CP): {$cp}\n" .
                          "4. Target Dimensi Profil Lulusan: {$profil}\n" .
                          "5. Karakteristik Peserta Didik (Gaya Belajar): {$gaya}\n" .
                          "6. Karakteristik Fisik: {$karakteristikFisik}\n" .
                          "7. Kerangka Pembelajaran (Bagian 1):\n" .
                          "   - Praktik Pedagogik / Model Pembelajaran: {$model}\n" .
                          "   - Metode Pembelajaran: {$metode}\n" .
                          "   - Strategi Pembelajaran: {$strategi}\n" .
                          "   - Media Pembelajaran: {$media}\n" .
                          "   - Materi Pembelajaran: {$materi}\n" .
                          "8. Kemitraan Pembelajaran (DU/DI & Mitra): {$dudi}\n" .
                          "9. Lingkungan Pembelajaran: Ruang Fisik ({$ruangFisik}), Ruang Virtual ({$ruangVirtual})\n" .
                          "10. Pemanfaatan Digital: {$softwareDigital}\n" .
                          "11. Langkah-Langkah Pembelajaran (Prinsip Pembelajaran Mendalam / Deep Learning):\n" .
                          "    a. Kegiatan Awal:\n" .
                          "       - Langkah Berkesadaran (Mindful)\n" .
                          "       - Langkah Kebermaknaan (Meaningful)\n" .
                          "       - Langkah Menggembirakan (Joyful)\n" .
                          "    b. Kegiatan Inti:\n" .
                          "       - Langkah Pemahaman (Understanding)\n" .
                          "       - Langkah Aplikasi (Application Praktik Vokasi)\n" .
                          "       - Refleksi Pembelajaran (Metakognisi)\n" .
                          "    c. Kegiatan Penutup\n" .
                          "12. Asesmen Pembelajaran Terintegrasi:\n" .
                          "    - Asesmen Formatif (Terintegrasi Literasi & Numerasi)\n" .
                          "    - Asesmen Sumatif (Terintegrasi Literasi & Numerasi)\n\n" .
                          "Output HARUS terbagi menjadi 4 bagian berikut:\n" .
                          "1. RPP / Modul Ajar (Berisi seluruh 12 poin struktur di atas utuh & detail)\n" .
                          "2. Media Pembelajaran (Draft Slide presentasi visual interactive, Infografis alur kerja, LKPD simulator digital)\n" .
                          "3. Video Script & Prompt AI (Skrip tutorial video 3D dan prompt generator video AI)\n" .
                          "4. Materi Pembelajaran (Ringkasan materi literasi & numerasi terapan vokasi standar industri)\n\n" .
                          "Berikan respon terstruktur dengan penanda judul yang sangat jelas untuk keempat bagian di atas.";

                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

                $response = Http::withHeaders([
                    'Content-Type'   => 'application/json',
                    'X-goog-api-key' => $this->apiKey,
                ])->timeout(35)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $resultText = $response->json('candidates.0.content.parts.0.text');
                    if ($resultText) {
                        return [
                            'content_rpp'          => $resultText,
                            'content_media'        => "SLIDE PRESENTASI (PPT) & MEDIA PEMBELAJARAN VOKASI\n" .
                                                      "Mata Pelajaran: {$mapel} | Konsentrasi Keahlian: {$jurusan}\n\n" .
                                                      "SLIDE 1: JUDUL & KESELAMATAN KERJA (K3LH)\n- Judul: Penerapan Deep Learning {$mapel} Standar Industri\n- Sub-Judul: Modul Vokasi Berbasis Mitra ({$dudi})\n- Catatan Visual: Tampilan peralatan kerja presisi dan seragam APD lengkap.\n\n" .
                                                      "SLIDE 2: LANGKAH BERKESADARAN & APERSEPSI\n- Pertanyaan Pemantik: Bagaimana kalkulasi presisi mencegah kerusakan pada unit kerja?\n- Orientasi Siswa: Keheningan 1 menit dan simulasi alat keselamatan.\n\n" .
                                                      "SLIDE 3: KONSEP UTAMA & STIMULUS NUMERASI\n- Formula Kalkulasi Presisi {$mapel}.\n- Diagram Alur Kerja Standar Operasional Prosedur (SOP) Industri.\n\n" .
                                                      "SLIDE 4: SIMULASI DIGITAL & PRAKTIK UNJUK KERJA\n- Panduan Praktik Berbasis Simulator: {$softwareDigital}.\n- Instruksi Lembar Kerja Praktik Peserta Didik (LKPD).\n\n" .
                                                      "SLIDE 5: REFLEKSI & EVALUASI MANDIRI\n- Diskusi Kelompok Vokasi & Refleksi Metakognisi.",
                            'content_video_script' => "NASKAH VIDEO TUTORIAL PEMBELAJARAN VOKASI 3D\n" .
                                                      "Mata Pelajaran: {$mapel} | Target Platform: Video Edukasi & LMS ({$ruangVirtual})\n\n" .
                                                      "--- ALUR SCENE & NASKAH AUDIO (00:00 - 03:00) ---\n" .
                                                      "[SCENE 1: 00:00 - 00:30] INTRO & KESELAMATAN KERJA\n- Visual: Tampilan 3D bengkel vokasi {$ruangFisik} dengan siswa mengenakan APD lengkap.\n- Narasi Suara: 'Selamat datang di modul praktik vokasi {$mapel}. Selalu utamakan keselamatan kerja dan pengamatan presisi.'\n\n" .
                                                      "[SCENE 2: 00:30 - 01:45] DEMONSTRASI UTAMA & KALKULASI NUMERASI\n- Visual: Zoom-in proses pengukuran presisi dan grafik animasi kalkulasi.\n- Narasi Suara: 'Perhatikan alur pengukuran berikut. Presisi adalah standar utama dalam industri pasangan {$dudi}.'\n\n" .
                                                      "[SCENE 3: 01:45 - 03:00] UNJUK KERJA & REFLEKSI MANDIRI\n- Visual: Tampilan QR Code LKPD Digital & Ringkasan Materi.\n- Narasi Suara: 'Diskusikan hasil analisismu bersama rekan kelompokmu. Selamat belajar!'",
                            'content_materi'       => "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK\n" .
                                                      "- Konsep Utama: Pembelajaran Kontekstual {$mapel} di Industri {$jurusan}.\n" .
                                                      "- Formula & Kalkulasi Presisi: Penerapan rumus dan pengukuran presisi.\n" .
                                                      "- Referensi SOP: Standar Operasional Prosedur Industri Pasangan ({$dudi}).",
                        ];
                    }
                } else {
                    Log::warning('Gemini API Non-Success Response: Status ' . $response->status() . ' - Body: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error('Gemini API Exception: ' . $e->getMessage());
            }
        }

        // Standard Intelligent Fallback Synthesis
        return [
            'content_rpp' => "RENCANA PROGRAM PEMBELAJARAN (RPP) / MODUL AJAR VOKASI DEEP LEARNING\n\n" .
                   "I. IDENTITAS & KONTEKS PEMBELAJARAN\n" .
                   "- Mata Pelajaran: {$mapel}\n" .
                   "- Konsentrasi Keahlian / Jurusan: {$jurusan}\n" .
                   "- Alokasi Waktu: {$alokasiWaktu}\n" .
                   "- Capaian Pembelajaran (CP): {$cp}\n\n" .
                   "II. DIMENSI PROFIL LULUSAN & KARAKTERISTIK PESERTA DIDIK\n" .
                   "- Target Dimensi Profil: {$profil}\n" .
                   "- Gaya Belajar Siswa: {$gaya}\n" .
                   "- Karakteristik Fisik: {$karakteristikFisik}\n\n" .
                   "III. KERANGKA PEMBELAJARAN & LINGKUNGAN\n" .
                   "- Praktik Pedagogik (Model): {$model}\n" .
                   "- Metode Pembelajaran: {$metode}\n" .
                   "- Strategi Pembelajaran: {$strategi}\n" .
                   "- Media Pembelajaran: {$media}\n" .
                   "- Materi Pembelajaran: {$materi}\n" .
                   "- Kemitraan Pembelajaran: {$dudi}\n" .
                   "- Lingkungan Pembelajaran: Ruang Fisik ({$ruangFisik}), Ruang Virtual ({$ruangVirtual})\n" .
                   "- Pemanfaatan Digital: {$softwareDigital}\n\n" .
                   "IV. LANGKAH-LANGKAH PEMBELAJARAN (DEEP LEARNING)\n" .
                   "A. KEGIATAN AWAL:\n" .
                   "   1. Langkah Berkesadaran (Mindful): Apersepsi, keheningan fokus, K3LH.\n" .
                   "   2. Langkah Kebermaknaan (Meaningful): Orientasi kasus riil industri DU/DI.\n" .
                   "   3. Langkah Menggembirakan (Joyful): Ice breaking interaktif alat kerja.\n" .
                   "B. KEGIATAN INTI:\n" .
                   "   1. Langkah Pemahaman (Understanding): Bedah materi & stimulus literasi-numerasi.\n" .
                   "   2. Langkah Aplikasi (Application): Unjuk kerja praktik lapangan/bengkel.\n" .
                   "   3. Refleksi Pembelajaran (Metakognisi): Evaluasi mandiri & umpan balik.\n" .
                   "C. KEGIATAN PENUTUP: Menyimpulkan hasil proyek & berdoa bersama.\n\n" .
                   "V. ASESMEN PEMBELAJARAN TERINTEGRASI LITERASI & NUMERASI\n" .
                   "- Asesmen Formatif: Observasi Rubrik Unjuk Kerja & Lembar Kerja (LKPD).\n" .
                   "- Asesmen Sumatif: Uji Kompetensi Proyek & Kalkulasi Kebutuhan Presisi.",
            'content_media' => "SLIDE PRESENTASI (PPT) & MEDIA PEMBELAJARAN VOKASI\n" .
                   "1. Slide Presentasi Visual Interactive: SOP & Prosedur Teknik {$mapel}\n" .
                   "2. Infografis Alur Kerja Praktik Bengkel/Lab SMK\n" .
                   "3. Lembar Kerja Praktik Peserta Didik (LKPD) berbasis Simulator Software Digital ({$softwareDigital}).",
            'content_video_script' => "NASKAH VIDEO TUTORIAL PEMBELAJARAN VOKASI 3D\n" .
                   "[SCENE 1: 00:00 - 00:30] INTRO & KESELAMATAN KERJA\n- Visual: Tampilan 3D bengkel vokasi {$ruangFisik}.\n- Narasi Suara: 'Selamat datang di modul praktik vokasi {$mapel}. Selalu utamakan K3LH.'\n\n" .
                   "[SCENE 2: 00:30 - 01:45] DEMONSTRASI UTAMA\n- Visual: Pengukuran presisi & simulasi {$softwareDigital}.\n- Narasi Suara: 'Perhatikan alur pengukuran berikut.'\n\n" .
                   "[SCENE 3: 01:45 - 03:00] REFLEKSI MANDIRI\n- Visual: Tampilan QR Code LKPD Digital.",
            'content_materi' => "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK\n" .
                   "- Konsep Utama: Pembelajaran Kontekstual {$mapel} di Industri {$jurusan}.\n" .
                   "- Formula & Kalkulasi Presisi: Penerapan rumus matematika/teknis pada alat kerja.\n" .
                   "- Referensi SOP: Standar Operasional Prosedur Industri Pasangan ({$dudi}).",
        ];
    }
}
