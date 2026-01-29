@extends('layouts.freelancer')

@section('title', 'Messages')

@section('content')
<div class="h-[calc(100vh-8.5rem)] flex bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden max-w-screen-2xl mx-auto">
    
    <!-- Left Sidebar: Conversation List -->
    <div class="w-full md:w-80 lg:w-96 border-r border-slate-200 flex flex-col bg-white z-10 shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white h-[73px]">
            <div class="relative">
                <button class="flex items-center gap-2 font-bold text-slate-800 text-lg hover:text-slate-600 transition-colors">
                    All Messages <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                </button>
            </div>
            <div class="flex gap-2">
                <button class="w-8 h-8 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors">
                    <i class="fas fa-sliders-h"></i>
                </button>
                <button class="w-8 h-8 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-500 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- List -->
        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200">
            @if(count($conversations) > 0)
                <ul class="divide-y divide-slate-50">
                    @foreach($conversations as $conversation)
                        <li>
                            <a href="{{ route('provider.freelancer.chat.index', ['user_id' => $conversation->id]) }}" class="block p-4 hover:bg-slate-50 transition-all {{ isset($selectedConversation) && $selectedConversation->id === $conversation->id ? 'bg-white border-r-2 border-primary-500 relative z-10 shadow-[inset_4px_0_0_0_theme(colors.primary.500)]' : 'bg-white' }}">
                                <div class="flex gap-3">
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $conversation->avatar ? asset('storage/' . $conversation->avatar) : asset('images/default-avatar.png') }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-sm">
                                        @if($conversation->is_online) 
                                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full shadow-sm"></span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-baseline mb-1">
                                            <h3 class="font-bold text-slate-900 truncate {{ isset($selectedConversation) && $selectedConversation->id === $conversation->id ? 'text-black' : '' }}">{{ $conversation->name }}</h3>
                                            @if($conversation->last_message_at)
                                                <span class="text-[11px] text-slate-400 flex-shrink-0 ml-2">{{ \Carbon\Carbon::parse($conversation->last_message_at)->shortAbsoluteDiffForHumans() }}</span>
                                            @endif
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm text-slate-500 truncate max-w-[160px] {{ $conversation->unread_count > 0 ? 'font-bold text-slate-800' : '' }}">
                                                {{ $conversation->last_message_content ?? 'Start a conversation' }}
                                            </p>
                                            <div class="flex gap-2 items-center">
                                                @if($conversation->unread_count > 0)
                                                    <span class="bg-primary-600 text-white text-[10px] font-bold h-5 min-w-[20px] px-1.5 flex items-center justify-center rounded-full shadow-sm">{{ $conversation->unread_count }}</span>
                                                @endif
                                                <button class="text-slate-300 hover:text-yellow-400 transition-colors"><i class="fas fa-star"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="flex flex-col items-center justify-center h-full text-center p-6 text-slate-400">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="far fa-comments text-2xl"></i>
                    </div>
                    <p class="font-medium text-slate-600">No messages</p>
                    <p class="text-xs mt-1">Conversations will appear here.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col bg-white relative min-w-0">
        @if($selectedConversation)
            <!-- Chat Header -->
            <div class="h-[73px] px-6 border-b border-slate-200 bg-white flex items-center justify-between z-10 shrink-0">
                <div class="flex items-center gap-3">
                    <button class="md:hidden mr-1 text-slate-500 hover:text-slate-700">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-slate-900 text-lg leading-tight">{{ $selectedConversation->name }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">Buyer</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5">
                            <span class="text-slate-400">Last seen {{ now()->subMinutes(rand(1, 59))->diffForHumans() }}</span>
                            <span class="text-slate-300">|</span>
                            <span>Local Time: {{ now()->format('h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-yellow-400 hover:bg-slate-50 rounded-full transition-colors" title="Star">
                        <i class="far fa-star"></i>
                    </button>
                    <button class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors" title="Video Call">
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors" title="Phone">
                        <i class="fas fa-phone"></i>
                    </button>
                    <button class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors" title="More">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-thin scrollbar-thumb-slate-200 bg-white" id="messages-container">
                <div class="flex flex-col items-center justify-center py-8 pb-4">
                     <span class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-2">This is the beginning of your conversation</span>
                     <div class="flex items-center gap-2 text-slate-300 w-full max-w-md">
                        <div class="h-px bg-slate-100 flex-1"></div>
                        <span class="text-[10px]">{{ \Carbon\Carbon::parse($selectedConversation->created_at)->format('M d, Y') }}</span>
                        <div class="h-px bg-slate-100 flex-1"></div>
                     </div>
                </div>

                @foreach($messages as $message)
                    @include('Provider.Freelancer.chat.partials.message-bubble', ['message' => $message, 'isMe' => $message->sender_id === Auth::id()])
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-200 shrink-0">
                <form id="chat-form" action="{{ route('provider.freelancer.chat.store') }}" method="POST" enctype="multipart/form-data" class="relative">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $selectedConversation->id }}">
                    
                    <div class="bg-white border border-slate-300 rounded-xl p-3 focus-within:ring-1 focus-within:ring-primary-500 focus-within:border-primary-500 transition-all shadow-sm">
                        <textarea name="message" rows="1" class="w-full bg-transparent border-0 focus:ring-0 text-slate-700 placeholder-slate-400 resize-none p-0 min-h-[24px] max-h-48 leading-relaxed" placeholder="Type a message..." required id="message-input"></textarea>
                        
                        <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-100">
                            <div class="flex items-center gap-1">
                                <button type="button" class="p-2 text-slate-400 hover:text-slate-600 transition-colors rounded-full hover:bg-slate-100" title="Emoji">
                                    <i class="far fa-smile text-lg"></i>
                                </button>
                                <button type="button" class="p-2 text-slate-400 hover:text-slate-600 transition-colors rounded-full hover:bg-slate-100" title="Attach file">
                                    <i class="fas fa-paperclip text-lg"></i>
                                </button>
                                <button type="button" class="p-2 text-slate-400 hover:text-primary-600 transition-colors rounded-full hover:bg-slate-100" title="Quick Response">
                                    <i class="fas fa-bolt text-lg"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <button type="button" class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                                    Create an Offer
                                </button>
                                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-colors flex items-center gap-2">
                                    Send <i class="far fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-slate-50">
                <div class="max-w-md mx-auto">
                    <img src="{{ asset('images/chat-illustration.svg') }}" onerror="this.src='https://illustrations.popsy.co/gray/work-from-home.svg'" alt="Select a conversation" class="w-64 h-64 mx-auto mb-8 opacity-90">
                    <h2 class="text-2xl font-bold text-slate-800 mb-3">Your Messages</h2>
                    <p class="text-slate-500 leading-relaxed">Select a conversation from the left sidebar to start chatting or check your new messages.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Sidebar: User Info (Desktop Only) -->
    @if($selectedConversation)
    <div class="hidden xl:flex w-80 border-l border-slate-200 bg-white flex-col shrink-0">
        <div class="p-8 flex flex-col items-center text-center border-b border-slate-100">
            <div class="relative mb-4">
                <img src="{{ $selectedConversation->avatar ? asset('storage/' . $selectedConversation->avatar) : asset('images/default-avatar.png') }}" class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 shadow-sm">
                <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></span>
            </div>
            <h3 class="font-bold text-slate-900 text-xl mb-1 hover:underline cursor-pointer">{{ $selectedConversation->name }}</h3>
            <p class="text-sm text-slate-500 mb-4">New Buyer</p>
            <button class="text-sm text-slate-500 hover:text-slate-800 border border-slate-300 rounded px-3 py-1 transition-colors">Contact Info</button>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="space-y-4">
                <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wide">About</h4>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">From</span>
                    <span class="font-semibold text-slate-700">{{ $selectedConversation->country ?? 'Global' }}</span>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Member since</span>
                    <span class="font-semibold text-slate-700">{{ $selectedConversation->created_at->format('M Y') }}</span>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Avg. Response Time</span>
                    <span class="font-semibold text-slate-700">1 hour</span>
                </div>
                
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Last Delivery</span>
                    <span class="font-semibold text-slate-700">N/A</span>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm uppercase tracking-wide mb-4">Notes</h4>
                <textarea class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none" rows="4" placeholder="Add a private note about this buyer..."></textarea>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        const messageInput = document.getElementById('message-input');
        if (messageInput) {
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim() !== '') {
                        document.getElementById('chat-form').submit();
                    }
                }
            });
            
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Focus input on load
            messageInput.focus();
        }
    });
</script>
@endsection
