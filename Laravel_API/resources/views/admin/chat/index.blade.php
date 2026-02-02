@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="h-[calc(100vh-140px)] flex gap-6 content-transition">
    <!-- Users Sidebar -->
    <div class="w-1/3 glass-panel rounded-2xl flex flex-col overflow-hidden">
        <!-- Search -->
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Messages</h2>
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" placeholder="Search conversations..." 
                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 border-none focus:ring-2 focus:ring-indigo-200 transition-all text-sm">
            </div>
        </div>

        <!-- User List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-2" id="user-list">
            @foreach($users as $user)
            <button onclick="loadChat({{ $user->id }})" class="w-full flex items-center p-4 rounded-xl hover:bg-indigo-50 transition-all group user-item relative" data-id="{{ $user->id }}">
                <div class="relative">
                    <img src="{{ $user->profile_photo_url }}" 
                         class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                    <!-- Online Status Indicator (Optional: Can be real-time via Pusher later) -->
                    <!-- <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span> -->
                </div>
                <div class="ml-4 text-left flex-1 min-w-0">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors truncate">{{ $user->name }}</h3>
                        <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                            {{ $user->last_message_at ? \Carbon\Carbon::parse($user->last_message_at)->shortRelativeDiffForHumans() : '' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-500 truncate w-3/4 user-last-msg">
                            {{ $user->last_message_content ?? 'No messages yet' }}
                        </p>
                        @if($user->unread_count > 0)
                        <span class="bg-indigo-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full unread-badge shadow-sm shadow-indigo-200">
                            {{ $user->unread_count }}
                        </span>
                        @endif
                    </div>
                </div>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 glass-panel rounded-2xl flex flex-col overflow-hidden relative" id="chat-container">
        <!-- Empty State -->
        <div id="empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 bg-white/50 backdrop-blur-sm z-10">
            <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <i class="fas fa-comments text-4xl text-indigo-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Select a Conversation</h3>
            <p class="mt-2">Choose a user from the left to start chatting</p>
        </div>

        <!-- Chat Header -->
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white/80 backdrop-blur-md z-20">
            <div class="flex items-center">
                <div class="relative">
                    <img id="chat-header-avatar" src="" class="w-10 h-10 rounded-full object-cover hidden">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full hidden" id="chat-header-status"></span>
                </div>
                <div class="ml-4">
                    <h3 id="chat-header-name" class="font-bold text-gray-800"></h3>
                    <p id="chat-header-role" class="text-xs text-indigo-500 font-medium uppercase tracking-wider"></p>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="w-10 h-10 rounded-full bg-gray-50 hover:bg-indigo-50 text-gray-400 hover:text-indigo-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-phone"></i>
                </button>
                <button class="w-10 h-10 rounded-full bg-gray-50 hover:bg-indigo-50 text-gray-400 hover:text-indigo-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-video"></i>
                </button>
                <button class="w-10 h-10 rounded-full bg-gray-50 hover:bg-indigo-50 text-gray-400 hover:text-indigo-600 transition-colors flex items-center justify-center">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>

        <!-- Messages List -->
        <div id="messages-list" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/50 custom-scrollbar">
            <!-- Messages will be injected here -->
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-gray-100 z-20 relative">
            <!-- Reply Banner -->
            <div id="reply-banner" class="hidden absolute top-0 left-0 right-0 -translate-y-full bg-gray-50 border-t border-gray-100 p-3 px-6 flex justify-between items-center z-10">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-1 h-10 bg-indigo-500 rounded-full"></div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-xs font-bold text-indigo-600" id="reply-to-name">Replying to User</span>
                        <span class="text-sm text-gray-500 truncate" id="reply-to-text">Message content...</span>
                    </div>
                </div>
                <button onclick="cancelReply()" class="text-gray-400 hover:text-gray-600 p-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Main Emoji Picker -->
            <div id="main-emoji-picker" class="absolute bottom-24 right-6 bg-white text-black p-2 rounded-lg shadow-xl grid grid-cols-6 gap-2 hidden w-64 z-50 max-h-48 overflow-y-auto border border-gray-100">
                <!-- Emojis will be injected here -->
            </div>

            <form id="chat-form" class="flex gap-4 items-end" enctype="multipart/form-data">
                <input type="file" id="attachment-input" name="attachment" class="hidden" accept="image/*">
                <button type="button" id="attachment-btn" class="p-3 text-gray-400 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-paperclip text-xl"></i>
                </button>
                <div class="flex-1 relative">
                    <textarea id="message-input" rows="1" placeholder="Type your message..." 
                        class="w-full pl-4 pr-12 py-3 rounded-2xl bg-gray-100 border-none focus:ring-2 focus:ring-indigo-200 resize-none custom-scrollbar max-h-32 transition-all"></textarea>
                    <button type="button" id="btn-main-emoji" class="absolute right-3 top-2.5 text-gray-400 hover:text-indigo-600 transition-colors">
                        <i class="far fa-smile text-xl"></i>
                    </button>
                </div>
                <button type="submit" class="w-12 h-12 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-200 flex items-center justify-center transition-all transform hover:scale-105">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Image Preview Modal (WhatsApp Style) -->
    <div id="image-preview-modal" class="fixed inset-0 z-50 hidden bg-slate-900/95 backdrop-blur-md flex flex-col transition-opacity duration-300 opacity-0">
        <!-- Header -->
        <div class="p-4 flex justify-between items-center text-white z-10">
            <button id="close-preview-btn" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="flex gap-6 text-gray-300 relative">
                <button id="btn-crop" class="hover:text-white transition-colors"><i class="fas fa-crop-alt text-lg"></i></button>
                <button id="btn-emoji" class="hover:text-white transition-colors"><i class="fas fa-smile text-lg"></i></button>
                <!-- Emoji Picker -->
                <div id="emoji-picker" class="absolute top-10 right-0 bg-white text-black p-2 rounded-lg shadow-xl grid grid-cols-6 gap-2 hidden w-64 z-50 max-h-48 overflow-y-auto">
                    <!-- Emojis will be injected here -->
                </div>
            </div>
        </div>

        <!-- Image Container -->
        <div class="flex-1 flex items-center justify-center p-4 sm:p-8 overflow-hidden relative">
            <div class="relative max-w-full max-h-full">
                <img id="preview-image" src="" class="max-h-[80vh] max-w-full object-contain shadow-2xl transition-transform duration-300 scale-95 opacity-0">
            </div>
            <!-- Crop Controls (Hidden by default) -->
            <div id="crop-controls" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-4 hidden z-20">
                <button id="btn-confirm-crop" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg">
                    <i class="fas fa-check mr-2"></i> Done
                </button>
                <button id="btn-cancel-crop" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            </div>
        </div>

        <!-- Footer / Caption Input -->
        <div class="p-4 bg-black/40 backdrop-blur-md border-t border-white/10 z-10">
            <div class="max-w-3xl mx-auto w-full flex flex-col gap-4">
                <!-- Thumbnail Strip (Visual only for now) -->
                <div class="flex justify-center">
                    <div class="w-12 h-12 rounded-lg border-2 border-indigo-500 overflow-hidden relative">
                        <img id="preview-thumbnail" src="" class="w-full h-full object-cover">
                    </div>
                    <button id="btn-add-more" class="w-12 h-12 ml-2 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors border border-white/10">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Input Area -->
                <div class="flex gap-4 items-center">
                    <div class="flex-1 bg-white/10 rounded-3xl flex items-center px-6 border border-white/10 focus-within:border-indigo-500/50 focus-within:bg-white/15 transition-all">
                        <input type="text" id="preview-message-input" placeholder="Add a caption..." 
                            class="w-full bg-transparent border-none text-white placeholder-gray-400 focus:ring-0 py-3.5 text-sm sm:text-base">
                    </div>
                    <button id="send-preview-btn" class="w-12 h-12 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 transition-all transform hover:scale-105 active:scale-95">
                        <i class="fas fa-paper-plane text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    /* Custom Scrollbar for Emoji Picker */
    #emoji-picker::-webkit-scrollbar {
        width: 4px;
    }
    #emoji-picker::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    let currentUserId = null;
    const adminId = {{ Auth::id() }};
    
    // Pusher Configuration
    Pusher.logToConsole = true;
    const pusher = new Pusher('{{ \App\Models\Setting::get("pusher_app_key") }}', {
        cluster: '{{ \App\Models\Setting::get("pusher_app_cluster") }}',
        encrypted: true
    });

    const channel = pusher.subscribe('chat.' + adminId);
    channel.bind('App\\Events\\MessageSent', function(data) {
        if (currentUserId && (data.message.sender_id == currentUserId)) {
            // If chat is open, just append message
            appendMessage(data.message, false);
            scrollToBottom();
            
            // Mark as read via AJAX if needed, or assume backend handles it when we open
            // Since we are open, we should theoretically mark as read.
            // For now, let's just update the list item to be at top
            updateUserListItem(data.message.sender_id, data.message.message, data.message.created_at, false);
        } else {
            // Chat not open or from another user
            // Update sidebar: move to top, update preview, increment badge
            updateUserListItem(data.message.sender_id, data.message.message, data.message.created_at, true);
        }
    });

    function updateUserListItem(userId, message, time, incrementUnread) {
        const list = document.getElementById('user-list');
        let item = document.querySelector(`.user-item[data-id="${userId}"]`);
        
        if (item) {
            // Update existing item
            item.querySelector('.user-last-msg').textContent = message;
            // Update time (simple format for now)
            const date = new Date(time);
            item.querySelector('.text-xs').textContent = date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            if (incrementUnread) {
                let badge = item.querySelector('.unread-badge');
                if (badge) {
                    badge.textContent = parseInt(badge.textContent) + 1;
                } else {
                    const badgeHtml = `<span class="bg-indigo-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full unread-badge shadow-sm shadow-indigo-200">1</span>`;
                    item.querySelector('.user-last-msg').parentElement.insertAdjacentHTML('beforeend', badgeHtml);
                }
            }
            
            // Move to top
            list.prepend(item);
        } else {
            // New user (not in list) - ideally fetch user details or just reload page
            // For now, reload to get fresh list
            location.reload(); 
        }
    }

    function loadChat(userId) {
        currentUserId = userId;
        document.getElementById('empty-state').classList.add('hidden');
        document.getElementById('messages-list').innerHTML = '<div class="flex justify-center py-10"><i class="fas fa-spinner fa-spin text-indigo-500 text-2xl"></i></div>';
        
        // Highlight active user
        document.querySelectorAll('.user-item').forEach(el => el.classList.remove('bg-indigo-50', 'ring-2', 'ring-indigo-100'));
        const activeItem = document.querySelector(`.user-item[data-id="${userId}"]`);
        if (activeItem) {
            activeItem.classList.add('bg-indigo-50', 'ring-2', 'ring-indigo-100');
            // Remove unread badge
            const badge = activeItem.querySelector('.unread-badge');
            if (badge) badge.remove();
        }

        const url = "{{ route('admin.chat.show', ':id') }}".replace(':id', userId);
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Update Header
                document.getElementById('chat-header-name').textContent = data.user.name;
                document.getElementById('chat-header-role').textContent = data.user.role; // Assuming role exists
                const avatar = document.getElementById('chat-header-avatar');
                avatar.src = data.user.profile_photo_url;
                avatar.classList.remove('hidden');
                document.getElementById('chat-header-status').classList.remove('hidden');

                // Render Messages
                const list = document.getElementById('messages-list');
                list.innerHTML = '';
                data.messages.forEach(msg => {
                    appendMessage(msg, msg.sender_id === adminId);
                });
                scrollToBottom();
            });
    }

    function appendMessage(message, isMe) {
        const list = document.getElementById('messages-list');
        const div = document.createElement('div');
        div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} group`;
        
        const bubbleColor = isMe 
            ? 'bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-indigo-200' 
            : 'bg-white text-gray-800 shadow-sm border border-gray-100';

        const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        // Reply Content (Quoted Message)
        let replyBlock = '';
        if (message.reply_to) {
            const isReplyMe = message.reply_to.sender_id === currentUserId; // currentUserId is the chat partner, so if sender_id == currentUserId it's them. Wait.
            // Actually 'isMe' is true if message.sender_id === adminId.
            // For reply: if reply_to.sender_id === adminId, it was sent by me.
            // adminId is needed here.
            
            const replySenderId = message.reply_to.sender_id;
            // We need to know if replySenderId is ME (Admin).
            // In blade we can inject adminId.
            
            const isReplyByMe = (replySenderId != currentUserId); // Rough check if not strictly defined
            // Better: use the sender name from relationship
            
            const replyName = message.reply_to.sender ? message.reply_to.sender.name : 'User';
            const replyBg = isMe ? 'bg-white/20 border-l-2 border-white/50' : 'bg-gray-100 border-l-2 border-indigo-500';
            const replyText = message.reply_to.message || (message.reply_to.attachment ? '📷 Photo' : 'Message');
            
            replyBlock = `
                <div class="mb-2 p-2 rounded text-xs ${replyBg} opacity-90">
                    <div class="font-bold mb-0.5">${replyName}</div>
                    <div class="truncate">${replyText}</div>
                </div>
            `;
        }

        let content = '';
        if (message.attachment) {
            content += `<img src="${message.attachment}" class="max-w-full rounded-lg mb-2 cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">`;
        }
        if (message.message) {
            content += `<p class="text-sm leading-relaxed">${message.message}</p>`;
        }

        // Reply Action Button
        const replyBtnClass = isMe 
            ? 'opacity-0 group-hover:opacity-100 absolute top-1/2 -translate-y-1/2 -left-8 text-gray-400 hover:text-indigo-600 transition-all p-1' 
            : 'opacity-0 group-hover:opacity-100 absolute top-1/2 -translate-y-1/2 -right-8 text-gray-400 hover:text-indigo-600 transition-all p-1';
            
        // For the reply button function, we need the sender name of the message we are replying TO.
        // If isMe is true, sender is 'You'. If false, sender is the current chat user.
        const msgSenderName = isMe ? 'You' : (document.getElementById('chat-header-name').textContent || 'User');
        const replyPayload = message.message ? message.message.replace(/'/g, "\\'") : 'Attachment';

        div.innerHTML = `
            <div class="max-w-[70%] relative">
                <button onclick="replyToMessage(${message.id}, '${replyPayload}', '${msgSenderName}')" class="${replyBtnClass}" title="Reply">
                    <i class="fas fa-reply"></i>
                </button>
                <div class="${bubbleColor} p-4 rounded-2xl shadow-lg relative ${isMe ? 'rounded-br-none' : 'rounded-bl-none'}">
                    ${replyBlock}
                    ${content}
                    <div class="flex items-center justify-end gap-1 mt-1 opacity-70">
                        <span class="text-[10px]">${time}</span>
                        ${isMe ? '<i class="fas fa-check-double text-[10px]"></i>' : ''}
                    </div>
                </div>
            </div>
        `;
        list.appendChild(div);
    }

    function scrollToBottom() {
        const list = document.getElementById('messages-list');
        list.scrollTop = list.scrollHeight;
    }

    // Global Reply Functions
    let replyToId = null;

    window.replyToMessage = function(id, text, name) {
        replyToId = id;
        document.getElementById('reply-to-name').textContent = 'Replying to ' + name;
        document.getElementById('reply-to-text').textContent = text || 'Attachment';
        document.getElementById('reply-banner').classList.remove('hidden');
        document.getElementById('message-input').focus();
    };

    window.cancelReply = function() {
        replyToId = null;
        document.getElementById('reply-banner').classList.add('hidden');
    };

    // Modal Elements
    const previewModal = document.getElementById('image-preview-modal');
    const previewImage = document.getElementById('preview-image');
    const previewThumbnail = document.getElementById('preview-thumbnail');
    const previewInput = document.getElementById('preview-message-input');
    const closePreviewBtn = document.getElementById('close-preview-btn');
    const sendPreviewBtn = document.getElementById('send-preview-btn');
    
    // Tools
    let cropper;
    let currentCroppedBlob = null;
    const emojiPicker = document.getElementById('emoji-picker');
    const mainEmojiPicker = document.getElementById('main-emoji-picker'); // Main Picker
    const btnCrop = document.getElementById('btn-crop');
    const btnEmoji = document.getElementById('btn-emoji');
    const btnMainEmoji = document.getElementById('btn-main-emoji'); // Main Button
    const cropControls = document.getElementById('crop-controls');
    
    // Initialize Emoji Picker
    const emojis = ['😀','😂','😍','😭','😡','👍','👎','🎉','🔥','❤️','✅','❌','👋','🙏','👀','🧠','💻','📱','🚀','⭐','💯','✨','📝','📸'];
    
    function initEmojiPicker(container, input) {
        emojis.forEach(emoji => {
            const span = document.createElement('span');
            span.textContent = emoji;
            span.className = 'cursor-pointer hover:bg-gray-200 p-2 rounded text-center text-xl transition-colors';
            span.onclick = () => {
                input.value += emoji;
                input.focus();
            };
            container.appendChild(span);
        });
    }

    // Initialize both pickers
    initEmojiPicker(emojiPicker, previewInput);
    initEmojiPicker(mainEmojiPicker, document.getElementById('message-input'));

    // Toggle Modal Emoji Picker
    btnEmoji.addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPicker.classList.toggle('hidden');
    });

    // Toggle Main Emoji Picker
    btnMainEmoji.addEventListener('click', (e) => {
        e.stopPropagation();
        mainEmojiPicker.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        // Modal Picker
        if (!emojiPicker.contains(e.target) && e.target !== btnEmoji && !btnEmoji.contains(e.target)) {
            emojiPicker.classList.add('hidden');
        }
        // Main Picker
        if (!mainEmojiPicker.contains(e.target) && e.target !== btnMainEmoji && !btnMainEmoji.contains(e.target)) {
            mainEmojiPicker.classList.add('hidden');
        }
    });

    // Crop Functionality
    btnCrop.addEventListener('click', () => {
        if (cropper) return;
        
        // Hide Footer
        document.querySelector('.p-4.bg-black\\/40').classList.add('hidden');
        
        cropper = new Cropper(previewImage, {
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 0.8,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            background: false,
        });
        
        cropControls.classList.remove('hidden');
    });

    document.getElementById('btn-confirm-crop').addEventListener('click', () => {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas();
        
        canvas.toBlob((blob) => {
            currentCroppedBlob = blob;
            previewImage.src = canvas.toDataURL();
            previewThumbnail.src = canvas.toDataURL(); // Update thumbnail too
            
            // Clean up
            cropper.destroy();
            cropper = null;
            cropControls.classList.add('hidden');
            document.querySelector('.p-4.bg-black\\/40').classList.remove('hidden'); // Show Footer
        });
    });

    document.getElementById('btn-cancel-crop').addEventListener('click', () => {
        if (!cropper) return;
        cropper.destroy();
        cropper = null;
        cropControls.classList.add('hidden');
        document.querySelector('.p-4.bg-black\\/40').classList.remove('hidden');
    });

    // Add More (Change Image)
    document.getElementById('btn-add-more').addEventListener('click', () => {
        document.getElementById('attachment-input').click();
    });

    // Handle File Selection & Modal Open
    document.getElementById('attachment-btn').addEventListener('click', () => {
        document.getElementById('attachment-input').click();
    });

    document.getElementById('attachment-input').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();

            // Reset crop if exists
            if (cropper) {
                cropper.destroy();
                cropper = null;
                cropControls.classList.add('hidden');
                document.querySelector('.p-4.bg-black\\/40').classList.remove('hidden');
            }
            currentCroppedBlob = null; // Reset previous crop

            reader.onload = function(e) {
                // Set images
                previewImage.src = e.target.result;
                previewThumbnail.src = e.target.result;

                // Show modal with animation
                previewModal.classList.remove('hidden');
                // Trigger reflow
                void previewModal.offsetWidth;
                previewModal.classList.remove('opacity-0');
                
                // Animate image
                setTimeout(() => {
                    previewImage.classList.remove('scale-95', 'opacity-0');
                    previewImage.classList.add('scale-100', 'opacity-100');
                }, 50);

                // Focus input
                previewInput.focus();
            }

            reader.readAsDataURL(file);
        }
    });

    // Close Modal
    function closePreviewModal() {
        // Cleanup Cropper if active
        if (cropper) {
            cropper.destroy();
            cropper = null;
            cropControls.classList.add('hidden');
            document.querySelector('.p-4.bg-black\\/40').classList.remove('hidden');
        }

        // Animate out
        previewModal.classList.add('opacity-0');
        previewImage.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            previewModal.classList.add('hidden');
            previewImage.classList.remove('scale-100', 'opacity-100');
            // Clear inputs
            document.getElementById('attachment-input').value = '';
            previewInput.value = '';
            previewImage.src = '';
            currentCroppedBlob = null;
        }, 300);
    }

    closePreviewBtn.addEventListener('click', closePreviewModal);

    // Send from Modal
    sendPreviewBtn.addEventListener('click', function() {
        const caption = previewInput.value;
        if (caption) {
            document.getElementById('message-input').value = caption;
        }
        
        // Trigger send
        document.getElementById('chat-form').dispatchEvent(new Event('submit'));
        
        // Close modal immediately
        previewModal.classList.add('opacity-0');
        setTimeout(() => {
            previewModal.classList.add('hidden');
            previewInput.value = '';
            previewImage.src = '';
            currentCroppedBlob = null;
        }, 300);
    });

    // Enter key in modal input
    previewInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendPreviewBtn.click();
        }
    });

    // Send Message (Chat Form)
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const messageInput = document.getElementById('message-input');
        const fileInput = document.getElementById('attachment-input');
        const message = messageInput.value.trim();
        // Use cropped blob if available, otherwise file input
        const file = currentCroppedBlob || fileInput.files[0];

        if ((!message && !file) || !currentUserId) return;

        const formData = new FormData();
        formData.append('receiver_id', currentUserId);
        if (message) formData.append('message', message);
        if (file) {
            // Append with filename if it's a blob
            if (currentCroppedBlob) {
                formData.append('attachment', file, 'cropped-image.png');
            } else {
                formData.append('attachment', file);
            }
        }
        
        // Add Reply ID
        if (replyToId) {
            formData.append('reply_to_id', replyToId);
        }

        // Optimistic UI Update (for text only)
        if (!file) {
            const tempMsg = {
                message: message,
                created_at: new Date().toISOString(),
                sender_id: adminId,
                reply_to: replyToId ? {
                    sender: { name: document.getElementById('reply-to-name').textContent.replace('Replying to ', '') },
                    message: document.getElementById('reply-to-text').textContent
                } : null
            };
            appendMessage(tempMsg, true);
            scrollToBottom();
            messageInput.value = '';
            cancelReply();
        }

        fetch('{{ route('admin.chat.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (file) {
                appendMessage(data, true);
                scrollToBottom();
                fileInput.value = '';
                currentCroppedBlob = null;
                cancelReply();
            }
            
            // Update Sidebar (Move to top)
            updateUserListItem(data.receiver_id, data.message || (data.attachment ? 'Sent an attachment' : ''), data.created_at, false);
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle Enter key to send
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chat-form').dispatchEvent(new Event('submit'));
        }
    });

    // Auto-load chat if user_id is present in URL
    const urlParams = new URLSearchParams(window.location.search);
    const initialUserId = urlParams.get('user_id');
    if (initialUserId) {
        loadChat(initialUserId);
    }
</script>
@endpush
@endsection
