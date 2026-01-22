<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $notifications = Notification::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('chat.chat', compact('notifications', 'unreadCount'));
    }

    public function clearAll()
    {
        Notification::where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found');
        }

        $notification->update(['is_read' => true]);

        return redirect($notification->url ?? back());
    }

    public function markAllRead()
    {
        Message::where('receiver_id', Auth::id())->update(['is_seen' => true]);
        return redirect()->back();
    }
}
