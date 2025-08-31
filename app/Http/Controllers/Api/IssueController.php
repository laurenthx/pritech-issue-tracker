<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function toggleIssueMember(Request $request, Issue $issue, User $user)
    {
        if ($issue->members()->where('user_id', $user->id)->exists()) {
            $issue->members()->detach($user);
            return response()->json(['status' => 'detached', 'message' => 'Member detached successfully.']);
        } else {
            $issue->members()->attach($user);
            return response()->json(['status' => 'attached', 'message' => 'Member attached successfully.']);
        }
    }

    public function getIssueMembers(Request $request, Issue $issue)
    {
        $issueMembers = $issue->members()->pluck('users.id')->toArray();
        $allUsers = User::select('id', 'name')->orderBy('name')->get();

        $formattedUsers = $allUsers->map(function ($user) use ($issueMembers) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'is_assigned' => in_array($user->id, $issueMembers),
            ];
        });

        return response()->json([
            'allUsers' => $formattedUsers,
            'assignedMembers' => $issue->members()->select('users.id', 'users.name')->get()
        ]);
    }
}