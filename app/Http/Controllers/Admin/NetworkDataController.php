<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NetworkDataController extends Controller
{
    /**
     * Display the Network Member Directory page for logged in user's team downlines.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user() ?: User::first();
        $search = $request->input('search');

        // Map of user_id => level_team (Generasi 1-12 downlines)
        $levelMap = [];
        $currentLevelIds = [$currentUser->id];

        for ($level = 1; $level <= 12; $level++) {
            if (empty($currentLevelIds)) {
                break;
            }

            $nextLevelUsers = User::whereIn('parent_id', $currentLevelIds)->pluck('id', 'id')->toArray();
            foreach ($nextLevelUsers as $id) {
                $levelMap[$id] = $level;
            }
            $currentLevelIds = array_keys($nextLevelUsers);
        }

        // Fetch downlines belonging to user's team
        $downlineIds = array_keys($levelMap);
        
        if (!empty($downlineIds)) {
            $query = User::with('parent')->whereIn('id', $downlineIds)->orderBy('id', 'asc');
        } else {
            // Fallback for founder/admin if no downline tree yet: show all other users
            $query = User::with('parent')->where('id', '!=', $currentUser->id)->orderBy('id', 'asc');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->get()->map(function ($u) use ($levelMap) {
            $idCode = 'USR' . str_pad($u->id, 3, '0', STR_PAD_LEFT);
            $sponsor = $u->parent ? '@' . $u->parent->username : 'FOUNDER';
            $levelNum = $levelMap[$u->id] ?? 1;

            return [
                'id'           => $u->id,
                'id_code'      => $idCode,
                'name'         => $u->name,
                'username'     => $u->username ?? 'user' . $u->id,
                'email'        => $u->email,
                'sponsor'      => $sponsor,
                'level_team'   => 'Team ' . $levelNum,
                'level_num'    => $levelNum,
                'package_name' => $u->package_name ?? 'DP Join Rp 500rb',
                'saldo'        => (float) ($u->saldo ?? 0),
            ];
        });

        return Inertia::render('Admin/NetworkData', [
            'members' => $members,
            'filters' => [
                'search' => $search ?? '',
            ],
        ]);
    }
}
