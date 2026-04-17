<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Private channel "chat.{receiverId}" — only that user may subscribe (incoming
| messages addressed to them).
|
*/

Broadcast::channel('chat.{receiverId}', function ($user, string $receiverId) {
    return (int) $user->id === (int) $receiverId;
});
