<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSellerChat;
use App\Models\Seller;

class UserSellerChatController extends Controller
{
    /** List all seller conversations for the logged-in user */
    public function index()
    {
        $userId = Auth::id();

        $conversations = UserSellerChat::where('user_id', $userId)
            ->select('seller_id')
            ->selectRaw('MAX(id) as last_id')
            ->selectRaw('SUM(is_read = 0 AND sender_type = "seller") as unread_count')
            ->groupBy('seller_id')
            ->with('seller')
            ->orderByDesc('last_id')
            ->get()
            ->map(function ($row) {
                $row->last_message = UserSellerChat::where('id', $row->last_id)->first();
                return $row;
            });

        return view('user-seller-chats', compact('conversations'));
    }

    /** Show chat with a specific seller */
    public function show($sellerId)
    {
        $seller = Seller::findOrFail($sellerId);
        $userId = Auth::id();

        // Mark seller messages as read
        UserSellerChat::where('user_id', $userId)
            ->where('seller_id', $sellerId)
            ->where('sender_type', 'seller')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = UserSellerChat::where('user_id', $userId)
            ->where('seller_id', $sellerId)
            ->orderBy('created_at')
            ->get();

        return view('user-seller-chat', compact('seller', 'messages'));
    }

    /** Send a message */
    public function send(Request $request, $sellerId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $seller = Seller::findOrFail($sellerId);

        UserSellerChat::create([
            'user_id'     => Auth::id(),
            'seller_id'   => $sellerId,
            'message'     => $request->message,
            'sender_type' => 'user',
            'is_read'     => 0,
        ]);

        return response()->json(['success' => true]);
    }

    /** Poll for new messages */
    public function getMessages($sellerId)
    {
        $userId = Auth::id();

        // Mark seller messages as read on poll
        UserSellerChat::where('user_id', $userId)
            ->where('seller_id', $sellerId)
            ->where('sender_type', 'seller')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        $messages = UserSellerChat::where('user_id', $userId)
            ->where('seller_id', $sellerId)
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
}
