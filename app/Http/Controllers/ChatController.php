<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface for logged-in users.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access chat.');
        }

        // Get all chat messages for the current user
        $messages = Chat::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        Chat::where('user_id', Auth::id())
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat', compact('messages'));
    }

    /**
     * Send a new message from the user.
     */
    public function send(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'sender_type' => 'user',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $chat,
        ]);
    }

    /**
     * Get new messages (for AJAX polling).
     */
    public function getMessages()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $messages = Chat::where('user_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        Chat::where('user_id', Auth::id())
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}
