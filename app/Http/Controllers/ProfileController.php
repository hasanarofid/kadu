<?php

namespace App\Http\Controllers;

use App\Mail\PasswordChangedMail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the Profile settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $isAdmin = $user->hasRole('admin');
        $settings = Setting::all()->pluck('value', 'key');

        $companyBanks = json_decode($settings['company_banks'] ?? '[]', true);

        return Inertia::render('Profile/Edit', [
            'is_admin'   => $isAdmin,
            'user_profile' => [
                'id'                  => $user->id,
                'name'                => $user->name,
                'username'            => $user->username ?? '',
                'email'               => $user->email,
                'avatar_url'          => $user->avatar_url,
                'phone'               => $user->phone ?? '',
                'bank_name'           => $user->bank_name ?? 'Bank Mandiri',
                'bank_account_number' => $user->bank_account_number ?? '',
                'bank_account_name'   => $user->bank_account_name ?? '',
                'role_name'           => $isAdmin ? 'ADMINISTRATOR' : 'MITRA',
            ],
            'company_profile' => [
                'name'      => $settings['company_name'] ?? 'Mitra Syiar Baitullah',
                'owner'     => $settings['company_owner'] ?? 'President Director',
                'copyright' => $settings['company_copyright'] ?? 'Mitra Syiar Baitullah. Hak Cipta Dilindungi Undang-Undang.',
                'logo_url'  => !empty($settings['site_logo']) ? Storage::url($settings['site_logo']) : null,
                'banks'     => is_array($companyBanks) ? $companyBanks : [],
            ],
            'status' => session('status'),
        ]);
    }

    /**
     * Update Corporate Profile & User Credentials.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $isAdmin = $user->hasRole('admin');

        $rules = [
            'name'                => 'required|string|max:100',
            'username'            => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'               => 'required|email|max:100|unique:users,email,' . $user->id,
            'avatar'              => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'phone'               => 'nullable|string|max:20',
            'password'            => 'nullable|string|min:8|confirmed',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_name'   => 'nullable|string|max:100',
        ];

        if ($isAdmin) {
            $rules['company_name']      = 'required|string|max:100';
            $rules['company_owner']     = 'required|string|max:100';
            $rules['company_copyright'] = 'required|string|max:255';
            $rules['site_logo']         = 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048';
        }

        $validated = $request->validate($rules);

        // Save company settings if Admin
        if ($isAdmin) {
            Setting::setValue('company_name', $validated['company_name'], 'text');
            Setting::setValue('company_owner', $validated['company_owner'], 'text');
            Setting::setValue('company_copyright', $validated['company_copyright'], 'text');

            if ($request->hasFile('site_logo')) {
                $path = $request->file('site_logo')->store('settings', 'public');
                Setting::setValue('site_logo', $path, 'image');
            }
        }

        // Save user credentials & bank profile
        $user->name = $validated['name'];
        $user->username = strtolower($validated['username']);
        $user->email = $validated['email'];

        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('phone')) {
            $user->phone = $validated['phone'];
        }
        if ($request->filled('bank_name')) {
            $user->bank_name = $validated['bank_name'];
        }
        if ($request->filled('bank_account_number')) {
            $user->bank_account_number = $validated['bank_account_number'];
        }
        if ($request->filled('bank_account_name')) {
            $user->bank_account_name = $validated['bank_account_name'];
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Send email alert if password changed
        if ($request->filled('password')) {
            try {
                Mail::to($user->email)->send(new PasswordChangedMail($user));
            } catch (\Throwable $e) {
                // Log SMTP error silently if email delivery fails so profile save isn't blocked
                \Illuminate\Support\Facades\Log::error('Failed to send password changed email: ' . $e->getMessage());
            }
        }

        return Redirect::route('profile.edit')->with('success', 'Profil akun Anda berhasil diperbarui.');
    }

    /**
     * Add or delete company bank account (Admin only).
     */
    public function updateBanks(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user->hasRole('admin')) {
            return Redirect::route('profile.edit')->with('error', 'Hanya Admin yang dapat memperbarui bank perusahaan.');
        }

        $banks = $request->input('banks', []);
        Setting::setValue('company_banks', json_encode($banks), 'json');

        return Redirect::route('profile.edit')->with('success', 'Daftar rekening bank perusahaan berhasil diperbarui.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
