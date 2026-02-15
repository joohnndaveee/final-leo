<?php

namespace App\Http\Controllers;

use App\Models\SellerChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerChatController extends Controller
{
    /**
     * Display seller chat with admin
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        $messages = SellerChat::where('seller_id', $seller->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        SellerChat::where('seller_id', $seller->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('seller.chat', compact('seller', 'messages'));
    }

    /**
     * Send a message to admin
     */
    public function send(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        if (!$seller) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = SellerChat::create([
            'seller_id' => $seller->id,
            'message' => $request->message,
            'sender_type' => 'seller',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => $chat,
        ]);
    }

    /**
     * Get messages (for AJAX polling)
     */
    public function getMessages()
    {
        $seller = Auth::guard('seller')->user();

        if (!$seller) {
            return response()->json(['success' => false], 401);
        }

        $messages = SellerChat::where('seller_id', $seller->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        SellerChat::where('seller_id', $seller->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}
