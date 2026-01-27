<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display all user conversations.
     */
    public function index()
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Get all users who have sent messages, with their latest message
        $conversations = Chat::select('user_id', DB::raw('MAX(created_at) as last_message_time'))
            ->groupBy('user_id')
            ->orderBy('last_message_time', 'desc')
            ->with('user')
            ->get()
            ->map(function ($chat) {
                $user = User::find($chat->user_id);
                $unreadCount = Chat::where('user_id', $chat->user_id)
                    ->where('sender_type', 'user')
                    ->where('is_read', false)
                    ->count();
                
                return [
                    'user' => $user,
                    'last_message_time' => $chat->last_message_time,
                    'unread_count' => $unreadCount,
                ];
            });

        return view('admin.chats.index', compact('conversations'));
    }

    /**
     * Display conversation with a specific user.
     */
    public function show($userId)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $user = User::findOrFail($userId);
        
        // Get all messages for this user
        $messages = Chat::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark user messages as read
        Chat::where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.chats.show', compact('user', 'messages'));
    }

    /**
     * Send a reply to a user.
     */
    public function reply(Request $request, $userId)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = User::findOrFail($userId);

        $chat = Chat::create([
            'user_id' => $userId,
            'message' => $request->message,
            'sender_type' => 'admin',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $chat,
        ]);
    }

    /**
     * Get messages for a specific user (for AJAX).
     */
    public function getMessages($userId)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['success' => false], 401);
        }

        $messages = Chat::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark user messages as read
        Chat::where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Delete a conversation.
     */
    public function destroy($userId)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['success' => false], 401);
        }

        Chat::where('user_id', $userId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Conversation deleted successfully',
        ]);
    }
}
