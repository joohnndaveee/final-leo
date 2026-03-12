<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Order;
use App\Models\UserSellerChat;
use App\Models\Seller;
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

        $orders = Order::where('user_id', Auth::id())->orderByDesc('id')->limit(10)->get();

        // Mark admin messages as read
        Chat::where('user_id', Auth::id())
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat', compact('messages', 'orders'));
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
            'order_id' => 'nullable|exists:orders,id',
        ]);

        if ($request->filled('order_id')) {
            $ownsOrder = Order::where('id', $request->order_id)->where('user_id', Auth::id())->exists();
            if (!$ownsOrder) {
                return response()->json(['success' => false, 'message' => 'Invalid order reference'], 403);
            }
        }

        $chat = Chat::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
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
     * Get all conversations (admin + sellers) for the chat widget.
     */
    public function widgetConversations()
    {
        if (!Auth::check()) {
            return response()->json(['conversations' => [], 'total_unread' => 0]);
        }

        $userId = Auth::id();
        $conversations = [];

        // 1. Admin conversation
        $adminLast = Chat::where('user_id', $userId)->orderByDesc('created_at')->first();
        $adminUnread = Chat::where('user_id', $userId)->where('sender_type', 'admin')->where('is_read', 0)->count();
        $conversations[] = [
            'type'        => 'admin',
            'id'          => 'admin',
            'name'        => 'U-KAY HUB Support',
            'avatar'      => null,
            'initials'    => 'A',
            'last_msg'    => $adminLast ? \Illuminate\Support\Str::limit($adminLast->message, 50) : 'Start a conversation',
            'last_time'   => $adminLast ? $adminLast->created_at->format('d/m') : '',
            'unread'      => $adminUnread,
            'send_url'    => route('chat.send'),
            'poll_url'    => route('chat.getMessages'),
        ];

        // 2. Seller conversations
        $sellerGroups = UserSellerChat::where('user_id', $userId)
            ->select('seller_id')
            ->selectRaw('MAX(id) as last_id')
            ->selectRaw('SUM(is_read = 0 AND sender_type = "seller") as unread_count')
            ->groupBy('seller_id')
            ->orderByDesc('last_id')
            ->get();

        foreach ($sellerGroups as $row) {
            $seller  = Seller::find($row->seller_id);
            if (!$seller) continue;
            $lastMsg = UserSellerChat::find($row->last_id);
            $logo    = !empty($seller->shop_logo) ? asset('uploaded_img/' . $seller->shop_logo) : null;

            $conversations[] = [
                'type'      => 'seller',
                'id'        => $seller->id,
                'name'      => $seller->shop_name ?? 'Seller',
                'avatar'    => $logo,
                'initials'  => strtoupper(substr($seller->shop_name ?? 'S', 0, 1)),
                'last_msg'  => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->message, 50) : '',
                'last_time' => $lastMsg ? $lastMsg->created_at->format('d/m') : '',
                'unread'    => (int) $row->unread_count,
                'send_url'  => route('user.seller.chat.send', $seller->id),
                'poll_url'  => route('user.seller.chat.messages', $seller->id),
            ];
        }

        $totalUnread = array_sum(array_column($conversations, 'unread'));

        return response()->json([
            'conversations' => $conversations,
            'total_unread'  => $totalUnread,
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
