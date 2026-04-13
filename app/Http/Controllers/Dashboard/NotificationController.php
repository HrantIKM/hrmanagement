<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends BaseController
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->paginate(25);

        return $this->dashboardView('notification.index', [
            'notifications' => $notifications,
        ]);
    }

    public function recent(Request $request): JsonResponse
    {
        $user = $request->user();
        $items = $user->notifications()
            ->take(20)
            ->get()
            ->map(fn ($n) => $this->serializeNotification($n));

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'items' => $items,
        ]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'OK',
                'unread_count' => $request->user()->unreadNotifications()->count(),
            ]);
        }

        return redirect()->back();
    }

    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'OK',
                'unread_count' => 0,
            ]);
        }

        return redirect()->back();
    }

    private function serializeNotification($n): array
    {
        $data = $n->data;

        return [
            'id' => $n->id,
            'read' => $n->read_at !== null,
            'title' => $data['title'] ?? Str::limit((string) ($data['message'] ?? ''), 72),
            'message' => $data['message'] ?? null,
            'url' => $data['url'] ?? '#',
            'created_human' => $n->created_at?->diffForHumans() ?? '',
        ];
    }
}
