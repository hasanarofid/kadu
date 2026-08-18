<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GenealogyController extends Controller
{
    /**
     * Display the binary genealogy network tree.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user() ?: User::first();
        
        $focusId = $request->query('focus_id', $currentUser->id);
        $focusedUser = User::with(['leftSon', 'rightSon'])->find($focusId);

        if (!$focusedUser) {
            $focusedUser = $currentUser;
        }

        // Build 3-level tree node array
        $treeData = $this->buildBinaryNode($focusedUser);

        // All users for quick focus search dropdown
        $allUsers = User::select('id', 'name', 'username', 'email')->get()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'username' => $u->username ?: ('@' . strtolower(explode(' ', $u->name)[0])),
                'label' => $u->name . ' (' . ($u->username ? '@' . $u->username : $u->email) . ')',
            ];
        });

        return Inertia::render('Admin/Genealogy/Index', [
            'focus_user' => [
                'id' => $focusedUser->id,
                'name' => $focusedUser->name,
                'username' => $focusedUser->username ? '@' . $focusedUser->username : '@admin',
            ],
            'tree' => $treeData,
            'all_users' => $allUsers,
        ]);
    }

    private function buildBinaryNode($user)
    {
        if (!$user) {
            return null;
        }

        $leftSon = User::where('parent_id', $user->id)->where('position', 'left')->first();
        $rightSon = User::where('parent_id', $user->id)->where('position', 'right')->first();

        $leftLeft = $leftSon ? User::where('parent_id', $leftSon->id)->where('position', 'left')->first() : null;
        $leftRight = $leftSon ? User::where('parent_id', $leftSon->id)->where('position', 'right')->first() : null;

        $rightLeft = $rightSon ? User::where('parent_id', $rightSon->id)->where('position', 'left')->first() : null;
        $rightRight = $rightSon ? User::where('parent_id', $rightSon->id)->where('position', 'right')->first() : null;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username ? '@' . $user->username : '@admin',
            'left_count' => $user->left_count ?? 0,
            'right_count' => $user->right_count ?? 0,
            'package_name' => $user->package_name ?? 'Basic',
            'left' => $leftSon ? [
                'id' => $leftSon->id,
                'name' => $leftSon->name,
                'username' => $leftSon->username ? '@' . $leftSon->username : '@budi',
                'left_count' => $leftSon->left_count ?? 0,
                'right_count' => $leftSon->right_count ?? 0,
                'package_name' => $leftSon->package_name ?? 'Basic',
                'left' => $leftLeft ? [
                    'id' => $leftLeft->id,
                    'name' => $leftLeft->name,
                    'username' => $leftLeft->username ? '@' . $leftLeft->username : '@dewi',
                    'left_count' => $leftLeft->left_count ?? 0,
                    'right_count' => $leftLeft->right_count ?? 0,
                ] : null,
                'right' => $leftRight ? [
                    'id' => $leftRight->id,
                    'name' => $leftRight->name,
                    'username' => $leftRight->username ? '@' . $leftRight->username : '@eko',
                    'left_count' => $leftRight->left_count ?? 0,
                    'right_count' => $leftRight->right_count ?? 0,
                ] : null,
            ] : null,
            'right' => $rightSon ? [
                'id' => $rightSon->id,
                'name' => $rightSon->name,
                'username' => $rightSon->username ? '@' . $rightSon->username : '@siti',
                'left_count' => $rightSon->left_count ?? 0,
                'right_count' => $rightSon->right_count ?? 0,
                'package_name' => $rightSon->package_name ?? 'Basic',
                'left' => $rightLeft ? [
                    'id' => $rightLeft->id,
                    'name' => $rightLeft->name,
                    'username' => $rightLeft->username ? '@' . $rightLeft->username : '@fajar',
                    'left_count' => $rightLeft->left_count ?? 0,
                    'right_count' => $rightLeft->right_count ?? 0,
                ] : null,
                'right' => $rightRight ? [
                    'id' => $rightRight->id,
                    'name' => $rightRight->name,
                    'username' => $rightRight->username ? '@' . $rightRight->username : '',
                    'left_count' => $rightRight->left_count ?? 0,
                    'right_count' => $rightRight->right_count ?? 0,
                ] : null,
            ] : null,
        ];
    }
}
