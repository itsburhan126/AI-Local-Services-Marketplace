<?php

namespace App\Http\Controllers\Provider\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display the chat interface with conversation list.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $selectedUserId = $request->query('user_id');

        // Fetch conversations (users who have exchanged messages with current user)
        // This is a simplified query; for better performance, consider a separate conversation model or optimized query
        $conversations = User::where('id', '!=', $userId)
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
            ->get();

        $selectedConversation = null;
        $messages = collect();

        if ($selectedUserId) {
            $selectedConversation = User::find($selectedUserId);
            
            if ($selectedConversation) {
                // Fetch messages between current user and selected user
                $messages = Message::where(function($q) use ($userId, $selectedUserId) {
                        $q->where('sender_id', $userId)->where('receiver_id', $selectedUserId);
                    })
                    ->orWhere(function($q) use ($userId, $selectedUserId) {
                        $q->where('sender_id', $selectedUserId)->where('receiver_id', $userId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Mark messages as read
                Message::where('sender_id', $selectedUserId)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);
            }
        }

        return view('Provider.Freelancer.chat.index', compact('conversations', 'selectedConversation', 'messages'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('chat_attachments', 'public');
            $attachmentType = $file->getClientOriginalExtension();
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // In a real app, you would broadcast an event here for real-time updates
        // event(new MessageSent($message));

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'view' => view('Provider.Freelancer.chat.partials.message-bubble', ['message' => $message, 'isMe' => true])->render()
            ]);
        }

        return back();
    }
}
