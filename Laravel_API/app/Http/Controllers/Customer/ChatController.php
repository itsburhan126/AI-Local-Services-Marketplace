<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface with conversation list.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $selectedUserId = $request->query('user_id');

        // Fetch categories for the layout
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(10)
            ->get();
            
        // Subcategories for mega menu
        $subcategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->get()
            ->groupBy('parent_id');

        // Fetch conversations (users who have exchanged messages with current user)
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
            
            // If selected user is not in conversations (new chat), add them to the list
            if ($selectedConversation && !$conversations->contains('id', $selectedUserId)) {
                $selectedConversation->unread_count = 0;
                $selectedConversation->last_message_at = null;
                $selectedConversation->last_message_content = null;
                $conversations->prepend($selectedConversation);
            }
            
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

        return view('Customer.freelancer.chat.index', compact('conversations', 'selectedConversation', 'messages', 'categories', 'subcategories'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|required_without:attachment|string',
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
