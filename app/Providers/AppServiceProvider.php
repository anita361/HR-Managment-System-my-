<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();

                // Latest 10 notifications
                $notifications = Notification::with('user')
                    ->where('user_id', $userId)
                    ->latest()
                    ->take(10)
                    ->get();

                // Count unread notifications
                $unreadCount = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();

                // Latest 10 messages
                $messages = Message::with('sender')
                    ->where('receiver_id', $userId)
                    ->where('is_deleted', false)
                    ->latest()
                    ->take(10)
                    ->get();

                // Count unread messages
                $messageUnreadCount = Message::where('receiver_id', $userId)
                    ->where('is_seen', false)
                    ->count();

                $view->with(compact('notifications', 'unreadCount', 'messages', 'messageUnreadCount'));
            }
        });
    }
}
