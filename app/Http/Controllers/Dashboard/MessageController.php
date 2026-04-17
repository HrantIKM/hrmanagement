<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\MessageSent;
use App\Http\Requests\Message\MessageStoreRequest;
use App\Models\Message\Message;
use App\Models\User\User;
use App\Services\Message\MessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends BaseController
{
    public function __construct(
        protected MessageService $messageService
    ) {
    }

    public function index(): View
    {
        view()->share('subHeaderData', ['pageName' => 'message.inbox']);

        return $this->dashboardView('message.inbox', $this->inboxVars(null));
    }

    public function thread(User $user): View
    {
        abort_if((int) $user->id === (int) auth()->id(), 404);

        view()->share('subHeaderData', ['pageName' => 'message.inbox']);

        return $this->dashboardView('message.inbox', $this->inboxVars($user));
    }

    /**
     * @return array<string, mixed>
     */
    private function inboxVars(?User $peer): array
    {
        $authId = (int) auth()->id();

        $otherUserId = User::query()->whereKeyNot($authId)->value('id');
        // Replace only the trailing user id in the path — never str_replace the raw id on the
        // full URL (e.g. peer id "1" would turn 127.0.0.1 into 127.0.0.__PEER_ID__).
        $historyUrlTemplate = $otherUserId
            ? (string) preg_replace(
                '#(/messages/history/)\d+(\?.*)?$#',
                '$1__PEER_ID__$2',
                route('dashboard.messages.history', ['user' => $otherUserId], false)
            )
            : '';
        $threadUrlTemplate = $otherUserId
            ? (string) preg_replace(
                '#(/messages/)\d+(\?.*)?$#',
                '$1__PEER_ID__$2',
                route('dashboard.messages.thread', ['user' => $otherUserId], false)
            )
            : '';

        return [
            'conversations' => $this->messageService->conversationSummariesForUser($authId),
            'peer' => $peer,
            'peerId' => $peer?->id,
            'usersForChat' => User::query()
                ->whereKeyNot($authId)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->limit(300)
                ->get(['id', 'first_name', 'last_name', 'email']),
            'broadcastConfig' => $this->broadcastClientConfig(),
            'messageStoreUrl' => route('dashboard.messages.store', [], false),
            'historyUrlTemplate' => $historyUrlTemplate,
            'threadUrlTemplate' => $threadUrlTemplate,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function broadcastClientConfig(): array
    {
        $default = config('broadcasting.default');
        // Path-only URL so Echo authorizes on the same origin as the dashboard (avoids
        // APP_URL host mismatch: e.g. 127.0.0.1 vs localhost → private channel never subscribes).
        $base = [
            'driver' => $default,
            'authEndpoint' => '/broadcasting/auth',
            'userId' => auth()->id(),
        ];

        if ($default === 'reverb') {
            $conn = config('broadcasting.connections.reverb', []);
            $opts = $conn['options'] ?? [];
            $scheme = $opts['scheme'] ?? 'https';
            $useTls = (bool) ($opts['useTLS'] ?? ($scheme === 'https'));
            $port = (int) ($opts['port'] ?? ($useTls ? 443 : 80));
            $wsHost = request()->getHost() ?: ($opts['host'] ?? 'localhost');

            return array_merge($base, [
                'key' => $conn['key'] ?? null,
                'wsHost' => $wsHost,
                'wsPort' => $port,
                'wssPort' => $port,
                'forceTLS' => $useTls,
            ]);
        }

        $pusher = config('broadcasting.connections.pusher', []);

        return array_merge($base, [
            'key' => $pusher['key'] ?? null,
            'cluster' => $pusher['options']['cluster'] ?? 'mt1',
        ]);
    }

    public function history(User $user): JsonResponse
    {
        abort_if((int) $user->id === (int) auth()->id(), 404);

        $authId = (int) auth()->id();
        $this->messageService->markThreadReadForReceiver($authId, (int) $user->id);

        $messages = $this->messageService->threadBetween($authId, (int) $user->id);

        return response()->json([
            'messages' => $messages->map(fn ($m) => $this->serializeMessage($m, $authId)),
        ]);
    }

    public function store(MessageStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $message = new \App\Models\Message\Message([
            'sender_id' => auth()->id(),
            'receiver_id' => (int) $data['receiver_id'],
            'body' => $data['body'],
        ]);
        $message->save();
        $message->load(['sender:id,first_name,last_name,email']);

        if (config('broadcasting.default') !== 'null') {
            event(new MessageSent($message));
        }

        return response()->json([
            'message' => $this->serializeMessage($message, (int) auth()->id()),
        ], 201);
    }

    public function markRead(Request $request, User $user): JsonResponse
    {
        abort_if((int) $user->id === (int) auth()->id(), 404);

        $updated = $this->messageService->markThreadReadForReceiver((int) auth()->id(), (int) $user->id);

        return response()->json(['updated' => $updated]);
    }

    public function destroy(Message $message): JsonResponse
    {
        abort_unless((int) $message->sender_id === (int) auth()->id(), 403);

        $deleted = $this->messageService->deleteOwnMessage((int) auth()->id(), (int) $message->id);

        return response()->json(['deleted' => $deleted]);
    }

    public function destroyThread(User $user): JsonResponse
    {
        abort_if((int) $user->id === (int) auth()->id(), 404);

        $deletedCount = $this->messageService->deleteOwnMessagesInThread((int) auth()->id(), (int) $user->id);

        return response()->json(['deleted' => $deletedCount]);
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeMessage(\App\Models\Message\Message $m, int $authId): array
    {
        return [
            'id' => $m->id,
            'sender_id' => $m->sender_id,
            'receiver_id' => $m->receiver_id,
            'body' => $m->body,
            'read_at' => $m->read_at?->toIso8601String(),
            'created_at' => $m->created_at?->toIso8601String(),
            'is_mine' => (int) $m->sender_id === $authId,
            'sender' => $m->sender ? [
                'id' => $m->sender->id,
                'name' => $m->sender->name,
                'avatar_url' => $m->sender->avatar_url,
            ] : null,
        ];
    }
}
