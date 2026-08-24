<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        $redirectUri = url('/auth/google/callback');
        
        // Ensure https scheme on production / live domain
        if (request()->secure() || str_contains($redirectUri, 'kadu.andibakhtiar.com')) {
            $redirectUri = str_replace('http://', 'https://', $redirectUri);
        }

        return Socialite::driver('google')->redirectUrl($redirectUri)->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $redirectUri = url('/auth/google/callback');
            if (request()->secure() || str_contains($redirectUri, 'kadu.andibakhtiar.com')) {
                $redirectUri = str_replace('http://', 'https://', $redirectUri);
            }

            $googleUser = Socialite::driver('google')->redirectUrl($redirectUri)->user();

            // Find user by google_id or email
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Update existing user
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $user->avatar,
                ]);
            } else {
                // Register new user via Google SSO
                $username = Str::slug(explode('@', $googleUser->email)[0]) . rand(100, 999);

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'username' => $username,
                    'password' => bcrypt(Str::random(16)),
                ]);

                // Assign default user role
                if (method_exists($user, 'assignRole')) {
                    try {
                        $user->assignRole('user');
                    } catch (\Throwable $th) {
                        // fallback
                    }
                }
            }

            Auth::login($user, true);

            return redirect()->route('dashboard');
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google: ' . $e->getMessage());
        }
    }
}
