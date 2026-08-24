<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TokenLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of users with token count and role management.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::with('roles');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => [
                'search' => $search,
            ]
        ]);
    }

    /**
     * Top-up tokens manually for a user (Admin feature).
     */
    public function topupTokens(Request $request, User $user)
    {
        $request->validate([
            'tokens' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $amount = (int) $request->tokens;
        $user->increment('tokens', $amount);

        TokenLog::create([
            'user_id' => $user->id,
            'rpp_id' => null,
            'type' => 'admin_topup',
            'tokens' => $amount,
            'balance_after' => $user->fresh()->tokens,
            'description' => 'Top-up manual oleh Admin (' . ($request->reason ?: 'Bonus/Topup') . ')',
        ]);

        return redirect()->back()->with('success', "Berhasil menambahkan {$amount} token ke akun {$user->name}.");
    }

    /**
     * Change user role between admin and user.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:admin,user',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', "Role user {$user->name} telah diubah menjadi {$request->role}.");
    }
}
