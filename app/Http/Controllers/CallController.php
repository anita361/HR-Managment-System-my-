<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Call;
use App\Models\User;
use App\Models\Group;
use App\Models\Message; 

class CallController extends Controller
{

    public function startCall(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'type'        => 'required|in:voice,video',
        ]);

        $call = Call::create([
            'caller_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'type'        => $request->type,
            'status'      => Call::STATUS_ONGOING,
            'started_at'  => now(),
        ]);

        return response()->json([
            'success' => true,
            'call_id' => $call->id,
        ]);
    }


    public function endCall(Request $request)
    {
        $request->validate([
            'call_id' => 'required|exists:calls,id',
            'missed'  => 'nullable|boolean',
        ]);

        $call = Call::findOrFail($request->call_id);


        if (!in_array(auth()->id(), [$call->caller_id, $call->receiver_id])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $endedAt  = now();
        $duration = $call->started_at ? $endedAt->diffInSeconds($call->started_at) : 0;

        $call->update([
            'status'   => $request->missed ? Call::STATUS_MISSED : Call::STATUS_ENDED,
            'ended_at' => $endedAt,
            'duration' => $duration,
        ]);

        return response()->json([
            'success'             => true,
            'status'              => $call->status,
            'duration'            => $call->duration,
            'formatted_duration'  => $call->formatted_duration,
        ]);
    }


    // public function voiceCall(User $user)
    // {
    //     $users        = User::all();
    //     $selectedUser = $user;
    //     $groups       = Group::with('users')->get();

    //     $calls = Call::with(['caller', 'receiver'])
    //         ->betweenUsers(auth()->id(), $selectedUser->id)
    //         ->orderBy('started_at', 'desc')
    //         ->get();

    //     return view('chat.chat', compact('users', 'selectedUser', 'groups', 'calls'));
    // }

    public function voiceCall(User $user)
    {
        $users        = User::all();
        $selectedUser = $user;
        $groups       = Group::with('users')->get();


        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();


        $calls = Call::with(['caller', 'receiver'])
            ->betweenUsers(auth()->id(), $user->id)
            ->orderBy('started_at', 'asc')
            ->get();

        return view('chat.chat', compact('users', 'selectedUser', 'groups', 'messages', 'calls'));
    }



    public function videoCall(User $user)
    {
        return $this->voiceCall($user);
    }


    public function getCallsForUser(User $user)
    {
        $calls = Call::with(['caller', 'receiver'])
            ->betweenUsers(auth()->id(), $user->id)
            ->orderBy('started_at', 'desc')
            ->get();

        return response()->json($calls);
    }
}
