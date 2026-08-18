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
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('login');
    }
}
