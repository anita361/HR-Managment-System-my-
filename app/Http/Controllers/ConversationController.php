<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;
use App\Models\Message;

class ConversationController extends Controller
{
    // public function show($id)
    // {
    //     $conversation = Conversation::with(['participants.user','messages.user'])->findOrFail($id);

    //     // 🔥 FIX 1: participants()->pluck() not loaded unless explicit
    //     if (! $conversation->participants->pluck('user_id')->contains(Auth::id())) {
    //         abort(403, 'Unauthorized');
    //     }

    //     return view('chat.chat', compact('conversation'));
    // }

    
public function show($id)
{
    $conversation = Conversation::with(['participants.user','messages.user'])->findOrFail($id);

    if (! $conversation->participants->pluck('user_id')->contains(Auth::id())) {
        abort(403,'Unauthorized');
    }

    
    $recentConversations = Conversation::whereHas('participants', function($q){
            $q->where('user_id', Auth::id());
        })
        ->with('participants.user')
        ->orderBy('updated_at', 'desc')
        ->get();

    return view('chat.chat', compact('conversation','recentConversations'));
}


    public function messagesJson($id)
    {
        $conversation = Conversation::with(['messages.user', 'participants'])->findOrFail($id);

        // 🔥 FIX 2: participants were not loaded -> unauthorized every time
        if (! $conversation->participants->pluck('user_id')->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $conversation->messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->user_id,
                    'sender_name' => $msg->user->name,
                    'body' => $msg->body,
                    'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                ];
            })
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);

        $conversation = Conversation::with('participants')->findOrFail($id);

        if (! $conversation->participants->pluck('user_id')->contains(Auth::id())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'body' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->user_id,
                'sender_name' => $message->user->name,
                'body' => $message->body,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
