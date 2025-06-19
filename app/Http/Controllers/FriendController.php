<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FriendRequest;
use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $currentUser = auth()->user();
        
        $users = User::where('id', '!=', $currentUser->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->get();
        
        return view('friends.search', compact('users'));
    }

    public function sendRequest(User $user)
    {
        $currentUser = auth()->user();

        // Check if already friends
        if ($currentUser->isFriendWith($user)) {
            return back()->with('error', 'Already friends!');
        }

        // Check for existing requests
        if ($currentUser->hasPendingFriendRequestFrom($user)) {
            return back()->with('error', 'Friend request already received!');
        }

        if ($currentUser->hasSentFriendRequestTo($user)) {
            return back()->with('error', 'Friend request already sent!');
        }

        // Create new request
        FriendRequest::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Friend request sent!');
    }

    public function accept(FriendRequest $request)
    {
        if ($request->receiver_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $request->update(['status' => 'accepted']);

        // Create bidirectional friendship
        $now = now();
        
        // Create friendship for receiver -> sender
        auth()->user()->friends()->attach($request->sender_id, [
            'created_at' => $now,
            'updated_at' => $now
        ]);
        
        // Create friendship for sender -> receiver
        User::find($request->sender_id)->friends()->attach(auth()->id(), [
            'created_at' => $now,
            'updated_at' => $now
        ]);

        return back()->with('success', 'Friend request accepted!');
    }

    public function reject(FriendRequest $request)
    {
        if ($request->receiver_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $request->update(['status' => 'rejected']);
        return back()->with('success', 'Friend request rejected!');
    }

    public function requests()
    {
        $receivedRequests = auth()->user()
            ->receivedFriendRequests()
            ->where('status', 'pending')
            ->with('sender')
            ->get();
        
        return view('friends.requests', compact('receivedRequests'));
    }
}
