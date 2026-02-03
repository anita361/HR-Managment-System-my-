<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Events\VoiceCallIncoming;
use App\Models\GroupMessage;

use App\Models\Group;
use App\Models\Call;


class ChatController extends Controller
{
    public function chat($user_id)
    {
        $users = User::all();
        $selectedUserQuery = User::where('user_id', $user_id);
        $selectedUser = $selectedUserQuery->firstOrFail();
        $groups = Group::with('users')->get();


        $calls = Call::with(['caller', 'receiver'])
            ->where(function ($q) use ($selectedUser) {
                $q->where('caller_id', auth()->id())
                    ->where('receiver_id', $selectedUser->id);
            })
            ->orWhere(function ($q) use ($selectedUser) {
                $q->where('caller_id', $selectedUser->id)
                    ->where('receiver_id', auth()->id());
            })
            ->orderBy('started_at', 'desc')
            ->get();


        $files = Message::with('sender')
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->where(function ($q) use ($selectedUser) {
                $q->where('sender_id', auth()->id())
                    ->orWhere('sender_id', $selectedUser->id);
            })
            ->latest()
            ->get();


        $myFiles = Message::with('sender')
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->where('sender_id', auth()->id())
            ->latest()
            ->get();



        return view('chat.chat', compact('users', 'selectedUser', 'groups',  'calls', 'files',  'myFiles'));
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
            'status' => true,
            'data'   => $msg
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



// dd($request->all());

    public function delete(Request $request, $id)
    {

    // dd($request->all());
        $msg = Message::findOrFail($id);
        $userId = auth()->id();
        $forEveryone = $request->input('for_everyone', 0);

        if ($forEveryone) {

            if ($msg->sender_id != $userId) {
                return response()->json(['error' => 'Only the sender can delete for everyone'], 403);
            }

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




    // public function undoDelete($id)
    // {
    //     $msg = Message::findOrFail($id);
    //     $userId = auth()->id();

    //     $deletedFor = $msg->deleted_for ? json_decode($msg->deleted_for, true) : [];
    //     $deletedFor = array_diff($deletedFor, [$userId]);

    //     $msg->deleted_for = json_encode(array_values($deletedFor));
    //     $msg->save();

    //     return response()->json(['status' => true]);
    // }










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

    // public function sendVoiceCallSignal(Request $request)
    // {
    //     // Validate required fields
    //     $request->validate([
    //         'to' => 'required|exists:users,id',
    //         'data' => 'required'
    //     ]);

    //     // Broadcast the signal
    //     broadcast(new VoiceCallSignal($request->to, $request->data))->toOthers();

    //     return response()->json(['status' => 'ok']);
    // }


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







    // public function searchgrpmsg(Group $group, Request $request)
    // {
    //     $query = $request->q;

    //     if (!$query) {
    //         return response()->json([]); 
    //     }

    //     $messages = $group->messages()
    //         ->where('body', 'like', "%{$query}%")
    //         ->with('sender') 
    //         ->orderBy('created_at', 'asc')
    //         ->get()
    //         ->map(function ($msg) {
    //             return [
    //                 'id' => $msg->id,
    //                 'sender_name' => $msg->sender->name,
    //                 'sender_avatar' => $msg->sender->avatar ? asset('assets/images/' . $msg->sender->avatar) : asset('default-avatar.png'),
    //                 'time' => $msg->created_at->format('H:i, d M'),
    //                 'body' => $msg->body,
    //             ];
    //         });

    //     return response()->json($messages);
    // }


    public function chatGroup(Group $group)
    {

        $group->load([
            'users',
            'messages.user'
        ]);

        return view('chat.group_chat', compact('group'));
    }



    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'invites' => 'nullable|string',
        ]);


        $group = Group::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);


        $group->users()->attach(Auth::id(), ['created_by' => Auth::id()]);


        if ($request->filled('participants')) {
            foreach ($request->participants as $userId) {
                DB::table('group_user')->insert([
                    'group_id' => $group->id,
                    'user_id' => $userId,
                    'created_by' => Auth::id(),
                ]);
            }
        }


