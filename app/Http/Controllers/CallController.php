<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Call;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Group;

class CallController extends Controller
{
    // Start a call
    public function startCall(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'type' => 'required|in:voice,video',
        ]);

        $call = Call::create([
            'caller_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'type' => $request->type,
            'status' => 'ongoing',
            'started_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'call_id' => $call->id,
        ]);
    }

    // End a call
    public function endCall(Request $request)
    {
        $request->validate([
            'call_id' => 'required|exists:calls,id',
            'missed' => 'nullable|boolean',
        ]);

        $call = Call::findOrFail($request->call_id);
        $endedAt = now();
        $duration = $call->started_at ? $endedAt->diffInSeconds($call->started_at) : 0;

        $call->update([
            'status' => $request->missed ? 'missed' : 'ended',
            'ended_at' => $endedAt,
            'duration' => $duration,
        ]);

        return response()->json([
            'success' => true,
            'status' => $call->status,
            'duration' => $call->duration,
        ]);
    }

    // Voice call UI
   public function voiceCall($userId)
{
    $users = User::all();
    $selectedUser = User::findOrFail($userId);
    $groups = Group::with('users')->get();
    $calls = Call::with(['caller','receiver'])
        ->where(function($q) use ($selectedUser){
            $q->where('caller_id', auth()->id())
              ->where('receiver_id', $selectedUser->id);
        })
        ->orWhere(function($q) use ($selectedUser){
            $q->where('caller_id', $selectedUser->id)
              ->where('receiver_id', auth()->id());
        })
        ->orderBy('started_at','desc')
        ->get();

    return view('chat.chat', compact('users','selectedUser','groups','calls'));
}
public function getCallsForUser($userId)
{
    $calls = Call::with(['caller', 'receiver'])
        ->where(function ($q) use ($userId) {
            $q->where('caller_id', auth()->id())
              ->where('receiver_id', $userId);
        })
        ->orWhere(function ($q) use ($userId) {
            $q->where('caller_id', $userId)
              ->where('receiver_id', auth()->id());
        })
        ->orderBy('started_at', 'desc')
        ->get();

    return response()->json($calls);
}


public function videoCall($userId)
{
    return $this->voiceCall($userId); // Reuse the same method
}

}
