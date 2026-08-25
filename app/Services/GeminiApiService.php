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
        $mapel    = $inputData['mata_pelajaran'] ?? 'Matematika Vokasi';
        $jurusan  = $inputData['jurusan_smk'] ?? 'Teknik Kendaraan Ringan (TKR)';
        $cp       = $inputData['capaian_pembelajaran'] ?? '';
        $model    = $inputData['model_pembelajaran'] ?? 'Project-Based Learning (PBL)';
        $dudi     = $inputData['kemitraan_dudi'] ?? 'Industri Pasangan DU/DI';
        $gaya     = is_array($inputData['gaya_belajar'] ?? null) ? implode(', ', $inputData['gaya_belajar']) : ($inputData['gaya_belajar'] ?? 'Visual, Kinestetik');
        $profil   = is_array($inputData['dimensi_profil'] ?? null) ? implode(', ', $inputData['dimensi_profil']) : ($inputData['dimensi_profil'] ?? 'Bernalar Kritis, Kreatif, Vokasi');

        if (!empty($this->apiKey)) {
            try {
                $prompt = "Anda adalah pakar Kurikulum Merdeka Vokasi SMK & Deep Learning Engine. Buatkan Rencana Program Pembelajaran (RPP) / Modul Ajar Deep Learning Vokasi utuh dengan spesifikasi:\n" .
                          "- Mata Pelajaran: {$mapel}\n" .
                          "- Konsentrasi Keahlian / Jurusan SMK: {$jurusan}\n" .
                          "- Capaian Pembelajaran (CP): {$cp}\n" .
                          "- Model Pembelajaran: {$model}\n" .
                          "- Kemitraan DU/DI: {$dudi}\n" .
                          "- Gaya Belajar Siswa: {$gaya}\n" .
                          "- Target Dimensi Profil: {$profil}\n\n" .
                          "Output HARUS terbagi menjadi 4 bagian berikut:\n" .
                          "1. RPP / Modul Ajar (Kerangka Pembelajaran, Tujuan Meaningful, Stimulus Literasi & Numerasi Terapan Vokasi, Langkah Pembelajaran Mindful-Meaningful-Joyful, Asesmen Formatif & Sumatif)\n" .
                          "2. Media Pembelajaran (Slide presentasi visual interactive, Infografis alur bengkel, LKPD simulator)\n" .
                          "3. Video Script & Prompt AI (Skrip tutorial video 3D dan prompt generator video AI)\n" .
                          "4. Materi Pembelajaran (Ringkasan materi literasi & numerasi terapan vokasi standar industri)\n\n" .
                          "Berikan respon terstruktur dengan penanda judul jelas untuk keempat bagian di atas.";

                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

                $response = Http::withHeaders([
                    'Content-Type'   => 'application/json',
                    'X-goog-api-key' => $this->apiKey,
                ])->timeout(30)->post($url, [
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
                            'content_media'        => "1. Slide Presentasi Visual Interactive: SOP & Prosedur Teknik {$jurusan}\n2. Infografis Alur Kerja Praktik Bengkel SMK\n3. LKPD berbasis Simulator Software Digital.",
                            'content_video_script' => "PROMPT AI VIDEO: 'High quality 3D tutorial video of vocational students practicing {$mapel} in modern {$jurusan} workshop'.",
                            'content_materi'       => "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK: Penerapan formula dan standar operasional prosedur industri {$dudi}.",
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
            'content_rpp' => "RENCANA PROGRAM PEMBELAJARAN (RPP) / MODUL AJAR VOKASI\n" .
                   "Pendekatan Pembelajaran Mendalam (Deep Learning) - SMK Vokasi\n\n" .
                   "Mata Pelajaran: {$mapel}\n" .
                   "Konsentrasi Keahlian: {$jurusan}\n" .
                   "Capaian Pembelajaran: {$cp}\n\n" .
                   "I. KERANGKA PEMBELAJARAN & EKOSISTEM\n" .
                   "- Praktik Pedagogik: {$model}\n" .
                   "- Kemitraan DU/DI: {$dudi}\n" .
                   "- Ruang Fisik & Virtual: Bengkel Vokasi & LMS Classroom\n\n" .
                   "II. TUJUAN PEMBELAJARAN (MEANINGFUL LEARNING)\n" .
                   "- Peserta didik mampu menganalisis dan menerapkan konsep {$mapel} pada standar spesifikasi kerja di bidang {$jurusan}.\n\n" .
                   "III. STIMULUS LITERASI & NUMERASI TERAPAN\n" .
                   "- Stimulus Literasi Vokasi: Modul panduan kerja standar (SOP) dan manual teknik {$jurusan}.\n" .
                   "- Stimulus Numerasi Vokasi: Pengukuran presisi, kalkulasi toleransi, dan estimasi biaya operasional.\n\n" .
                   "IV. LANGKAH-LANGKAH PEMBELAJARAN (DEEP LEARNING)\n" .
                   "A. Kegiatan Awal:\n" .
                   "   1. Mindful (Kesadaran): Apersepsi dan pengenalan keselamatan kerja (K3LH).\n" .
                   "   2. Meaningful (Kebermaknaan): Diskusi studi kasus riil dari industri pasangan DU/DI.\n" .
                   "   3. Joyful (Menggembirakan): Ice breaking simulasi cepat alat kerja.\n" .
                   "B. Kegiatan Inti:\n" .
                   "   1. Pemahaman Konsep & Stimulus Literasi-Numerasi.\n" .
                   "   2. Aplikasi Praktik Vokasi di Bengkel/Laboratorium.\n" .
                   "   3. Refleksi Pembelajaran (Metakognisi).\n" .
                   "C. Kegiatan Penutup: Evaluasi hasil karya dan doa bersama.\n\n" .
                   "V. ASESMEN PEMBELAJARAN TERINTEGRASI\n" .
                   "- Asesmen Formatif: Observasi unjuk kerja praktik bengkel.\n" .
                   "- Asesmen Sumatif: Uji kompetensi hasil proyek.",
            'content_media' => "DRAFT MEDIA PEMBELAJARAN VOKASI\n" .
                   "1. Slide Presentasi Visual Interactive: SOP & Prosedur Teknik {$jurusan}\n" .
                   "2. Infografis Alur Kerja Praktik Bengkel SMK\n" .
                   "3. Lembar Kerja Praktik Peserta Didik (LKPD) berbasis Simulator Software Digital.",
            'content_video_script' => "PROMPT & SCRIPT VIDEO PEMBELAJARAN AI\n" .
                   "[Video Title]: Tutorial Praktik Vokasi Presisi - {$mapel}\n" .
                   "[Prompt AI Video Generator]: 'Create a high quality 3D animated tutorial video showing vocational students working safely in a modern workshop for {$jurusan}'.\n" .
                   "[Scene 1]: Pengenalan Alat & APD K3LH.\n" .
                   "[Scene 2]: Demonstrasi Pengukuran Presisi & Troubleshooting.",
            'content_materi' => "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK\n" .
                   "- Konsep Utama: Pembelajaran Kontekstual {$mapel} di Industri {$jurusan}.\n" .
                   "- Formula & Kalkulasi Presisi: Penerapan rumus matematika/teknis pada alat bengkel.\n" .
                   "- Referensi SOP: Standar Operasional Prosedur Duduk Industri.",
        ];
    }
}
