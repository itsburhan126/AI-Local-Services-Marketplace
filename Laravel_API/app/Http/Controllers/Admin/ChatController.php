<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $adminId = Auth::id();

        // Get users with whom the admin has chatted, or who have chatted with the admin
        // Include last message timestamp and unread count
        $users = User::where('id', '!=', $adminId)
            ->where(function($query) use ($adminId) {
                $query->whereHas('sentMessages', function($q) use ($adminId) {
                    $q->where('receiver_id', $adminId);
                })
                ->orWhereHas('receivedMessages', function($q) use ($adminId) {
                    $q->where('sender_id', $adminId);
                });
            })
            ->withCount(['sentMessages as unread_count' => function ($query) use ($adminId) {
                $query->where('receiver_id', $adminId)->whereNull('read_at');
            }])
            ->addSelect(['last_message_at' => Message::select('created_at')
                ->where(function ($query) use ($adminId) {
                    $query->where('sender_id', $adminId)
                        ->whereColumn('receiver_id', 'users.id');
                })
                ->orWhere(function ($query) use ($adminId) {
                    $query->where('receiver_id', $adminId)
                        ->whereColumn('sender_id', 'users.id');
                })
                ->latest()
                ->take(1)
            ])
            ->addSelect(['last_message_content' => Message::select('message')
                ->where(function ($query) use ($adminId) {
                    $query->where('sender_id', $adminId)
                        ->whereColumn('receiver_id', 'users.id');
                })
                ->orWhere(function ($query) use ($adminId) {
                    $query->where('receiver_id', $adminId)
                        ->whereColumn('sender_id', 'users.id');
                })
                ->latest()
                ->take(1)
            ])
            ->orderByDesc('last_message_at')
            ->paginate(50); // Use pagination for performance
            
        return view('admin.chat.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Mark messages as read
        Message::where('sender_id', $id)
            ->where('receiver_id', Auth::id())
            ->update(['read_at' => now()]);

        $messages = Message::with(['replyTo.sender'])
            ->where(function($q) use ($id) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $id);
            })
            ->orWhere(function($q) use ($id) {
                $q->where('sender_id', $id)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'user' => $user,
            'messages' => $messages
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|required_without:attachment',
            'attachment' => 'nullable|image|max:5120', // Max 5MB
            'reply_to_id' => 'nullable|exists:messages,id',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat_attachments', $filename, 'public');
            $attachmentPath = '/storage/' . $path;
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'attachment' => $attachmentPath,
            'reply_to_id' => $request->reply_to_id,
        ]);

        $message->load(['replyTo.sender']); // Eager load for frontend

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }
}
