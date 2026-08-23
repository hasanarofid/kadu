<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MemberActivationController extends Controller
{
    /**
     * Display member activation form.
     */
    public function index()
    {
        $currentUser = auth()->user() ?: User::first();

        // Get user's active DP Awal vouchers
        $vouchers = Voucher::where('user_id', $currentUser->id)
            ->where('status', 'active')
            ->get()
            ->map(function ($v) {
                return [
                    'code' => $v->code,
                    'package_name' => $v->package_name,
                    'label' => 'Kode DP Awal: ' . $v->code . ' (DP Rp 500.000)',
                ];
            });

        // List of active users to choose as Sponsor
        $allUsers = User::select('id', 'name', 'username', 'email')->get()->map(function ($u) {
            return [
                'username' => $u->username ?: strtolower(explode(' ', $u->name)[0]),
                'name'     => $u->name,
                'label'    => '@' . ($u->username ?: strtolower(explode(' ', $u->name)[0])) . ' (' . $u->name . ')',
            ];
        });

        return Inertia::render('Admin/Activation/Index', [
            'vouchers'        => $vouchers,
            'users'           => $allUsers,
            'default_sponsor' => $currentUser->username ?: 'admin',
        ]);
    }

    /**
     * Process member activation with DP Awal code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'         => 'required|string|alpha_dash|max:50|unique:users,username',
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users,email',
            'sponsor_username' => 'required|string|exists:users,username',
            'voucher_code'     => 'required|string|exists:vouchers,code',
        ]);

        $currentUser = auth()->user() ?: User::first();

        // Verify DP Awal code (voucher)
        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('user_id', $currentUser->id)
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            throw ValidationException::withMessages([
                'voucher_code' => 'Kode DP Awal tidak valid, sudah digunakan, atau bukan milik Anda.',
            ]);
        }

        $sponsorUser = User::where('username', $request->sponsor_username)->first();
        if (!$sponsorUser) {
            throw ValidationException::withMessages([
                'sponsor_username' => 'Username Sponsor tidak ditemukan.',
            ]);
        }

        // Auto determine leg position if binary structure needs it
        $hasLeft = User::where('parent_id', $sponsorUser->id)->where('position', 'left')->exists();
        $autoPosition = $hasLeft ? 'right' : 'left';

        // Create new member directly linked to Sponsor
        $newUser = User::create([
            'name'         => $request->name,
            'username'     => strtolower($request->username),
            'email'        => $request->email,
            'password'     => bcrypt('password'),
            'parent_id'    => $sponsorUser->id,
            'position'     => $autoPosition,
            'package_name' => 'DP Join Rp 500rb',
            'left_count'   => 0,
            'right_count'  => 0,
            'left_points'  => 0,
            'right_points' => 0,
        ]);
        $newUser->assignRole('client');

        // Mark DP Awal code as used
        $voucher->update([
            'status'     => 'used',
            'used_by_id' => $newUser->id,
            'used_at'    => now(),
        ]);

        return redirect()->route('admin.team.index')
            ->with('success', "Mitra baru @{$newUser->username} ({$newUser->name}) berhasil didaftarkan & diaktifkan dengan DP Awal!");
    }
}
