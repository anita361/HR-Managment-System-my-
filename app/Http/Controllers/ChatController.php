<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class ChatController extends Controller
{
    public function chat($user_id)
    {
        $users = User::all();
        $selectedUser = User::findOrFail($user_id);

        return view('chat.chat', compact('users', 'selectedUser'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string|min:1'
        ]);

        $msg = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'body'        => $request->message
        ]);

        return response()->json([
            'status' => true,
            'message' => $msg->body
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $users = User::where('id', '!=', auth()->id())
            ->where('name', 'like', "%$q%")
            ->get();

        return response()->json($users);
    }

    public function fetchMessages($userId)
{
    $authId = Auth::id();

    $messages = Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)
              ->where('receiver_id', $userId);
        })
        ->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)
              ->where('receiver_id', $authId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return view('chat.chat', compact('messages', 'userId'));
}
}