        if ($request->filled('invites')) {
            $emails = array_map('trim', explode(',', $request->invites));
            $userIds = User::whereIn('email', $emails)->pluck('id')->toArray();

            foreach ($userIds as $userId) {
                DB::table('group_user')->insert([
                    'group_id' => $group->id,
                    'user_id' => $userId,
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }


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

    public function groupChat(Group $group)
    {
        if (! $group->users->contains(auth()->id())) {
            abort(403);
        }


        $group->load('messages.sender');

        return view('chat.group_chat', compact('group'));
    }


    public function sendGroupMessage(Request $request, Group $group)
    {
        if (! $group->users->contains(auth()->id())) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        GroupMessage::create([
            'group_id'  => $group->id,
            'sender_id' => auth()->id(),
            'body'      => $request->message,
        ]);

        return redirect()->route('group.chat', $group->id);
    }



    public function fetchGroupMessages(Request $request, $groupId)
    {
        $authId = auth()->id();
        $lastId = $request->query('last_id');


        $group = Group::where('id', $groupId)
            ->whereHas('users', function ($q) use ($authId) {
                $q->where('users.id', $authId);
            })
            ->firstOrFail();


        $query = GroupMessage::with('sender:id,name,avatar')
            ->where('group_id', $groupId)
            ->where(function ($q) {
                $q->whereNotNull('body')
                    ->orWhereNotNull('file');
            });

        if ($lastId) {
            $query->where('id', '>', $lastId);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }

    public function groupmsgsearch(Request $request, Group $group)
    {
        $q = $request->q;

        if (!$q) {
            return response()->json([]);
        }

        return $group->messages()
            ->where('body', 'LIKE', "%{$q}%")
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    public function updateGroupMessage(Request $request, $id)
    {

        $message = GroupMessage::findOrFail($id);

        if ($message->sender_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate(['body' => 'required|string']);

        $message->body = $request->body;
        $message->edited_at = now();
        $message->save();

        return response()->json(['status' => true]);
    }

    public function deleteGroupMessage(Request $request, $id)
    {

        $message = GroupMessage::findOrFail($id);
        $forEveryone = (int) $request->input('for_everyone', 0);

        if ($forEveryone === 1) {
           
            if ($message->sender_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $message->is_deleted = 1;
            $message->save();
        } else {
            
            $deletedFor = $message->deleted_for ?? [];
            if (!is_array($deletedFor)) $deletedFor = json_decode($deletedFor, true) ?? [];

            if (!in_array(auth()->id(), $deletedFor)) $deletedFor[] = auth()->id();
            $message->deleted_for = json_encode(array_values($deletedFor));
            $message->save();
        }

        return response()->json(['status' => true]);
    }

    public function searchgrpmsg(Group $group, Request $request)
    {
        $query = $request->q;

        if (!$query) {
            return response()->json([]);
        }

        $messages = $group->messages()
            ->where('body', 'like', "%{$query}%")
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_name' => $msg->sender->name,
                    'sender_avatar' => $msg->sender->avatar ? asset('assets/images/' . $msg->sender->avatar) : asset('default-avatar.png'),
                    'time' => $msg->created_at->format('H:i, d M'),
                    'body' => $msg->body,
                ];
            });

        return response()->json($messages);
    }

    // public function uploadGroupFiles(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'group_id' => 'required|exists:groups,id',
    //         'file.*'   => 'required|file|max:20480',
    //     ]);

    //     $filesData = [];

    //     foreach ($request->file('file') as $file) {
    //         $path = $file->store('group_files', 'public');

    //         $msg = GroupMessage::create([
    //             'group_id'  => $request->group_id,
    //             'sender_id' => auth()->id(),
    //             'body'      => null,
    //             'file'      => $path,
    //         ]);


    //         $msg->load('sender');

    //         $filesData[] = $msg;
    //     }

    //     return response()->json([
    //         'message' => 'Files uploaded successfully',
    //         'files'   => $filesData,  
    //     ]);
    // }


    public function uploadGroupFiles(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'file'     => 'required|array',
            'file.*'   => 'file|max:10240',
        ]);

        $group = \App\Models\Group::findOrFail($request->group_id);

        if (! $group->users->contains(auth()->id())) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $messages = [];

        foreach ($request->file('file') as $file) {

            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();


            $path = $file->storeAs('group_files', $filename, 'public');

            $msg = \App\Models\GroupMessage::create([
                'group_id'   => $group->id,
                'sender_id'  => auth()->id(),
                'body'       => null,
                'file_path'  => 'storage/' . $path,
                'file_type'  => $file->getMimeType(),
                'file_name'  => $file->getClientOriginalName(),
                'is_deleted' => false,
            ]);

            $messages[] = [
                'id'         => $msg->id,
                'file_url'   => asset($msg->file_path),
                'file_type'  => $msg->file_type,
                'file_name'  => $msg->file_name,
                'created_at' => $msg->created_at->toDateTimeString(),
            ];
        }

        return response()->json([
            'status'  => true,
            'message' => 'Files sent successfully',
            'data'    => $messages,
        ]);
    }




    public function getGroupFiles($groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->users->contains(auth()->id())) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $files = GroupMessage::with('sender:id,name,avatar')
            ->where('group_id', $groupId)
            ->whereNotNull('file')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($files);
    }








    public function profile(Group $group)
    {
        abort_if(! $group->users->contains(auth()->id()), 403);

        $group->load('users', 'messages.sender');

        return view('groups.profile', compact('group'));
    }



    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }

