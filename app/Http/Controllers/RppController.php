<?php

namespace App\Http\Controllers;

use App\Models\Rpp;
use App\Models\TokenLog;
use App\Services\GeminiApiService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RppController extends Controller
{
    protected GeminiApiService $aiService;

    public function __construct(GeminiApiService $aiService)
    {
        $this->aiService = $aiService;
    }

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
            'userTokens' => $user->tokens ?? 0,
        ]);
    }

    /**
     * Show the form for creating a new RPP (4-step wizard).
     */
    public function create()
    {
        $user = auth()->user();

        // Pembuatan RPP wajib menggunakan Token (kecuali Admin)
        if (($user->tokens ?? 0) <= 0 && !$user->hasRole('admin')) {
            return redirect()->route('token.purchase')->with('error', 'Token RPP Anda kosong (0 Token). Silakan beli Token RPP terlebih dahulu melalui Payment Gateway.');
        }

        return Inertia::render('Rpp/CreateEdit', [
            'userTokens' => $user->tokens ?? 0,
        ]);
    }

    /**
     * Store a newly created RPP in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Check if user has available tokens
        if (($user->tokens ?? 0) <= 0 && !$user->hasRole('admin')) {
            return redirect()->route('token.purchase')->with('error', 'Token RPP Anda telah habis. Silakan isi saldo Token RPP terlebih dahulu.');
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

        // Generate AI Content via GeminiApiService
        $aiContents = $this->aiService->generateRppContent($validated);

        $rpp = Rpp::create([
            'user_id' => $user->id,
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
            'content_rpp' => $aiContents['content_rpp'],
            'content_media' => $aiContents['content_media'],
            'content_video_script' => $aiContents['content_video_script'],
            'content_materi' => $aiContents['content_materi'],
            'status' => 'published',
        ]);

        // Deduct 1 token if not admin
        if (!$user->hasRole('admin') && $user->tokens > 0) {
            $user->decrement('tokens', 1);

            TokenLog::create([
                'user_id' => $user->id,
                'rpp_id' => $rpp->id,
                'type' => 'deduct',
                'tokens' => 1,
                'balance_after' => $user->fresh()->tokens,
                'description' => "Penggunaan 1 Token untuk generate RPP: " . $title,
            ]);
        }

        return redirect()->route('rpps.show', $rpp->id)->with('success', 'RPP Vokasi berhasil digenerate via AI!');
    }

    /**
     * Display the specified RPP.
     */
    public function show(Rpp $rpp)
    {
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
            'userTokens' => auth()->user()->tokens ?? 0,
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

        $aiContents = $this->aiService->generateRppContent($validated);

        $rpp->update(array_merge($validated, [
            'title' => $title,
            'content_rpp' => $aiContents['content_rpp'],
            'content_media' => $aiContents['content_media'],
            'content_video_script' => $aiContents['content_video_script'],
            'content_materi' => $aiContents['content_materi'],
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
}
