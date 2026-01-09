<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Events\VoiceCallIncoming;

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






    public function fetchMessages(Request $request, $userId)
    {
        $authId = auth()->id();


        $lastId = $request->query('last_id');


        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_seen', 0)
            ->update(['is_seen' => 1]);


        $query = Message::with([
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
            });


        if ($lastId) {
            $query->where('id', '>', $lastId);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function updateMessage(Request $request, $id)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $message = Message::where('id', $id)
            ->where('sender_id', auth()->id())
            ->firstOrFail();

        $message->body = $request->body;
        $message->save();

        return response()->json(['success' => true]);
    }



    public function delete(Request $request, $id)
    {
        $msg = Message::findOrFail($id);
        $userId = auth()->id();
        $forEveryone = $request->input('for_everyone', 0);


        if ($msg->sender_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($forEveryone) {

            $msg->is_deleted = true;
            $msg->save();
        } else {

            $deletedFor = $msg->deleted_for ? json_decode($msg->deleted_for, true) : [];
            $deletedFor[] = $userId;
            $msg->deleted_for = json_encode(array_unique($deletedFor));
            $msg->save();
        }

        return response()->json(['status' => true]);
    }




    public function searchMessages(Request $request)
    {
        $query = trim($request->query('query'));

        if (!$query) {
            return response()->json([]);
        }

        $authId = auth()->id();

        $messages = Message::with([
            'sender:id,name,avatar',
            'receiver:id,name,avatar'
        ])
            ->whereNotNull('body')
            ->where('body', 'LIKE', "%{$query}%")
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                    ->orWhere('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }



    public function undoDelete($id)
    {
        $msg = Message::findOrFail($id);
        $userId = auth()->id();

        $deletedFor = $msg->deleted_for ? json_decode($msg->deleted_for, true) : [];
        $deletedFor = array_diff($deletedFor, [$userId]);

        $msg->deleted_for = json_encode(array_values($deletedFor));
        $msg->save();

        return response()->json(['status' => true]);
    }


    // public function voiceCall($receiver)
    // {
    //     $receiverUser = User::findOrFail($receiver);
    //     $callerUser   = auth()->user();


    //     event(new VoiceCallIncoming($callerUser, $receiverUser));


    //     return view('chat.chat', [
    //         'caller'   => $callerUser,
    //         'receiver' => $receiverUser
    //     ]);
    // }

    public function sendVoiceCallSignal(Request $request)
    {
        // Validate required fields
        $request->validate([
            'to' => 'required|exists:users,id',
            'data' => 'required'
        ]);

        // Broadcast the signal
        broadcast(new VoiceCallSignal($request->to, $request->data))->toOthers();

        return response()->json(['status' => 'ok']);
    }


    public function fetchChatFiles($userId)
    {
        $authId = auth()->id();

        $files = Message::with('sender:id,name')
            ->whereNotNull('file')
            ->where(function ($q) use ($authId, $userId) {
                $q->where([
                    ['sender_id', $authId],
                    ['receiver_id', $userId]
                ])->orWhere([
                    ['sender_id', $userId],
                    ['receiver_id', $authId]
                ]);
            })
            ->latest()
            ->get();

        return response()->json($files);
    }







    public function search(Request $request)
    {
        // dd($request->all());
        $q = $request->input('q');

        if (!$q || strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', auth()->id())
            ->where('name', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }


    public function chatGroup(Group $group)
    {
        // Load users and messages in the group
        $group->load('users', 'messages');

        return view('chat.chat', compact('group'));
    }



    // Create a new group
    // public function createGroup(Request $request)
    // {
    //     // Validate input
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'invites' => 'nullable|string',
    //     ]);

    //     // Create the group
    //     $group = Group::create([
    //         'name' => $request->name,
    //         'created_by' => Auth::id(),
    //     ]);

    //     // Attach the creator to the group
    //     $group->users()->attach(Auth::id());

    //     // Attach invited users if any
    //     $invites = $request->input('invites');
    //     if ($invites) {
    //         $emails = array_map('trim', explode(',', $invites));
    //         $users = User::whereIn('email', $emails)->pluck('id')->toArray();
    //         if ($users) {
    //             $group->users()->attach($users);
    //         }
    //     }


    //     return redirect()
    //         ->back()  
    //         ->with('success', 'Group created successfully!');
    // }


    public function createGroup(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'invites' => 'nullable|string',
        'members' => 'nullable|array', // <-- selected users from modal
        'members.*' => 'integer|exists:users,id',
    ]);

    // Create the group
    $group = Group::create([
        'name' => $request->name,
        'created_by' => Auth::id(),
    ]);

    // Attach the creator
    $group->users()->attach(Auth::id());

    // Attach members selected from modal
    if ($request->filled('members')) {
        $group->users()->syncWithoutDetaching($request->members);
    }

    // Attach invited users by email
    $invites = $request->input('invites');
    if ($invites) {
        $emails = array_map('trim', explode(',', $invites));
        $users = User::whereIn('email', $emails)->pluck('id')->toArray();
        if ($users) {
            $group->users()->syncWithoutDetaching($users);
        }
    }

    // Return JSON if AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
            ],
        ]);
    }

    return redirect()->back()->with('success', 'Group created successfully!');
}


    // public function addMembersToGroup(Request $request, $groupId)
    // {
    //     $request->validate([
    //         'emails' => 'required|string',
    //     ]);

    //     $group = Group::findOrFail($groupId);

    //     $emails = array_map('trim', explode(',', $request->emails));

    //     // Fetch existing users by email
    //     $users = User::whereIn('email', $emails)->pluck('id')->toArray();

    //     if ($users) {
    //         $group->users()->syncWithoutDetaching($users); // adds without removing existing members
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Members added successfully!',
    //         'added_users' => $users
    //     ]);
    // }





    public function groupChat($group_id)
    {
        $groups = Group::all();
        $selectedGroup = Group::findOrFail($group_id);

        return view('chat.chat', compact('groups', 'selectedGroup'));
    }


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
