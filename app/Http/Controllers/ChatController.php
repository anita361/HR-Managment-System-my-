<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    /** Show chat */
    public function chatWith()
    {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)->orderBy('name')->get();

        return view('chat.chat', compact('user', 'users'));
    }

    /** Add a chat user (create user record) */
    public function addChatUser(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email ?? null,
            'password'  => bcrypt('password'),
            'role_name' => $request->role_name ?? 'Employee',
        ]);

        return response()->json([
            'status' => 'success',
            'user'   => $user,
        ]);
    }

    /** Get messages between logged-in user and another user (ordered oldest -> newest) */
    public function messages($receiverId)
    {
        $me = Auth::id();

        $messages = Message::where(function ($q) use ($me, $receiverId) {
            $q->where('sender_id', $me)->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($q) use ($me, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $me);
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    /** Send a message (single sendMessage method) */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
        ]);

        // return the created message (and optionally the sender relation)
        return response()->json([
            'status'  => 'success',
            'message' => $message,
        ]);
    }
}
