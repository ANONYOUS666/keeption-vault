<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function getMembers()
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $owner = User::find($ownerId);
        $members = TeamMember::where('owner_id', $ownerId)->orderBy('created_at', 'asc')->get();

        // Always ensure the owner themselves is represented as the Primary Admin!
        $formatted = [
            [
                'id'            => 'owner_' . $owner->id,
                'name'          => $owner->name,
                'email'         => $owner->email,
                'role'          => 'admin',
                'status'        => 'active',
                'is_owner'      => true,
                'joined_date'   => $owner->created_at->format('M d, Y'),
                'avatar_url'    => $owner->avatar ? '/avatars/' . $owner->avatar : null,
            ]
        ];

        foreach ($members as $m) {
            $formatted[] = [
                'id'            => $m->id,
                'name'          => $m->name ?: explode('@', $m->invited_email)[0],
                'email'         => $m->invited_email,
                'role'          => $m->role,
                'status'        => $m->status,
                'is_owner'      => false,
                'joined_date'   => $m->created_at->format('M d, Y'),
                'avatar_url'    => $m->user && $m->user->avatar ? '/avatars/' . $m->user->avatar : null,
            ];
        }

        return response()->json([
            'members'      => $formatted,
            'seats'        => $owner->seats,
            'teams_name'   => $owner->teams_name,
            'plan'         => $owner->plan,
            'storage_total'=> $owner->plan === 'teams' ? 500 * 1024**3 : ($owner->plan === 'pro' ? 100 * 1024**3 : 5 * 1024**3),
        ]);
    }

    public function invite(Request $request)
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'email' => 'required|email',
            'role'  => 'required|in:admin,editor,viewer',
        ]);

        $owner = User::find($ownerId);
        
        // Count invited + active members (excluding owner)
        $memberCount = TeamMember::where('owner_id', $ownerId)->count();
        
        // Note: owner's seat counts as part of the total.
        // So total members count = $memberCount + 1 (owner)
        if ($memberCount + 1 >= $owner->seats) {
            return response()->json([
                'error'        => 'No seats available',
                'require_seat' => true,
                'price'        => 8,
                'message'      => 'You have used all ' . $owner->seats . ' paid seats. Purchase another seat to invite this member.'
            ], 403);
        }

        // Check if already invited
        $existing = TeamMember::where('owner_id', $ownerId)->where('invited_email', $request->email)->first();
        if ($existing) {
            return response()->json(['error' => 'This user has already been invited.'], 422);
        }

        // Check if invited email matches owner email
        if (strtolower($request->email) === strtolower($owner->email)) {
            return response()->json(['error' => 'You cannot invite yourself as a team member.'], 422);
        }

        // Try to find if user is already registered in Keeption Vault
        $registeredUser = User::where('email', $request->email)->first();

        $member = TeamMember::create([
            'owner_id'      => $ownerId,
            'user_id'       => $registeredUser ? $registeredUser->id : null,
            'invited_email' => strtolower($request->email),
            'name'          => $registeredUser ? $registeredUser->name : null,
            'role'          => $request->role,
            'status'        => $registeredUser ? 'active' : 'pending',
        ]);

        // Audit Log
        AuditController::log(
            $ownerId, 
            $ownerId, 
            $owner->name, 
            'invite', 
            "Invited team member " . $request->email . " with role " . ucfirst($request->role)
        );

        return response()->json([
            'success' => true,
            'member'  => [
                'id'            => $member->id,
                'name'          => $member->name ?: explode('@', $member->invited_email)[0],
                'email'         => $member->invited_email,
                'role'          => $member->role,
                'status'        => $member->status,
                'is_owner'      => false,
                'joined_date'   => $member->created_at->format('M d, Y'),
                'avatar_url'    => $member->user && $member->user->avatar ? '/avatars/' . $member->user->avatar : null,
            ]
        ]);
    }

    public function updateRole(Request $request)
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'id'   => 'required',
            'role' => 'required|in:admin,editor,viewer',
        ]);

        $owner = User::find($ownerId);

        // Can't edit owner
        if (strpos($request->id, 'owner_') === 0) {
            return response()->json(['error' => 'Cannot modify owner role.'], 403);
        }

        $member = TeamMember::where('owner_id', $ownerId)->where('id', $request->id)->first();
        if (!$member) {
            return response()->json(['error' => 'Team member not found.'], 404);
        }

        $oldRole = $member->role;
        $member->role = $request->role;
        $member->save();

        // Audit Log
        AuditController::log(
            $ownerId,
            $ownerId,
            $owner->name,
            'role_change',
            "Changed role of " . $member->invited_email . " from " . ucfirst($oldRole) . " to " . ucfirst($request->role)
        );

        return response()->json(['success' => true]);
    }

    public function removeMember($id)
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $owner = User::find($ownerId);

        // Can't delete owner
        if (strpos($id, 'owner_') === 0) {
            return response()->json(['error' => 'Cannot remove owner.'], 403);
        }

        $member = TeamMember::where('owner_id', $ownerId)->where('id', $id)->first();
        if (!$member) {
            return response()->json(['error' => 'Team member not found.'], 404);
        }

        $email = $member->invited_email;
        $member->delete();

        // Audit Log
        AuditController::log(
            $ownerId,
            $ownerId,
            $owner->name,
            'remove_member',
            "Removed team member " . $email
        );

        return response()->json(['success' => true]);
    }

    public function addSeat(Request $request)
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $owner = User::find($ownerId);
        
        // Add 1 seat
        $owner->seats = $owner->seats + 1;
        $owner->save();

        // Audit Log
        AuditController::log(
            $ownerId,
            $ownerId,
            $owner->name,
            'billing_change',
            "Added team seat. Total seats: " . $owner->seats . " ($8/seat/mo charged via Stripe)"
        );

        return response()->json([
            'success' => true,
            'seats'   => $owner->seats
        ]);
    }

    public function updateSettings(Request $request)
    {
        $ownerId = session('user_id');
        if (!$ownerId) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'teams_name' => 'required|string|max:100',
        ]);

        $owner = User::find($ownerId);
        $oldName = $owner->teams_name;
        $owner->teams_name = $request->teams_name;
        $owner->save();

        // Audit Log
        AuditController::log(
            $ownerId,
            $ownerId,
            $owner->name,
            'settings_change',
            "Renamed team from '" . $oldName . "' to '" . $request->teams_name . "'"
        );

        return response()->json([
            'success' => true,
            'teams_name' => $owner->teams_name
        ]);
    }
}
