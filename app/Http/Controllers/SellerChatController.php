<?php

namespace App\Http\Controllers;

use App\Models\SellerChat;
use App\Models\SellerChatFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerChatController extends Controller
{
    /**
     * Display seller chat with admin
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();

        $messages = SellerChat::with('files')->where('seller_id', $seller->id)
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

        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        $hasMessage = isset($validated['message']) && trim((string) $validated['message']) !== '';
        $files = $request->file('attachments') ?: [];
        $hasFiles = is_array($files) && count($files) > 0;

        if (!$hasMessage && !$hasFiles) {
            return response()->json(['success' => false, 'message' => 'Please type a message or attach a file.'], 422);
        }

        $chat = SellerChat::create([
            'seller_id' => $seller->id,
            'message' => $hasMessage ? $validated['message'] : '',
            'sender_type' => 'seller',
            'is_read' => false,
        ]);

        if ($hasFiles) {
            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }

                $originalName = $file->getClientOriginalName();
                $mime = $file->getClientMimeType() ?: ($file->extension() ? ('application/' . $file->extension()) : 'application/octet-stream');
                $size = (int) ($file->getSize() ?: 0);

                $ext = $file->getClientOriginalExtension() ?: $file->extension();
                $filename = (string) Str::uuid() . ($ext ? ('.' . $ext) : '');
                $path = $file->storeAs("seller_chat_files/{$seller->id}", $filename, 'public');

                SellerChatFile::create([
                    'seller_chat_id' => $chat->id,
                    'path' => $path,
                    'original_name' => $originalName ?: $filename,
                    'mime' => $mime ?: 'application/octet-stream',
                    'size' => $size,
                ]);
            }
        }

        // Auto-greet once (first time seller messages and no admin messages exist yet)
        $hasAdminMessage = SellerChat::where('seller_id', $seller->id)
            ->where('sender_type', 'admin')
            ->exists();

        if (!$hasAdminMessage) {
            $name = $seller->name ?: ($seller->shop_name ?? 'Seller');
            SellerChat::create([
                'seller_id' => $seller->id,
                'message' => "Hello {$name}!\n\nThanks for reaching out.\nHow can we help you today?",
                'sender_type' => 'admin',
                'is_read' => false,
            ]);
        }

        $chat->load('files');

        return response()->json([
            'success' => true,
            'message' => $chat,
        ]);
    }

    public function viewFile(SellerChatFile $file)
    {
        $seller = Auth::guard('seller')->user();

        $file->loadMissing('chat');
        if (!$seller || !$file->chat || (int) $file->chat->seller_id !== (int) $seller->id) {
            abort(403);
        }

        $path = Storage::disk('public')->path($file->path);
        if (!is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function downloadFile(SellerChatFile $file)
    {
        $seller = Auth::guard('seller')->user();

        $file->loadMissing('chat');
        if (!$seller || !$file->chat || (int) $file->chat->seller_id !== (int) $seller->id) {
            abort(403);
        }

        $path = Storage::disk('public')->path($file->path);
        if (!is_file($path)) {
            abort(404);
        }

        $name = $file->original_name ?: basename($file->path);
        return response()->download($path, $name);
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

        $messages = SellerChat::with('files')->where('seller_id', $seller->id)
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
