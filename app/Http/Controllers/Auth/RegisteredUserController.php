<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tokens' => 10,
        ]);

        $user->assignRole('user');

        // Catat Log Bonus Token Pendaftaran Baru
        \App\Models\TokenLog::create([
            'user_id' => $user->id,
            'rpp_id' => null,
            'type' => 'bonus',
            'tokens' => 10,
            'balance_after' => 10,
            'description' => 'Bonus 10 Token Gratis Pendaftaran Baru',
        ]);

        event(new Registered($user));

        // Kirim Welcome Email secara langsung
        Mail::to($user->email)->send(new WelcomeMail($user));

        Auth::login($user);

        return redirect(route('dashboard'))->with('success', 'Selamat datang! Akun Anda berhasil dibuat dan Anda mendapatkan bonus 10 Token gratis untuk mulai membuat RPP Vokasi.');
    }
}