    public function members(Group $group)
    {
        $members = $group->users;


        $userIds = $members->pluck('id')->toArray();
        $users = User::whereNotIn('id', $userIds)->get();

        return view('groups.members', compact('group', 'members', 'users'));
    }


    public function leave(Group $group)
    {
        $group->users()->detach(auth()->id());
        return redirect()->route('groups.index')->with('success', 'You left the group.');
    }



    public function index()
    {

        $groups = auth()->user()->groups()->get();

        return view('groups.index', compact('groups'));
    }


    public function addMemberForm(Group $group)
    {

        $membersIds = $group->users->pluck('id')->toArray();
        $users = User::whereNotIn('id', $membersIds)->get();

        return view('groups.add-member', compact('group', 'users'));
    }

    public function addMember(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);


        $group->users()->attach($request->user_id);

        return redirect()->route('groups.members', $group->id)
            ->with('success', 'Member added successfully!');
    }



    public function updateAvatar(Request $request, Group $group)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($group->avatar && file_exists(public_path('assets/images/' . $group->avatar))) {
            unlink(public_path('assets/images/' . $group->avatar));
        }


        $fileName = time() . '_' . $request->avatar->getClientOriginalName();
        $request->avatar->move(public_path('assets/images'), $fileName);

        $group->avatar = $fileName;
        $group->save();

        return redirect()->back()->with('success', 'Group avatar updated successfully!');
    }

    public function sendGroupFile(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'file'     => 'required|array',
            'file.*'   => 'file|max:10240',
        ]);

        $group = \App\Models\Group::findOrFail($request->group_id);

        // Make sure user is in group
        if (! $group->users->contains(auth()->id())) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $messages = [];

        foreach ($request->file('file') as $file) {

            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $filename);

            $msg = \App\Models\GroupMessage::create([
                'group_id'   => $group->id,
                'sender_id'  => auth()->id(),
                'body'       => null,
                'file'       => $filename,
                'is_deleted' => false,
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

    public function fetchFiles($receiverId)
    {
        $authId = auth()->id();

        // Get all messages with files between auth user and receiver
        $files = Message::with('sender')
            ->where(function ($q) use ($authId, $receiverId) {
                $q->where('sender_id', $authId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($authId, $receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', $authId);
            })
            ->whereNotNull('file')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'file' => $msg->file,
                    'sender_id' => $msg->sender_id,
                    'sender' => $msg->sender,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ];
            });

        return response()->json($files);
    }


    // public function updateChatUserAvatar(Request $request)
    // {
    //     // dd($request->all());
    //     $request->validate([
    //         'avatar'  => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
    //         'user_id' => 'required'
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $user = User::findOrFail($request->user_id);

    //         $image_name = $user->avatar;

    //         if ($request->hasFile('avatar')) {

    //             if ($image_name && $image_name !== 'photo_defaults.jpg') {
    //                 $oldPath = public_path('assets/images/' . $image_name);
    //                 if (file_exists($oldPath)) {
    //                     unlink($oldPath);
    //                 }
    //             }


    //             $file = $request->file('avatar');
    //             $image_name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('assets/images'), $image_name);
    //         }


    //         $user->avatar = $image_name;
    //         $user->save();

    //         DB::commit();


    //         return response()->json([
    //             'status' => 'success',
    //             'image_name' => $image_name
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Chat user avatar update failed', ['error' => $e->getMessage()]);
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function updateChatUserAvatar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = User::findOrFail($request->user_id);

        $filename = time() . '_' . $request->avatar->getClientOriginalName();
        $request->avatar->move(public_path('assets/images'), $filename);

        $user->avatar = $filename;
        $user->save();

        return response()->json(['success' => true]);
    }



    public function deleteConversations()
    {
        Message::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->delete();

        return redirect()->back()
            ->with('success', 'All conversations deleted permanently.');
    }

    public function startGroupCall($groupId)
    {
        $group = \App\Models\Group::findOrFail($groupId);


        return view('chat.group_chat', compact('group'));
    }




    public function groupCallSignal(Request $request)
    {
        broadcast(new GroupCallSignal(
            auth()->id(),
            $request->group_id,
            $request->to,
            $request->signal
        ))->toOthers();

        return response()->json(['status' => 'ok']);
    }

    public function startGroupVideoCall($groupId)
    {
        $group = \App\Models\Group::findOrFail($groupId);


        return view('chat.group_chat', compact('group'));
    }

    public function deletegrpAllConversations($groupId)
    {
        $group = Group::findOrFail($groupId);


        $group->messages()->delete();

        return redirect()->back()->with('success', 'All group conversations deleted successfully.');
    }
}
