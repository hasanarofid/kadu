<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Display the Team Saya (Multi-Tier Team Generation Level 1-12) page.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        
        // Admin can inspect another user's team structure via query parameter ?username=xxx
        $targetUser = $currentUser;
        if ($request->filled('username') && $currentUser->hasRole('admin')) {
            $searchedUser = User::where('username', $request->username)->first();
            if ($searchedUser) {
                $targetUser = $searchedUser;
            }
        }

        $selectedLevel = $request->input('level', 'all'); // 'all' or 1..12
        $search = $request->input('search', '');

        // Generate 12 Team Levels (Generasi 1 - 12)
        $teamLevels = [];
        $currentLevelUserIds = [$targetUser->id];

        $totalMembersAllLevels = 0;
        $totalCommissionAllLevels = 0;
        $activeLevelsCount = 0;
        $allMembersList = [];

        for ($level = 1; $level <= 12; $level++) {
            $commPerMember = $this->getCommissionForLevel($level);

            if (empty($currentLevelUserIds)) {
                $teamLevels[$level] = [
                    'level'                 => $level,
                    'name'                  => "Team {$level}",
                    'count'                 => 0,
                    'commission_per_member' => $commPerMember,
                    'total_commission'      => 0,
                    'members'               => [],
                ];
                continue;
            }

            // Fetch members at this level
            $query = User::whereIn('parent_id', $currentLevelUserIds)
                ->select('id', 'name', 'username', 'email', 'parent_id', 'created_at', 'package_name', 'phone')
                ->with('parent:id,name,username')
                ->orderBy('id', 'desc');

            $levelMembers = $query->get();
            $nextUserIds = $levelMembers->pluck('id')->toArray();
            $count = $levelMembers->count();

            if ($count > 0) {
                $activeLevelsCount++;
            }

            $totalMembersAllLevels += $count;
            $levelTotalComm = $count * $commPerMember;
            $totalCommissionAllLevels += $levelTotalComm;

            $formattedMembers = $levelMembers->map(function ($m) use ($level, $commPerMember) {
                return [
                    'id'           => $m->id,
                    'name'         => $m->name,
                    'username'     => $m->username ?: 'user' . $m->id,
                    'email'        => $m->email,
                    'phone'        => $m->phone ?? '-',
                    'sponsor'      => $m->parent ? '@' . ($m->parent->username ?: 'user' . $m->parent->id) . ' (' . $m->parent->name . ')' : 'FOUNDER',
                    'package'      => $m->package_name ?: 'Basic DP Rp 500rb',
                    'joined_at'    => $m->created_at ? $m->created_at->format('d/m/Y') : '-',
                    'level'        => "Team {$level}",
                    'level_num'    => $level,
                    'commission'   => $commPerMember,
                ];
            })->toArray();

            $teamLevels[$level] = [
                'level'                 => $level,
                'name'                  => "Team {$level}",
                'count'                 => $count,
                'commission_per_member' => $commPerMember,
                'total_commission'      => $levelTotalComm,
                'members'               => $formattedMembers,
            ];

            // Accumulate for search / list filtering
            foreach ($formattedMembers as $fm) {
                $allMembersList[] = $fm;
            }

            $currentLevelUserIds = $nextUserIds;
        }

        // Filter members list based on selected level & search keyword
        $filteredMembers = collect($allMembersList);

        if ($selectedLevel !== 'all') {
            $lvlNum = (int) $selectedLevel;
            $filteredMembers = $filteredMembers->filter(fn($m) => $m['level_num'] === $lvlNum);
        }

        if (!empty($search)) {
            $searchLower = strtolower($search);
            $filteredMembers = $filteredMembers->filter(function ($m) use ($searchLower) {
                return str_contains(strtolower($m['name']), $searchLower)
                    || str_contains(strtolower($m['username']), $searchLower)
                    || str_contains(strtolower($m['email']), $searchLower)
                    || str_contains(strtolower($m['sponsor']), $searchLower);
            });
        }

        $directSponsorsCount = $teamLevels[1]['count'] ?? 0;

        return Inertia::render('Admin/Team', [
            'target_user' => [
                'id'       => $targetUser->id,
                'name'     => $targetUser->name,
                'username' => $targetUser->username ?: 'user' . $targetUser->id,
            ],
            'summary' => [
                'total_members'    => $totalMembersAllLevels,
                'direct_sponsors'  => $directSponsorsCount,
                'total_commission' => $totalCommissionAllLevels,
                'active_levels'    => $activeLevelsCount,
            ],
            'team_levels'       => array_values($teamLevels),
            'filtered_members'  => array_values($filteredMembers->toArray()),
            'filters' => [
                'level'  => (string) $selectedLevel,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Get commission per member by team level according to PRD 2026 plan.
     */
    private function getCommissionForLevel(int $level): float
    {
        return match (true) {
            $level >= 1 && $level <= 7 => 5000,
            $level == 8                => 3000,
            $level >= 9 && $level <= 10 => 2000,
            $level >= 11 && $level <= 12 => 1000,
            default                    => 0,
        };
    }
}
