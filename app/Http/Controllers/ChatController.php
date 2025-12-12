<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function chat()
    {
        $recentConversations = Conversation::whereHas('participants', function ($q) {
            $q->where('user_id', Auth::id());
        })->with(['participants.user', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $users = User::where('id', '!=', Auth::id())->limit(20)->get();

        return view('chat.chat', [
            'users' => User::where('id', '!=', Auth::id())->limit(20)->get(),
            'recentConversations' => $recentConversations
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q', '');

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'avatar']);


        return response()->json(['users' => $users]);
    }

    public function startDirectChat(Request $request)
    {
        // validate input
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $userId = (int) $request->user_id;
        $me = Auth::id();

        // prevent chatting with yourself
        if ($me === $userId) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot start a chat with yourself.'
            ], 422);
        }

        // find existing direct conversation
        $conversation = Conversation::where('is_group', false)
            ->whereHas('participants', function ($q) use ($me) {
                $q->where('user_id', $me);
            })
            ->whereHas('participants', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with('participants.user')
            ->first();

        // create conversation if not found
        if (! $conversation) {
            $conversation = Conversation::create([
                'title' => null,
                'is_group' => false,
            ]);

            $conversation->participants()->createMany([
                ['user_id' => $me],
                ['user_id' => $userId],
            ]);

            $conversation->load('participants.user');
        }

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'conversation' => $conversation,
        ]);
    }
}
