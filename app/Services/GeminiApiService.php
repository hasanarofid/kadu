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
        $kelas            = $inputData['kelas_semester'] ?? 'X / Semester 1';
        $alokasiWaktu     = $inputData['alokasi_waktu'] ?? '3 JP (3 x 45 Menit)';
        $jurusan          = $inputData['jurusan_smk'] ?? 'Teknik Kendaraan Ringan (TKR)';
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
                $prompt = "Anda adalah Pakar Utama Kurikulum Merdeka Vokasi SMK, Instructional Designer, dan Master AI Deep Learning Engine.\n" .
                          "Tugas Anda adalah memproses SELURUH 14 variabel spesifik yang diinput oleh Guru SMK berikut ini menjadi dokumen RPP Vokasi utuh, Media Pembelajaran Interaktif, Video Script 3D, dan Ringkasan Materi yang SANGAT DETAIL, MENDALAM, KONTEKSTUAL, dan SIAP PAKAI.\n\n" .
                          "=== VARIABEL INPUT GURU SMK ===\n" .
                          "1. Mata Pelajaran: {$mapel}\n" .
                          "2. Kelas / Semester: {$kelas}\n" .
                          "3. Alokasi Waktu: {$alokasiWaktu}\n" .
                          "4. Konsentrasi Keahlian / Jurusan SMK: {$jurusan}\n" .
                          "5. Capaian Pembelajaran (CP): {$cp}\n" .
                          "6. Gaya Belajar Peserta Didik: {$gaya}\n" .
                          "7. Karakteristik Fisik / Akomodasi Inklusi: {$karakteristikFisik}\n" .
                          "8. Model Pembelajaran: {$model}\n" .
                          "9. Metode Pembelajaran: {$metode}\n" .
                          "10. Kemitraan DU/DI & Mitra Industri: {$dudi}\n" .
                          "11. Lingkungan Belajar Fisik: {$ruangFisik}\n" .
                          "12. Lingkungan Belajar Virtual / LMS: {$ruangVirtual}\n" .
                          "13. Software / Media Digital: {$softwareDigital}\n" .
                          "14. Target Profil Pelajar Pancasila & Vokasi: {$profil}\n\n" .
                          "=== INSTRUKSI SINTESIS AI (MANDATORI) ===\n" .
                          "Integrasikan KESELURUHAN 14 variabel di atas secara nyata dan kontekstual di dalam setiap narasi dokumen. Jangan mengabaikan variabel manapun!\n\n" .
                          "Format output HARUS berupa JSON valid dengan 4 objek key berikut:\n" .
                          "{\n" .
                          '  "content_rpp": "Modul Ajar / RPP Utuh 4-Step Deep Learning (Identitas, Strategi Diferensiasi Gaya Belajar ' . $gaya . ' & Akomodasi ' . $karakteristikFisik . ', Integrasi Model ' . $model . ' dengan Mitra ' . $dudi . ', Langkah Mindful-Meaningful-Joyful, Understanding, Application Praktik Vokasi di ' . $ruangFisik . ' dengan software ' . $softwareDigital . ', Refleksi Metakognisi, Asesmen Formatif & Sumatif terintegrasi Literasi-Numerasi)",' . "\n" .
                          '  "content_media": "Draft PPT & Media Pembelajaran Interaktif (Slide 1-5 spesifik ' . $mapel . ' ' . $jurusan . ', LKPD Digital simulator berbasis ' . $softwareDigital . ', Infografis alur bengkel ' . $ruangFisik . ' rujukan ' . $dudi . ')",' . "\n" .
                          '  "content_video_script": "Naskah Video Tutorial Pembelajaran 3D & Prompt Generator AI (Alur Scene 00:00-03:00, Visual 3D ' . $ruangFisik . ', Narasi Audio K3LH & Pengukuran Presisi ' . $mapel . ', serta Prompt AI Video Generator)",' . "\n" .
                          '  "content_materi": "Ringkasan Materi Pembelajaran Literasi & Numerasi Terapan SMK (Konsep Kontekstual Industri ' . $jurusan . ', Formula & Kalkulasi Teknis Presisi ' . $mapel . ', SOP Industri Mitra ' . $dudi . ', Glosarium Istilah Teknis)"' . "\n" .
                          "}\n\n" .
                          "Berikan respon HANYA berupa objek JSON valid tanpa teks pengantar atau penutup di luar JSON.";

                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

                $response = Http::withHeaders([
                    'Content-Type'   => 'application/json',
                    'X-goog-api-key' => $this->apiKey,
                ])->timeout(20)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature'      => 0.7,
                        'maxOutputTokens'  => 2500,
                    ],
                ]);

                if ($response->successful()) {
                    $resultText = $response->json('candidates.0.content.parts.0.text');
                    if ($resultText) {
                        $cleanJson = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($resultText));
                        $parsed = json_decode($cleanJson, true);

                        if (is_array($parsed) && isset($parsed['content_rpp'])) {
                            return [
                                'content_rpp'          => $parsed['content_rpp'] ?? '',
                                'content_media'        => $parsed['content_media'] ?? '',
                                'content_video_script' => $parsed['content_video_script'] ?? '',
                                'content_materi'       => $parsed['content_materi'] ?? '',
                            ];
                        }

                        // Fallback if parsing failed but result exists
                        return [
                            'content_rpp'          => $resultText,
                            'content_media'        => "SLIDE PRESENTASI (PPT) & MEDIA PEMBELAJARAN VOKASI\n" .
                                                      "Mata Pelajaran: {$mapel} | Konsentrasi Keahlian: {$jurusan}\n" .
                                                      "Kelas/Semester: {$kelas} | Alokasi Waktu: {$alokasiWaktu}\n\n" .
                                                      "SLIDE 1: JUDUL & KESELAMATAN KERJA (K3LH)\n- Judul: Penerapan Deep Learning {$mapel} Standar Industri\n- Sub-Judul: Modul Vokasi Berbasis Mitra ({$dudi})\n- Catatan Visual: Tampilan peralatan kerja presisi dan seragam APD lengkap.\n\n" .
                                                      "SLIDE 2: LANGKAH BERKESADARAN & APERSEPSI\n- Pertanyaan Pemantik: Bagaimana kalkulasi presisi mencegah kerusakan pada unit kerja?\n- Orientasi Siswa: Keheningan 1 menit dan simulasi alat keselamatan di {$ruangFisik}.\n\n" .
                                                      "SLIDE 3: KONSEP UTAMA & STIMULUS NUMERASI\n- Formula Kalkulasi Presisi {$mapel}.\n- Diagram Alur Kerja Standar Operasional Prosedur (SOP) Industri ({$dudi}).\n\n" .
                                                      "SLIDE 4: SIMULASI DIGITAL & PRAKTIK UNJUK KERJA\n- Panduan Praktik Berbasis Simulator: {$softwareDigital}.\n- Instruksi Lembar Kerja Praktik Peserta Didik (LKPD).\n\n" .
                                                      "SLIDE 5: REFLEKSI & EVALUASI MANDIRI\n- Diskusi Kelompok Vokasi & Refleksi Metakognisi via LMS ({$ruangVirtual}).",
                            'content_video_script' => "NASKAH VIDEO TUTORIAL PEMBELAJARAN VOKASI 3D\n" .
                                                      "Mata Pelajaran: {$mapel} | Target Platform: Video Edukasi & LMS ({$ruangVirtual})\n\n" .
                                                      "--- ALUR SCENE & NASKAH AUDIO (00:00 - 03:00) ---\n" .
                                                      "[SCENE 1: 00:00 - 00:30] INTRO & KESELAMATAN KERJA\n- Visual: Tampilan 3D bengkel vokasi {$ruangFisik} dengan siswa mengenakan APD lengkap.\n- Narasi Suara: 'Selamat datang di modul praktik vokasi {$mapel}. Selalu utamakan keselamatan kerja dan pengamatan presisi.'\n\n" .
                                                      "[SCENE 2: 00:30 - 01:45] DEMONSTRASI UTAMA & KALKULASI NUMERASI\n- Visual: Zoom-in proses pengukuran presisi dan simulasi {$softwareDigital}.\n- Narasi Suara: 'Perhatikan alur pengukuran berikut. Presisi adalah standar utama dalam industri pasangan {$dudi}.'\n\n" .
                                                      "[SCENE 3: 01:45 - 03:00] UNJUK KERJA & REFLEKSI MANDIRI\n- Visual: Tampilan QR Code LKPD Digital & Ringkasan Materi.\n- Narasi Suara: 'Diskusikan hasil analisismu bersama rekan kelompokmu. Selamat belajar!'",
                                                      'content_materi'       => "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK\n" .
                                                      "- Konsep Utama: Pembelajaran Kontekstual {$mapel} di Industri {$jurusan} ({$kelas}).\n" .
                                                      "- Formula & Kalkulasi Presisi: Penerapan rumus teknis dan pengukuran presisi pada {$softwareDigital}.\n" .
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
                   "- Kelas / Semester: {$kelas}\n" .
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
                   "   2. Langkah Kebermaknaan (Meaningful): Orientasi kasus riil industri DU/DI ({$dudi}).\n" .
                   "   3. Langkah Menggembirakan (Joyful): Ice breaking interaktif simulator ({$softwareDigital}).\n" .
                   "B. KEGIATAN INTI:\n" .
                   "   1. Langkah Pemahaman (Understanding): Bedah materi & stimulus literasi-numerasi.\n" .
                   "   2. Langkah Aplikasi (Application): Unjuk kerja praktik lapangan di {$ruangFisik}.\n" .
                   "   3. Refleksi Pembelajaran (Metakognisi): Evaluasi mandiri & umpan balik via {$ruangVirtual}.\n" .
                   "C. KEGIATAN PENUTUP: Menyimpulkan hasil proyek & berdoa bersama.\n\n" .
                   "V. ASESMEN PEMBELAJARAN TERINTEGRASI LITERASI & NUMERASI\n" .
                   "- Asesmen Formatif: Observasi Rubrik Unjuk Kerja & Lembar Kerja (LKPD).\n" .
                   "- Asesmen Sumatif: Uji Kompetensi Proyek & Kalkulasi Kebutuhan Presisi.",
            'content_media' => "SLIDE PRESENTASI (PPT) & MEDIA PEMBELAJARAN VOKASI\n" .
                   "1. Slide Presentasi Visual Interactive: SOP & Prosedur Teknik {$mapel} ({$jurusan})\n" .
                   "2. Infografis Alur Kerja Praktik Bengkel/Lab SMK ({$ruangFisik}) Rujukan Mitra ({$dudi})\n" .
                   "3. Lembar Kerja Praktik Peserta Didik (LKPD) berbasis Simulator Software Digital ({$softwareDigital}).",
            'content_video_script' => "NASKAH VIDEO TUTORIAL PEMBELAJARAN VOKASI 3D\n" .
                   "[SCENE 1: 00:00 - 00:30] INTRO & KESELAMATAN KERJA\n- Visual: Tampilan 3D bengkel vokasi {$ruangFisik}.\n- Narasi Suara: 'Selamat datang di modul praktik vokasi {$mapel}. Selalu utamakan K3LH.'\n\n" .
                   "[SCENE 2: 00:30 - 01:45] DEMONSTRASI UTAMA\n- Visual: Pengukuran presisi & simulasi {$softwareDigital}.\n- Narasi Suara: 'Perhatikan alur pengukuran berikut sesuai standar mitra {$dudi}.'\n\n" .
                   "[SCENE 3: 01:45 - 03:00] REFLEKSI MANDIRI\n- Visual: Tampilan QR Code LKPD Digital di {$ruangVirtual}.",
            'content_materi' => "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK\n" .
                   "- Konsep Utama: Pembelajaran Kontekstual {$mapel} di Industri {$jurusan} ({$kelas}).\n" .
                   "- Formula & Kalkulasi Presisi: Penerapan rumus matematika/teknis pada alat kerja.\n" .
                   "- Referensi SOP: Standar Operasional Prosedur Industri Pasangan ({$dudi}).",
        ];
    }
}
