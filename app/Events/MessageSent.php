<?php

namespace App\Events;

use App\Models\Message\Message;
use App\Models\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Message $message
    ) {
        $this->message->loadMissing(['sender:id,first_name,last_name,email']);
    }

    /**
     * @return array<int, \Illuminate\Broadcasting\PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->receiver_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        /** @var User|null $sender */
        $sender = $this->message->sender;

        return [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'receiver_id' => $this->message->receiver_id,
                'body' => $this->message->body,
                'read_at' => $this->message->read_at?->toIso8601String(),
                'created_at' => $this->message->created_at?->toIso8601String(),
            ],
            'sender' => [
                'id' => $sender?->id,
                'name' => $sender?->name,
                'avatar_url' => $sender?->avatar_url,
            ],
        ];
    }
}
