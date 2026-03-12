<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSellerChat;
use App\Models\User;

class SellerBuyerChatController extends Controller
{
    /** List all buyer conversations for the logged-in seller */
    public function index()
    {
        $sellerId = Auth::guard('seller')->id();

        $conversations = UserSellerChat::where('seller_id', $sellerId)
            ->select('user_id')
            ->selectRaw('MAX(id) as last_id')
            ->selectRaw('SUM(is_read = 0 AND sender_type = "user") as unread_count')
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('last_id')
            ->get()
            ->map(function ($row) {
                $row->last_message = UserSellerChat::where('id', $row->last_id)->first();
                return $row;
            });

        return view('seller.buyer-chats', compact('conversations'));
    }

    /** Show conversation with a specific buyer */
    public function show($userId)
    {
        $sellerId = Auth::guard('seller')->id();
        $buyer    = User::findOrFail($userId);

        // Mark buyer messages as read
        UserSellerChat::where('seller_id', $sellerId)
            ->where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = UserSellerChat::where('seller_id', $sellerId)
            ->where('user_id', $userId)
            ->orderBy('created_at')
            ->get();

        return view('seller.buyer-chat-show', compact('buyer', 'messages', 'userId'));
    }

    /** Send a message to a buyer */
    public function send(Request $request, $userId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $sellerId = Auth::guard('seller')->id();
        $buyer    = User::findOrFail($userId);

        UserSellerChat::create([
            'user_id'     => $userId,
            'seller_id'   => $sellerId,
            'message'     => $request->message,
            'sender_type' => 'seller',
            'is_read'     => 0,
        ]);

        return response()->json(['success' => true]);
    }

    /** Poll for new messages */
    public function getMessages($userId)
    {
        $sellerId = Auth::guard('seller')->id();

        // Mark buyer messages as read on poll
        UserSellerChat::where('seller_id', $sellerId)
            ->where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = UserSellerChat::where('seller_id', $sellerId)
            ->where('user_id', $userId)
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'message'     => $m->message,
                'sender_type' => $m->sender_type,
                'time'        => $m->created_at->format('h:i A'),
                'date'        => $m->created_at->diffForHumans(),
            ]);

        return response()->json(['messages' => $messages]);
    }

    /** Unread count for badge (used by seller layout) */
    public function unreadCount()
    {
        $sellerId = Auth::guard('seller')->id();
        $count    = UserSellerChat::where('seller_id', $sellerId)
            ->where('sender_type', 'user')
            ->where('is_read', 0)
            ->count();

        return response()->json(['count' => $count]);
    }
}
