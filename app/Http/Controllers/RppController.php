<?php

namespace App\Http\Controllers;

use App\Models\Rpp;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RppController extends Controller
{
    /**
     * Display a listing of the user's RPPs.
     */
    public function index()
    {
        $user = auth()->user();
        
        $query = Rpp::with('user');

        // If not admin, only show current user's RPPs
        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        $rpps = $query->latest()->paginate(10);

        return Inertia::render('Rpp/Index', [
            'rpps' => $rpps,
        ]);
    }

    /**
     * Show the form for creating a new RPP (4-step wizard).
     */
    public function create()
    {
        return Inertia::render('Rpp/CreateEdit');
    }

    /**
     * Store a newly created RPP in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_pelajaran' => 'required|string|max:255',
            'kelas_semester' => 'required|string|max:255',
            'alokasi_waktu' => 'required|string|max:255',
            'jurusan_smk' => 'required|string|max:255',
            'capaian_pembelajaran' => 'required|string',
            'gaya_belajar' => 'nullable|array',
            'karakteristik_fisik' => 'nullable|string',
            'model_pembelajaran' => 'nullable|string',
            'metode_pembelajaran' => 'nullable|string',
            'kemitraan_dudi' => 'nullable|string',
            'ruang_fisik' => 'nullable|string',
            'ruang_virtual' => 'nullable|string',
            'software_digital' => 'nullable|string',
            'dimensi_profil' => 'nullable|array',
        ]);

        $title = "RPP " . $validated['mata_pelajaran'] . " (" . $validated['jurusan_smk'] . ") - " . $validated['kelas_semester'];

        // Generate Structured Deep Learning Content
        $contentRpp = $this->generateContentRpp($validated);
        $contentMedia = $this->generateContentMedia($validated);
        $contentVideoScript = $this->generateContentVideoScript($validated);
        $contentMateri = $this->generateContentMateri($validated);

        $rpp = Rpp::create([
            'user_id' => auth()->id(),
            'title' => $title,
            'mata_pelajaran' => $validated['mata_pelajaran'],
            'kelas_semester' => $validated['kelas_semester'],
            'alokasi_waktu' => $validated['alokasi_waktu'],
            'jurusan_smk' => $validated['jurusan_smk'],
            'capaian_pembelajaran' => $validated['capaian_pembelajaran'],
            'gaya_belajar' => $validated['gaya_belajar'] ?? ['Visual', 'Kinestetik'],
            'karakteristik_fisik' => $validated['karakteristik_fisik'] ?? 'Non-Inklusi (Reguler)',
            'model_pembelajaran' => $validated['model_pembelajaran'] ?? 'Project-Based Learning (PBL)',
            'metode_pembelajaran' => $validated['metode_pembelajaran'] ?? 'Diskusi Kelompok, Simulasi, dan Praktik Bengkel',
            'kemitraan_dudi' => $validated['kemitraan_dudi'] ?? 'Industri Pasangan DU/DI & Guru Tamu Praktisi',
            'ruang_fisik' => $validated['ruang_fisik'] ?? 'Bengkel / Ruang Teori SMK',
            'ruang_virtual' => $validated['ruang_virtual'] ?? 'LMS Google Classroom & WhatsApp Group Class',
            'software_digital' => $validated['software_digital'] ?? 'Platform Merdeka Mengajar (PMM), Simulator Engine Scan, Canva',
            'dimensi_profil' => $validated['dimensi_profil'] ?? ['Bernalar Kritis (Critical Thinking)', 'Gotong Royong & Kolaboratif (Collaboration)', 'Kompeten & Berstandar Industri (Vokasi)'],
            'content_rpp' => $contentRpp,
            'content_media' => $contentMedia,
            'content_video_script' => $contentVideoScript,
            'content_materi' => $contentMateri,
            'status' => 'published',
        ]);

        return redirect()->route('rpps.show', $rpp->id)->with('success', 'RPP Vokasi berhasil digenerate via AI!');
    }

    /**
     * Display the specified RPP (4-Tab Output View).
     */
    public function show(Rpp $rpp)
    {
        // Check ownership or admin
        if (auth()->user()->id !== $rpp->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses ke RPP ini.');
        }

        return Inertia::render('Rpp/Show', [
            'rpp' => $rpp->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified RPP.
     */
    public function edit(Rpp $rpp)
    {
        if (auth()->user()->id !== $rpp->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return Inertia::render('Rpp/CreateEdit', [
            'rpp' => $rpp,
        ]);
    }

    /**
     * Update the specified RPP in storage.
     */
    public function update(Request $request, Rpp $rpp)
    {
        if (auth()->user()->id !== $rpp->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'mata_pelajaran' => 'required|string|max:255',
            'kelas_semester' => 'required|string|max:255',
            'alokasi_waktu' => 'required|string|max:255',
            'jurusan_smk' => 'required|string|max:255',
            'capaian_pembelajaran' => 'required|string',
            'gaya_belajar' => 'nullable|array',
            'karakteristik_fisik' => 'nullable|string',
            'model_pembelajaran' => 'nullable|string',
            'metode_pembelajaran' => 'nullable|string',
            'kemitraan_dudi' => 'nullable|string',
            'ruang_fisik' => 'nullable|string',
            'ruang_virtual' => 'nullable|string',
            'software_digital' => 'nullable|string',
            'dimensi_profil' => 'nullable|array',
        ]);

        $title = "RPP " . $validated['mata_pelajaran'] . " (" . $validated['jurusan_smk'] . ") - " . $validated['kelas_semester'];

        $rpp->update(array_merge($validated, [
            'title' => $title,
            'content_rpp' => $this->generateContentRpp($validated),
            'content_media' => $this->generateContentMedia($validated),
            'content_video_script' => $this->generateContentVideoScript($validated),
            'content_materi' => $this->generateContentMateri($validated),
        ]));

        return redirect()->route('rpps.show', $rpp->id)->with('success', 'RPP Vokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified RPP from storage.
     */
    public function destroy(Rpp $rpp)
    {
        if (auth()->user()->id !== $rpp->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $rpp->delete();

        return redirect()->route('rpps.index')->with('success', 'RPP berhasil dihapus.');
    }

    private function generateContentRpp(array $data): string
    {
        $mapel = $data['mata_pelajaran'];
        $jurusan = $data['jurusan_smk'];
        $cp = $data['capaian_pembelajaran'];
        $model = $data['model_pembelajaran'] ?? 'Project-Based Learning (PBL)';
        $dudi = $data['kemitraan_dudi'] ?? 'Industri Pasangan DU/DI';

        return "RENCANA PROGRAM PEMBELAJARAN (RPP) / MODUL AJAR VOKASI\n" .
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
               "- Asesmen Sumatif: Uji kompetensi hasil proyek.";
    }

    private function generateContentMedia(array $data): string
    {
        return "DRAFT MEDIA PEMBELAJARAN VOKASI\n" .
               "1. Slide Presentasi Visual Interactive: SOP & Prosedur Teknik " . $data['jurusan_smk'] . "\n" .
               "2. Infografis Alur Kerja Praktik Bengkel SMK\n" .
               "3. Lembar Kerja Praktik Peserta Didik (LKPD) berbasis Simulator Software Digital.";
    }

    private function generateContentVideoScript(array $data): string
    {
        return "PROMPT & SCRIPT VIDEO PEMBELAJARAN AI\n" .
               "[Video Title]: Tutorial Praktik Vokasi Presisi - " . $data['mata_pelajaran'] . "\n" .
               "[Prompt AI Video Generator]: 'Create a high quality 3D animated tutorial video showing vocational students working safely in a modern workshop for " . $data['jurusan_smk'] . "'.\n" .
               "[Scene 1]: Pengenalan Alat & APD K3LH.\n" .
               "[Scene 2]: Demonstrasi Pengukuran Presisi & Troubleshooting.";
    }

    private function generateContentMateri(array $data): string
    {
        return "RINGKASAN MATERI LITERASI & NUMERASI TERAPAN SMK\n" .
               "- Konsep Utama: Pembelajaran Kontekstual " . $data['mata_pelajaran'] . " di Industri " . $data['jurusan_smk'] . ".\n" .
               "- Formula & Kalkulasi Presisi: Penerapan rumus matematika/teknis pada alat bengkel.\n" .
               "- Referensi SOP: Standar Operasional Prosedur Duduk Industri.";
    }
}
