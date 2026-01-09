<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

class VoiceCallIncoming implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $caller;
    public $receiver;

    public function __construct(User $caller, User $receiver)
    {
        $this->caller = $caller;
        $this->receiver = $receiver;
    }

    public function broadcastOn()
    {
        return new Channel('voice-call.' . $this->receiver->id);
    }

    public function broadcastWith()
    {
        return [
            'caller_id'   => $this->caller->id,
            'caller_name' => $this->caller->name,
            'receiver_id' => $this->receiver->id,
        ];
    }
}
