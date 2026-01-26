<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Events\MessageDelivered;
use App\Events\MessageRead;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class ChatController extends Controller
{
    public function config()
    {
        return response()->json([
            'pusher_app_key' => Setting::get('pusher_app_key', config('broadcasting.connections.pusher.key')),
            'pusher_app_cluster' => Setting::get('pusher_app_cluster', config('broadcasting.connections.pusher.options.cluster')),
        ]);
    }

    public function typing(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        broadcast(new UserTyping(Auth::id(), $request->receiver_id))->toOthers();

        return response()->json(['status' => 'success']);
    }

    /**
     * Get list of conversations (users chatted with)
     */
    public function index()
    {
        $userId = Auth::id();

        // Get users with whom the current user has chatted
        $users = User::where('id', '!=', $userId)
            ->where(function($query) use ($userId) {
                $query->whereHas('sentMessages', function($q) use ($userId) {
                    $q->where('receiver_id', $userId);
                })
                ->orWhereHas('receivedMessages', function($q) use ($userId) {
                    $q->where('sender_id', $userId);
                });
            })
            ->withCount(['sentMessages as unread_count' => function ($query) use ($userId) {
                $query->where('receiver_id', $userId)->whereNull('read_at');
            }])
            ->addSelect(['last_message_at' => Message::select('created_at')
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereColumn('receiver_id', 'users.id');
                })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->whereColumn('sender_id', 'users.id');
                })
                ->latest()
                ->take(1)
            ])
            ->addSelect(['last_message_content' => Message::select('message')
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->whereColumn('receiver_id', 'users.id');
                })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->whereColumn('sender_id', 'users.id');
                })
                ->latest()
                ->take(1)
            ])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return response()->json($users);
    }

    /**
     * Get messages for a specific user
     */
    public function show($id)
    {
        $otherUser = User::findOrFail($id);
        $currentUserId = Auth::id();

        // Mark messages as read
        Message::where('sender_id', $id)
            ->where('receiver_id', $currentUserId)
            ->update(['read_at' => now()]);

        $messages = Message::with(['replyTo.sender'])
            ->where(function($q) use ($id, $currentUserId) {
                $q->where('sender_id', $currentUserId)
                  ->where('receiver_id', $id);
            })
            ->orWhere(function($q) use ($id, $currentUserId) {
                $q->where('sender_id', $id)
                  ->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'user' => $otherUser,
            'messages' => $messages
        ]);
    }

    /**
     * Send a message
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|required_without:attachment',
            'attachment' => 'nullable|file|max:10240',
            'reply_to_id' => 'nullable|exists:messages,id',
        ]);

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat_attachments', $filename, 'public');
            $attachmentPath = '/storage/' . $path;

            // Determine attachment type
            $mimeType = $file->getMimeType();
            if (str_contains($mimeType, 'image')) {
                $attachmentType = 'image';
            } elseif (str_contains($mimeType, 'audio')) {
                $attachmentType = 'audio';
            } elseif (str_contains($mimeType, 'video')) {
                $attachmentType = 'video';
            } else {
                $attachmentType = 'file';
            }
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'reply_to_id' => $request->reply_to_id,
        ]);

        $message->load(['replyTo.sender']);

        broadcast(new MessageSent($message))->toOthers();

        // Send FCM Notification
        try {
            $receiver = User::find($request->receiver_id);
            if ($receiver && $receiver->fcm_token) {
                $fcmService = new FCMService();
                $title = "New Message from " . Auth::user()->name;
                $body = $message->message ?: "Sent an attachment";
                
                $data = [
                    'type' => 'chat_message',
                    'sender_id' => (string) Auth::id(),
                    'sender_name' => Auth::user()->name,
                    'message_id' => (string) $message->id,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ];
                
                $fcmService->sendNotification($receiver->fcm_token, $title, $body, $data);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Illuminate\Support\Facades\Log::error('Failed to send FCM notification: ' . $e->getMessage());
        }

        return response()->json([
            'message' => $message,
        ], 201);
    }

    public function delivered(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        $message = Message::find($request->message_id);

        // Only update if not already delivered
        if (!$message->delivered_at) {
            $message->update(['delivered_at' => now()]);
            broadcast(new MessageDelivered($message))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }

    public function read(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        $message = Message::find($request->message_id);

        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
            broadcast(new MessageRead($message))->toOthers();
        }

        return response()->json(['status' => 'success']);
    }
}
