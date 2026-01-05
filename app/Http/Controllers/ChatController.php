<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Group;


class ChatController extends Controller
{
    public function chat($user_id)
    {
        $users = User::all();
        $selectedUserQuery = User::where('user_id', $user_id);
        $selectedUser = $selectedUserQuery->firstOrFail();


        return view('chat.chat', compact('users', 'selectedUser'));
    }


    // public function send(Request $request)
    // {
    //     $request->validate([
    //         'receiver_id' => 'required|integer',
    //         'message' => 'required|string|min:1'
    //     ]);

    //     $msg = Message::create([
    //         'sender_id'   => auth()->id(),
    //         'receiver_id' => $request->receiver_id,
    //         'body'        => $request->message,
    //         'is_seen'     => false,
    //         'seen_at'     => null,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => $msg->body
    //     ]);
    // }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string|min:1'
        ]);

        $msg = Message::create([
            'sender_id'    => auth()->id(),
            'receiver_id'  => $request->receiver_id,
            'body'         => $request->message,
            'is_seen'      => false,
            'seen_at'      => null,
            'is_delivered' => true,
        ]);

        return response()->json([
            'status'  => true,
            'data'    => [
                'id'         => $msg->id,
                'body'       => $msg->body,
                'sender_id'  => $msg->sender_id,
                'created_at' => $msg->created_at->toDateTimeString(),
                'is_seen'    => $msg->is_seen,
                'is_delivered' => $msg->is_delivered
            ]
        ]);
    }

    // public function fetchMessages($userId)
    // {
    //     $authId = auth()->id();

    //     $messages = Message::with(['sender:id,name,avatar', 'receiver:id,name,avatar'])
    //         ->where(function ($q) use ($authId, $userId) {
    //             $q->where('sender_id', $authId)
    //                 ->where('receiver_id', $userId);
    //         })
    //         ->orWhere(function ($q) use ($authId, $userId) {
    //             $q->where('sender_id', $userId)
    //                 ->where('receiver_id', $authId);
    //         })
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     return response()->json($messages);
    // }


    public function fetchMessages($userId)
    {
        $authId = auth()->id();
        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_seen', 0)
            ->update(['is_seen' => 1]);

        $messages = Message::with([
            'sender:id,name,avatar',
            'receiver:id,name,avatar'
        ])
            ->where(function ($q) use ($authId, $userId) {
                $q->where(function ($q2) use ($authId, $userId) {
                    $q2->where('sender_id', $authId)
                        ->where('receiver_id', $userId);
                })
                    ->orWhere(function ($q2) use ($authId, $userId) {
                        $q2->where('sender_id', $userId)
                            ->where('receiver_id', $authId);
                    });
            })
            ->where(function ($q) {
                $q->whereNotNull('body')
                    ->orWhereNotNull('file');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }


    //     public function search(Request $request)
    // {
    //     // dd($request->all());
    //     $q = trim($request->q);

    //     if (!$q) {
    //         return response()->json([]);
    //     }

    //     $users = User::where('name', 'LIKE', "%{$q}%")
    //         ->orWhere('email', 'LIKE', "%{$q}%")
    //         ->select('id', 'name', 'email', 'avatar')
    //         ->limit(10)
    //         ->get();

    //     return response()->json($users);
    // }

    // public function search(Request $request)
    // {
    //     $q = trim($request->q);

    //     if (!$q) {
    //         return response()->json([]);
    //     }

    //     $users = User::query()
    //         ->where(function ($query) use ($q) {
    //             $query->where('name', 'LIKE', "%{$q}%")
    //                   ->orWhere('email', 'LIKE', "%{$q}%");
    //         })
    //         ->select('id', 'name', 'email', 'avatar') 
    //         ->limit(10)
    //         ->get();

    //     \Log::info('Chat search results', [
    //         'query' => $q,
    //         'count' => $users->count(),
    //         'users' => $users->toArray()
    //     ]);

    //     return response()->json($users);
    // }


    public function search(Request $request)
    {
        if (!$request->filled('q')) {
            return response()->json([]);
        }

        return User::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('email', 'like', '%' . $request->q . '%')
            ->select('id', 'name', 'email', 'avatar')
            ->limit(10)
            ->get();
    }

    // Create a new group
    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        $invites = $request->input('invites');
        if ($invites) {
            $emails = array_map('trim', explode(',', $invites));
            // You can add logic to send invites here
        }

        return redirect()
            ->route('chat.chat', $group->id)
            ->with('success', 'Group created successfully');
    }

    // Show the group chat page
    public function groupChat($group_id)
    {
        $groups = Group::all();
        $selectedGroup = Group::findOrFail($group_id);

        return view('chat.chat', compact('groups', 'selectedGroup'));
    }

    // Fetch messages for a group
    public function fetchGroupMessages($groupId)
    {
        $group = Group::findOrFail($groupId);
        $messages = $group->messages()->with('user')->get();
        return response()->json($messages);
    }


    public function sendGroupMessage(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'message' => 'required|string',
        ]);

        $message = $request->user()->messages()->create([
            'group_id' => $request->group_id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }


    // public function uploadFiles(Request $request)
    // {
    //     dd($request->all());
    //     if ($request->hasFile('file')) {

    //         $file = $request->file('file');
    //         $originalName = $file->getClientOriginalName();
    //         $newName = time() . '_' . $originalName;

    //         $file->move(public_path('assets/images'), $newName);

    //         DB::table('files')->insert([
    //             'file' => 'assets/images/' . $newName,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);

    //         return response()->json(['message' => 'File uploaded successfully']);
    //     }

    //     return response()->json(['message' => 'No file selected'], 400);
    // }

    public function sendFile(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'file'        => 'required|array',
            'file.*'      => 'file|max:10240',
        ]);

        $messages = [];

        foreach ($request->file('file') as $file) {

            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('assets/images'), $filename);

            $msg = Message::create([
                'sender_id'   => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'body'        => null,
                'file'        => $filename,
                'is_seen'     => false,
            ]);

            $messages[] = [
                'id'         => $msg->id,
                'file'       => asset('assets/images/' . $filename),
                'created_at' => $msg->created_at->toDateTimeString(),
            ];
        }

        return response()->json([
            'status'  => true,
            'message' => 'Files sent successfully',
            'data'    => $messages,
        ]);
    }
}
