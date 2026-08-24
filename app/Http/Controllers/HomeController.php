<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Redirect root access: directly to dashboard if authenticated, or to login if guest.
     */
    public function index()
    {
        $settings = [
            'site_name' => 'KADU (Karsa Edukasi Vokasi)',
            'site_description' => 'Generator RPP Deep Learning, Literasi & Numerasi Terapan Vokasi SMK Terintegrasi Payment Gateway',
            'site_logo_url' => null,
            'whatsapp_number' => '6281234567890',
        ];

        return Inertia::render('Welcome', [
            'settings' => $settings,
            'navigation' => [],
        ]);
    }
}
