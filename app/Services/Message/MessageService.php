<?php

namespace App\Services\Message;

use App\Models\Message\Message;
use App\Models\User\User;
use Illuminate\Support\Collection;

class MessageService
{
    /**
     * Recent conversations for the inbox (one row per other participant).
     *
     * @return Collection<int, object{peer: User, last_message: Message, unread_count: int}>
     */
    public function conversationSummariesForUser(int $userId): Collection
    {
        $messages = Message::query()
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
            })
            ->with(['sender:id,first_name,last_name,email', 'receiver:id,first_name,last_name,email'])
            ->orderByDesc('created_at')
            ->limit(500)
            ->get();

        $seenPeerIds = [];
        $rows = collect();

        foreach ($messages as $message) {
            $peerId = (int) $message->sender_id === $userId
                ? (int) $message->receiver_id
                : (int) $message->sender_id;

            if (isset($seenPeerIds[$peerId])) {
                continue;
            }
            $seenPeerIds[$peerId] = true;

            $peer = (int) $message->sender_id === $userId
                ? $message->receiver
                : $message->sender;

            if (!$peer) {
                continue;
            }

            $unreadCount = Message::query()
                ->where('sender_id', $peerId)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();

            $rows->push((object) [
                'peer' => $peer,
                'last_message' => $message,
                'unread_count' => $unreadCount,
            ]);
        }

        return $rows;
    }

    /**
     * @return Collection<int, Message>
     */
    public function threadBetween(int $authId, int $peerId, int $limit = 200): Collection
    {
        return Message::query()
            ->where(function ($q) use ($authId, $peerId) {
                $q->where(function ($q2) use ($authId, $peerId) {
                    $q2->where('sender_id', $authId)->where('receiver_id', $peerId);
                })->orWhere(function ($q2) use ($authId, $peerId) {
                    $q2->where('sender_id', $peerId)->where('receiver_id', $authId);
                });
            })
            ->with(['sender:id,first_name,last_name,email', 'receiver:id,first_name,last_name,email'])
            ->orderBy('created_at')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    public function markThreadReadForReceiver(int $receiverId, int $senderId): int
    {
        return Message::query()
            ->where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function deleteOwnMessage(int $authId, int $messageId): bool
    {
        $deleted = Message::query()
            ->whereKey($messageId)
            ->where('sender_id', $authId)
            ->delete();

        return $deleted > 0;
    }

    public function deleteOwnMessagesInThread(int $authId, int $peerId): int
    {
        return Message::query()
            ->where('sender_id', $authId)
            ->where('receiver_id', $peerId)
            ->delete();
    }
}
