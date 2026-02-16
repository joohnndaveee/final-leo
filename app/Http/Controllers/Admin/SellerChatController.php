<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerChat;
use App\Models\SellerChatFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $messages = SellerChat::with('files')->where('seller_id', $sellerId)
            ->orderBy('created_at', 'asc')
            ->get();

        $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
        $rentAmount = number_format((float) ($seller->monthly_rent ?? 500.00), 2);
        $endDate = $seller->subscription_end_date;
        $daysLeft = $endDate ? now()->diffInDays($endDate, false) : null;
        $endDateStr = $endDate ? $endDate->format('M d, Y') : 'N/A';

        $quickReplies = [];

        $quickReplies[] = [
            'label' => 'Greeting',
            'text' => "Hello {$name}!\n\nThanks for messaging us.\nHow can I help you today?",
        ];

        if ($seller->subscription_status === 'suspended') {
            if (($seller->suspension_reason ?? '') === 'Overdue Payment') {
                $quickReplies[] = [
                    'label' => 'Overdue Payment',
                    'text' => "Hello {$name}!\n\nYour seller account is suspended due to overdue payment.\n\nMonthly rent: ₱{$rentAmount}\nAction: Wallet → Pay Monthly Rent",
                ];
            } else {
                $reason = $seller->suspension_reason ?? 'Administrative Action';
                $notes = $seller->suspension_notes ? "\nNotes: {$seller->suspension_notes}" : '';
                $quickReplies[] = [
                    'label' => 'Suspension / Violation',
                    'text' => "Hello {$name}!\nYour seller account is currently suspended.\n\n\"{$reason}\"{$notes}\n\nIf you want to appeal, please reply here with your explanation and any proof.",
                ];
            }
        } else {
            if (in_array($seller->subscription_status, ['expired', 'inactive']) || ($daysLeft !== null && $daysLeft < 0)) {
                $quickReplies[] = [
                    'label' => 'Subscription Expired',
                    'text' => "Hello {$name}!\n\nYour subscription is expired.\nDue date: {$endDateStr}\nAmount: ₱{$rentAmount}\nAction: Wallet → Pay Monthly Rent",
                ];
            } elseif ($daysLeft !== null && $daysLeft <= 7) {
                $quickReplies[] = [
                    'label' => 'Expiring Soon',
                    'text' => "Hello {$name}!\n\nJust a reminder: your subscription will expire soon.\nExpiry: {$endDateStr}\nDays left: {$daysLeft}\nAmount: ₱{$rentAmount}\nAction: Wallet → Pay Monthly Rent",
                ];
            }
        }

        $quickReplies[] = [
            'label' => 'Request Details',
            'text' => "Hello {$name}!\n\nThanks for your message.\n\nPlease share more details so we can assist faster:\n- What happened\n- Screenshots\n- Order IDs\n- Dates/times",
        ];

        // Mark seller messages as read
        SellerChat::where('seller_id', $sellerId)
            ->where('sender_type', 'seller')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.seller-chats.show', compact('seller', 'messages', 'quickReplies'));
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

        $message = $request->message;
        // Normalize common newline formats, and also convert literal "\n" sequences (from some clients) to real newlines.
        $message = str_replace(["\r\n", "\r"], "\n", $message);
        $message = str_replace(["\\r\\n", "\\n", "\\r"], "\n", $message);

        $chat = SellerChat::create([
            'seller_id' => $sellerId,
            'message' => $message,
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

        $messages = SellerChat::with('files')->where('seller_id', $sellerId)
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

    public function viewFile($sellerId, SellerChatFile $file)
    {
        if (!Auth::guard('admin')->check()) {
            abort(403);
        }

        $file->loadMissing('chat');
        if (!$file->chat || (int) $file->chat->seller_id !== (int) $sellerId) {
            abort(404);
        }

        $path = Storage::disk('public')->path($file->path);
        if (!is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function downloadFile($sellerId, SellerChatFile $file)
    {
        if (!Auth::guard('admin')->check()) {
            abort(403);
        }

        $file->loadMissing('chat');
        if (!$file->chat || (int) $file->chat->seller_id !== (int) $sellerId) {
            abort(404);
        }

        $path = Storage::disk('public')->path($file->path);
        if (!is_file($path)) {
            abort(404);
        }

        $name = $file->original_name ?: basename($file->path);
        return response()->download($path, $name);
    }
}
