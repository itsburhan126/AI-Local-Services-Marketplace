<div class="flex gap-4 mb-6 group px-2 hover:bg-slate-50/50 rounded-lg transition-colors py-2 -mx-2">
    <div class="flex-shrink-0 pt-1">
        @if($isMe)
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
        @else
            <img src="{{ $message->sender->avatar ? asset('storage/' . $message->sender->avatar) : asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
        @endif
    </div>
    
    <div class="flex-1 min-w-0">
        <div class="flex items-baseline gap-2 mb-1">
            <h4 class="font-bold text-slate-900 text-sm">
                {{ $isMe ? 'Me' : $message->sender->name }}
            </h4>
            <span class="text-xs text-slate-400">{{ $message->created_at->format('M d, h:i A') }}</span>
        </div>
        
        <div class="text-slate-800 text-[15px] leading-relaxed break-words">
            {{ $message->message }}
        </div>

        @if($message->attachment)
            <div class="mt-3">
                 <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl hover:border-primary-500 hover:shadow-sm transition-all group/file max-w-sm">
                    <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fas fa-file-alt text-lg"></i>
                    </div>
                    <div class="text-left overflow-hidden">
                        <p class="text-sm font-bold text-slate-700 truncate">Attachment</p>
                        <p class="text-xs text-slate-400 group-hover/file:text-primary-500 transition-colors">Click to download</p>
                    </div>
                 </a>
            </div>
        @endif
    </div>
</div>