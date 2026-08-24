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
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

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

                // Assign default client role
                if (method_exists($user, 'assignRole')) {
                    try {
                        $user->assignRole('client');
                    } catch (\Throwable $th) {
                        // ignore if role doesn't exist
                    }
                }
            }

            Auth::login($user, true);

            return redirect()->intended(route('admin.dashboard'));
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google: ' . $e->getMessage());
        }
    }
}
