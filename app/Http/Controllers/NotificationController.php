<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                                     ->orderByDesc('created_at')
                                     ->paginate(20);

        // Mark all as read when viewing
        Notification::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true]);

        return view('notifications', compact('user', 'notifications'));
    }

    public function getUnread()
    {
        if (!Auth::check()) return response()->json(['count' => 0, 'items' => []]);

        $notifications = Notification::where('user_id', Auth::id())
                                     ->where('is_read', false)
                                     ->orderByDesc('created_at')
                                     ->limit(10)
                                     ->get();

        return response()->json([
            'count' => $notifications->count(),
            'items' => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->title,
                'message'    => $n->message,
                'type'       => $n->type,
                'related_id' => $n->related_id,
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function markRead(Request $request, $id)
    {
        Notification::where('user_id', Auth::id())->where('id', $id)->update(['is_read' => true]);
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('notifications.index');
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read.');
    }
}
