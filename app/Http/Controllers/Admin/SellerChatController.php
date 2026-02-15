<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerChatController extends Controller
{
    /**
     * Display all seller chat conversations
     */
    public function index()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $conversations = SellerChat::select('seller_id', DB::raw('MAX(created_at) as last_message_time'))
            ->groupBy('seller_id')
            ->orderBy('last_message_time', 'desc')
            ->get()
            ->map(function ($chat) {
                $seller = Seller::find($chat->seller_id);
                $unreadCount = SellerChat::where('seller_id', $chat->seller_id)
                    ->where('sender_type', 'seller')
                    ->where('is_read', false)
                    ->count();

                return [
                    'seller' => $seller,
                    'last_message_time' => $chat->last_message_time,
                    'unread_count' => $unreadCount,
                ];
            });

        return view('admin.seller-chats.index', compact('conversations'));
    }

    /**
     * Display conversation with a specific seller
     */
    public function show($sellerId)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $seller = Seller::findOrFail($sellerId);
        $messages = SellerChat::where('seller_id', $sellerId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark seller messages as read
        SellerChat::where('seller_id', $sellerId)
            ->where('sender_type', 'seller')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.seller-chats.show', compact('seller', 'messages'));
    }

    /**
     * Send reply to seller
     */
    public function reply(Request $request, $sellerId)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Seller::findOrFail($sellerId);

        $chat = SellerChat::create([
            'seller_id' => $sellerId,
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
     * Get messages for a seller (for AJAX)
     */
    public function getMessages($sellerId)
    {
        if (!Auth::guard('admin')->check()) {
            return response()->json(['success' => false], 401);
        }

        $messages = SellerChat::where('seller_id', $sellerId)
            ->orderBy('created_at', 'asc')
            ->get();

        SellerChat::where('seller_id', $sellerId)
            ->where('sender_type', 'seller')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}
