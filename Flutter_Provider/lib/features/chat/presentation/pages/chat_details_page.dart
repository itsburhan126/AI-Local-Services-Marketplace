import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';
import 'package:file_picker/file_picker.dart';
import 'package:emoji_picker_flutter/emoji_picker_flutter.dart' as emoji;
import 'package:geolocator/geolocator.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:record/record.dart';
import 'package:audioplayers/audioplayers.dart';
import 'package:path_provider/path_provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:intl/intl.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../../core/constants/api_constants.dart';
import '../../../auth/data/models/user_model.dart';
import '../../data/models/chat_message_model.dart';
import 'package:dio/dio.dart';
import '../providers/chat_provider.dart';
import '../../../auth/presentation/providers/auth_provider.dart';
import 'chat_user_profile_page.dart';
import '../widgets/ai_options_sheet.dart';

class ChatDetailsPage extends StatefulWidget {
  final UserModel otherUser;

  const ChatDetailsPage({super.key, required this.otherUser});

  @override
  State<ChatDetailsPage> createState() => _ChatDetailsPageState();
}

class _ChatDetailsPageState extends State<ChatDetailsPage> {
  final TextEditingController _messageController = TextEditingController();
  final ScrollController _scrollController = ScrollController();
  final FocusNode _focusNode = FocusNode();
  final AudioRecorder _audioRecorder = AudioRecorder();
  final AudioPlayer _audioPlayer = AudioPlayer();

  bool _showEmojiPicker = false;
  bool _isRecording = false;
  String? _audioPath;
  bool _isSearching = false;
  String _searchQuery = '';

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final currentUser = context.read<AuthProvider>().user;
      if (currentUser?.id != null) {
        context.read<ChatProvider>().initPusher(currentUser!.id!);
      }
      if (widget.otherUser.id != null) {
        context.read<ChatProvider>().loadMessages(int.parse(widget.otherUser.id!.toString()));
      }
    });

    _focusNode.addListener(() {
      if (_focusNode.hasFocus) {
        setState(() {
          _showEmojiPicker = false;
        });
      }
    });
  }

  @override
  void dispose() {
    _messageController.dispose();
    _scrollController.dispose();
    _focusNode.dispose();
    _audioRecorder.dispose();
    _audioPlayer.dispose();
    context.read<ChatProvider>().clearCurrentChat();
    super.dispose();
  }

  void _scrollToBottom() {
    if (_scrollController.hasClients) {
      _scrollController.animateTo(
        0.0,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeOut,
      );
    }
  }

  void _sendMessage() {
    final text = _messageController.text.trim();
    if (text.isEmpty) return;

    final currentUser = context.read<AuthProvider>().user;
    if (currentUser?.id == null || widget.otherUser.id == null) return;

    context.read<ChatProvider>().sendMessage(
      widget.otherUser.id!,
      text,
      currentUser!.id!,
    );

    _messageController.clear();
    _scrollToBottom();
  }

  @override
  Widget build(BuildContext context) {
    final chatState = context.watch<ChatProvider>();
    final allMessages = chatState.currentMessages;
    final messages = _isSearching && _searchQuery.isNotEmpty
        ? allMessages.where((m) => m.message.toLowerCase().contains(_searchQuery.toLowerCase())).toList()
        : allMessages;

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0.5,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.black),
          onPressed: () {
            if (_isSearching) {
              setState(() {
                _isSearching = false;
                _searchQuery = '';
              });
            } else {
              Navigator.pop(context);
            }
          },
        ),
        title: _isSearching
            ? TextField(
                autofocus: true,
                decoration: const InputDecoration(
                  hintText: "Search messages...",
                  border: InputBorder.none,
                  hintStyle: TextStyle(color: Colors.grey),
                ),
                style: const TextStyle(color: Colors.black, fontSize: 16),
                onChanged: (value) {
                  setState(() {
                    _searchQuery = value;
                  });
                },
              )
            : GestureDetector(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => ChatUserProfilePage(user: widget.otherUser),
                    ),
                  );
                },
                child: Row(
                  children: [
                    CircleAvatar(
                      radius: 20,
                      backgroundImage: widget.otherUser.profileImage != null
                          ? CachedNetworkImageProvider(widget.otherUser.profileImage!)
                          : null,
                      child: widget.otherUser.profileImage == null
                          ? Text(widget.otherUser.name?[0] ?? '?', style: const TextStyle(fontWeight: FontWeight.bold))
                          : null,
                    ),
                    const SizedBox(width: 12),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          widget.otherUser.name ?? 'User',
                          style: const TextStyle(color: Colors.black, fontSize: 16, fontWeight: FontWeight.bold),
                        ),
                        if (chatState.isOtherUserTyping)
                          Text(
                            "Typing...",
                            style: TextStyle(color: Colors.blue[600], fontSize: 12, fontWeight: FontWeight.w500),
                          )
                        else
                          Text(
                            "Online",
                            style: TextStyle(color: Colors.green[600], fontSize: 12, fontWeight: FontWeight.w500),
                          ),
                      ],
                    ),
                  ],
                ),
              ),
        actions: [
          if (!_isSearching)
            IconButton(
              icon: const Icon(Icons.call, color: Colors.black),
              onPressed: () {
                 ScaffoldMessenger.of(context).showSnackBar(
                   SnackBar(
                     content: Row(
                       children: const [
                         Icon(Icons.info_outline, color: Colors.white),
                         SizedBox(width: 10),
                         Text("Audio Call Feature Coming Soon!", style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                       ],
                     ),
                     backgroundColor: Colors.blueAccent,
                     behavior: SnackBarBehavior.floating,
                     shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                     duration: const Duration(seconds: 2),
                   ),
                 );
              },
            ),
          PopupMenuButton<String>(
            icon: const Icon(Icons.more_vert_rounded, color: Colors.black),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            onSelected: (value) {
              if (value == 'report') {
                _showReportDialog();
              } else if (value == 'block') {
                _showBlockConfirmation();
              } else if (value == 'search') {
                setState(() {
                  _isSearching = true;
                });
              } else if (value == 'clear') {
                _showClearChatConfirmation();
              }
            },
            itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
              const PopupMenuItem<String>(
                value: 'search',
                child: Row(
                  children: [
                    Icon(Icons.search_rounded, size: 20, color: Colors.black54),
                    SizedBox(width: 12),
                    Text('Search'),
                  ],
                ),
              ),
              const PopupMenuItem<String>(
                value: 'clear',
                child: Row(
                  children: [
                    Icon(Icons.delete_outline_rounded, size: 20, color: Colors.black54),
                    SizedBox(width: 12),
                    Text('Clear Chat'),
                  ],
                ),
              ),
              const PopupMenuItem<String>(
                value: 'report',
                child: Row(
                  children: [
                    Icon(Icons.flag_outlined, size: 20, color: Colors.black54),
                    SizedBox(width: 12),
                    Text('Report'),
                  ],
                ),
              ),
              const PopupMenuItem<String>(
                value: 'block',
                child: Row(
                  children: [
                    Icon(Icons.block_rounded, size: 20, color: Colors.redAccent),
                    SizedBox(width: 12),
                    Text('Block', style: TextStyle(color: Colors.redAccent)),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
      body: SafeArea(
        child: Column(
          children: [
            Expanded(
              child: ListView.builder(
                reverse: true,
                controller: _scrollController,
                padding: const EdgeInsets.all(16),
                itemCount: messages.length,
                itemBuilder: (context, index) {
                  final message = messages[index];
                  final isMe = message.senderId == context.read<AuthProvider>().user?.id;
                  return _buildMessageItem(message, isMe);
                },
              ),
            ),
            if (chatState.isOtherUserTyping)
              Padding(
                padding: const EdgeInsets.only(left: 16, bottom: 8),
                child: _buildTypingIndicator(),
              ),
            _buildInputArea(),
            if (_showEmojiPicker)
              SizedBox(
                height: 250,
                child: emoji.EmojiPicker(
                  textEditingController: _messageController,
                  config: emoji.Config(
                    checkPlatformCompatibility: true,
                    emojiViewConfig: emoji.EmojiViewConfig(
                      // emojiSizeMax: 32 * (Platform.isIOS ? 1.30 : 1.0),
                      verticalSpacing: 0,
                      horizontalSpacing: 0,
                      gridPadding: EdgeInsets.zero,
                      recentsLimit: 28,
                      replaceEmojiOnLimitExceed: false,
                      noRecents: const Text(
                        'No Recents',
                        style: TextStyle(fontSize: 20, color: Colors.black26),
                        textAlign: TextAlign.center,
                      ),
                      loadingIndicator: const SizedBox.shrink(),
                      buttonMode: emoji.ButtonMode.MATERIAL,
                      backgroundColor: const Color(0xFFF2F2F2),
                    ),
                    skinToneConfig: const emoji.SkinToneConfig(
                      dialogBackgroundColor: Colors.white,
                      indicatorColor: Colors.grey,
                      enabled: true,
                    ),
                    categoryViewConfig: const emoji.CategoryViewConfig(
                      indicatorColor: Colors.blue,
                      iconColor: Colors.grey,
                      iconColorSelected: Colors.blue,
                      backspaceColor: Colors.blue,
                      tabIndicatorAnimDuration: kTabScrollDuration,
                      categoryIcons: emoji.CategoryIcons(),
                      backgroundColor: Color(0xFFF2F2F2),
                    ),
                    bottomActionBarConfig: const emoji.BottomActionBarConfig(
                      backgroundColor: Color(0xFFF2F2F2),
                      buttonColor: Color(0xFFF2F2F2),
                      buttonIconColor: Colors.grey,
                    ),
                    searchViewConfig: const emoji.SearchViewConfig(
                      backgroundColor: Color(0xFFF2F2F2),
                    ),
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _buildMessageItem(ChatMessageModel message, bool isMe) {
    return Align(
      alignment: isMe ? Alignment.centerRight : Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.75),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: isMe ? const Color(0xFF6366F1) : Colors.grey[100],
          borderRadius: BorderRadius.only(
            topLeft: const Radius.circular(16),
            topRight: const Radius.circular(16),
            bottomLeft: isMe ? const Radius.circular(16) : Radius.zero,
            bottomRight: isMe ? Radius.zero : const Radius.circular(16),
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (message.attachment != null)
              _buildAttachmentPreview(message.attachment!, isMe),
            if (message.message.isNotEmpty)
              if (message.message.startsWith("Location: "))
                 _buildLocationPreview(message.message!, isMe)
              else if (message.message.startsWith("✨ SPECIAL OFFER ✨"))
                 _buildOfferPreview(message.message!, isMe)
              else
                Text(
                  message.message,
                  style: TextStyle(
                    color: isMe ? Colors.white : Colors.black87,
                    fontSize: 15,
                  ),
                ),
            const SizedBox(height: 4),
            Row(
              mainAxisSize: MainAxisSize.min,
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                Text(
                  DateFormat('hh:mm a').format(message.createdAt ?? DateTime.now()),
                  style: TextStyle(
                    color: isMe ? Colors.white70 : Colors.grey[500],
                    fontSize: 10,
                  ),
                ),
                if (isMe) ...[
                  const SizedBox(width: 4),
                  if (message.readAt != null)
                    const Icon(Icons.done_all_rounded, size: 16, color: Colors.lightBlueAccent)
                  else if (message.deliveredAt != null)
                    const Icon(Icons.done_all_rounded, size: 16, color: Colors.white70)
                  else
                    const Icon(Icons.check_rounded, size: 16, color: Colors.white70),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAttachmentPreview(String path, bool isMe) {
    if (path.endsWith('.jpg') || path.endsWith('.png') || path.endsWith('.jpeg')) {
      String displayPath = path;
      bool isNetwork = path.startsWith('http');
      
      if (!isNetwork && path.startsWith('/storage')) {
         displayPath = '${ApiConstants.baseUrl}$path';
         isNetwork = true;
      }

      return Padding(
        padding: const EdgeInsets.only(bottom: 8),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(8),
          child: isNetwork
              ? CachedNetworkImage(
                  imageUrl: displayPath,
                  width: 200,
                  fit: BoxFit.cover,
                  placeholder: (context, url) => const CircularProgressIndicator(),
                  errorWidget: (context, url, error) => const Icon(Icons.error),
                )
              : Image.file(File(path), width: 200, fit: BoxFit.cover),
        ),
      );
    } else if (path.endsWith('.m4a') || path.endsWith('.mp3')) {
      return Container(
        padding: const EdgeInsets.all(8),
        margin: const EdgeInsets.only(bottom: 8),
        decoration: BoxDecoration(
          color: Colors.black12,
          borderRadius: BorderRadius.circular(8),
        ),
        child: const Row(
          children: [
            Icon(Icons.audiotrack, size: 20),
            SizedBox(width: 8),
            Text("Audio Message"),
          ],
        ),
      );
    }
    return const SizedBox.shrink();
  }

  Widget _buildOfferPreview(String message, bool isMe) {
    return Container(
      width: 260,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
        border: Border.all(color: Colors.indigo.withOpacity(0.1)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.indigo.withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.local_offer_rounded, color: Colors.indigo, size: 20),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Text(
                  "Special Offer",
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                    color: Colors.black87,
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            message.replaceAll("✨ SPECIAL OFFER ✨\n\n", ""),
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: Colors.black87,
              height: 1.5,
            ),
          ),
          const SizedBox(height: 16),
          if (!isMe)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                   // In a real app, this would trigger a booking flow
                   ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Offer Accepted! Proceeding to booking...")));
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.indigo,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
                child: const Text("Accept Offer"),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildLocationPreview(String message, bool isMe) {
    final url = message.replaceFirst("Location: ", "");
    return GestureDetector(
      onTap: () async {
        final uri = Uri.parse(url);
        if (await canLaunchUrl(uri)) {
          await launchUrl(uri, mode: LaunchMode.externalApplication);
        }
      },
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            height: 120,
            width: 200,
            decoration: BoxDecoration(
              color: Colors.grey[200],
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.grey.withOpacity(0.3)),
            ),
            child: Stack(
              children: [
                Center(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.map_rounded, color: Colors.blue[400], size: 40),
                      const SizedBox(height: 8),
                      Text("View Location", style: TextStyle(color: Colors.grey[600], fontWeight: FontWeight.w500)),
                    ],
                  ),
                ),
                Positioned(
                  right: 8,
                  top: 8,
                  child: Container(
                     padding: const EdgeInsets.all(4),
                     decoration: const BoxDecoration(
                       color: Colors.white,
                       shape: BoxShape.circle
                     ),
                     child: const Icon(Icons.location_on, color: Colors.red, size: 20),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            "Open in Maps",
            style: GoogleFonts.plusJakartaSans(
              color: isMe ? Colors.white : Colors.blue,
              fontSize: 14,
              fontWeight: FontWeight.w600,
              decoration: TextDecoration.underline,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTypingIndicator() {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.grey[200],
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          _buildDot(0),
          const SizedBox(width: 4),
          _buildDot(1),
          const SizedBox(width: 4),
          _buildDot(2),
        ],
      ),
    );
  }

  Widget _buildDot(int index) {
    return Container(
      width: 8,
      height: 8,
      decoration: const BoxDecoration(
        color: Colors.grey,
        shape: BoxShape.circle,
      ),
    ).animate(onPlay: (controller) => controller.repeat())
     .scale(duration: 600.ms, delay: (index * 200).ms, begin: const Offset(1, 1), end: const Offset(1.5, 1.5))
     .then().scale(begin: const Offset(1.5, 1.5), end: const Offset(1, 1));
  }

  Widget _buildInputArea() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 8),
      color: Colors.white,
      child: Row(
        children: [
           IconButton(
             icon: const Icon(Icons.add_rounded, color: Color(0xFF6366F1), size: 28),
             onPressed: _showAttachmentSheet,
           ),
          Expanded(
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              decoration: BoxDecoration(
                color: const Color(0xFFF3F4F6),
                borderRadius: BorderRadius.circular(24),
              ),
              child: TextField(
                controller: _messageController,
                focusNode: _focusNode,
                minLines: 1,
                maxLines: 4,
                style: const TextStyle(fontSize: 16),
                decoration: const InputDecoration(
                  hintText: "Message",
                  hintStyle: TextStyle(color: Colors.grey, fontSize: 16),
                  border: InputBorder.none,
                  contentPadding: EdgeInsets.symmetric(vertical: 10),
                  isDense: true,
                ),
                onChanged: (value) {
                  context.read<ChatProvider>().sendTypingEvent(int.parse(widget.otherUser.id!.toString()));
                },
              ),
            ),
          ),
          const SizedBox(width: 8),
          GestureDetector(
            onTap: _sendMessage,
            child: Container(
              width: 44,
              height: 44,
              decoration: const BoxDecoration(
                color: Color(0xFF6366F1),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.send_rounded, color: Colors.white, size: 22),
              alignment: Alignment.center,
            ),
          ),
        ],
      ),
    );
  }

  void _handleAttachment(String type) async {
    Navigator.pop(context);
    
    String? path;
    
    try {
      if (type == 'image') {
        final ImagePicker picker = ImagePicker();
        final XFile? image = await picker.pickImage(source: ImageSource.gallery);
        path = image?.path;
      } else if (type == 'video') {
        final ImagePicker picker = ImagePicker();
        final XFile? video = await picker.pickVideo(source: ImageSource.gallery);
        path = video?.path;
      } else if (type == 'file') {
        final allowedExtensions = context.read<ChatProvider>().allowedExtensions;
        FilePickerResult? result = await FilePicker.platform.pickFiles(
          type: allowedExtensions.isNotEmpty ? FileType.custom : FileType.any,
          allowedExtensions: allowedExtensions.isNotEmpty ? allowedExtensions : null,
        );
        path = result?.files.single.path;
      } else if (type == 'camera') {
         final ImagePicker picker = ImagePicker();
        final XFile? image = await picker.pickImage(source: ImageSource.camera);
        path = image?.path;
      }
      
      if (path != null) {
         if (!mounted) return;
         
         // Server control: Allowed extensions validation for ALL file types
         final allowedExtensions = context.read<ChatProvider>().allowedExtensions;
         if (allowedExtensions.isNotEmpty) {
           final extension = path.split('.').last.toLowerCase();
           if (!allowedExtensions.contains(extension)) {
             ScaffoldMessenger.of(context).showSnackBar(
               SnackBar(content: Text("File type '.$extension' is not allowed. Allowed: ${allowedExtensions.join(', ')}")),
             );
             return;
           }
         }

         final file = File(path);
         final fileSize = await file.length();
         if (!mounted) return;
         final maxFileSize = context.read<ChatProvider>().maxFileSize;
         
         if (fileSize > maxFileSize) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text("File size exceeds limit of ${maxFileSize ~/ (1024*1024)}MB")),
            );
            return;
         }

         final currentUser = context.read<AuthProvider>().user;
         if (currentUser?.id == null || widget.otherUser.id == null) return;

         context.read<ChatProvider>().sendMessage(
          widget.otherUser.id!, 
          "Sent an attachment",
          currentUser!.id!,
          attachmentPath: path,
        );
      }
    } catch (e) {
      debugPrint("Error picking file: $e");
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error picking file: $e")),
      );
    }
  }

  Future<void> _handleAudio() async {
     Navigator.pop(context);
     
     if (!await Permission.microphone.request().isGranted) {
       if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Microphone permission required")));
       return;
     }

     showModalBottomSheet(
       context: context,
       isDismissible: false,
       enableDrag: false,
       builder: (context) => StatefulBuilder(
         builder: (context, setSheetState) {
           return Container(
             padding: const EdgeInsets.all(24),
             child: Column(
               mainAxisSize: MainAxisSize.min,
               children: [
                 const Text("Record Audio", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                 const SizedBox(height: 30),
                 GestureDetector(
                   onTap: () async {
                      if (_isRecording) {
                        final path = await _audioRecorder.stop();
                        setSheetState(() {
                          _isRecording = false;
                          _audioPath = path;
                        });
                        
                        if (mounted) {
                          Navigator.pop(context);
                          _showAudioPreview(path!);
                        }
                      } else {
                        if (await _audioRecorder.hasPermission()) {
                          final dir = await getTemporaryDirectory();
                          final path = '${dir.path}/audio_${DateTime.now().millisecondsSinceEpoch}.m4a';
                          
                          await _audioRecorder.start(const RecordConfig(), path: path);
                          setSheetState(() {
                            _isRecording = true;
                          });
                        }
                      }
                   },
                   child: Container(
                     padding: const EdgeInsets.all(24),
                     decoration: BoxDecoration(
                       color: _isRecording ? Colors.red.withOpacity(0.1) : Colors.red,
                       shape: BoxShape.circle,
                       border: _isRecording ? Border.all(color: Colors.red, width: 2) : null,
                     ),
                     child: Icon(
                       _isRecording ? Icons.stop_rounded : Icons.mic_rounded,
                       color: _isRecording ? Colors.red : Colors.white,
                       size: 40,
                     ),
                   ).animate(target: _isRecording ? 1 : 0),
                 ),
                 const SizedBox(height: 20),
                 Text(
                   _isRecording ? "Recording..." : "Tap to record",
                   style: TextStyle(color: Colors.grey[600]),
                 ),
                 const SizedBox(height: 10),
                 if (_isRecording)
                   TextButton(
                     onPressed: () async {
                       await _audioRecorder.cancel();
                       if (mounted) Navigator.pop(context);
                     }, 
                     child: const Text("Cancel", style: TextStyle(color: Colors.grey))
                   )
                 else
                   TextButton(
                     onPressed: () => Navigator.pop(context), 
                     child: const Text("Close")
                   ),
               ],
             ),
           );
         }
       ),
     );
  }

  void _showAudioPreview(String path) {
    showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text("Audio Preview", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 20),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                IconButton(
                  icon: const Icon(Icons.play_arrow_rounded, size: 32, color: Colors.blue),
                  onPressed: () {
                    _audioPlayer.play(DeviceFileSource(path));
                  },
                ),
                const SizedBox(width: 20),
                IconButton(
                  icon: const Icon(Icons.delete_outline_rounded, size: 32, color: Colors.red),
                  onPressed: () {
                    Navigator.pop(context);
                  },
                ),
              ],
            ),
            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(context);
                  _sendAudio(path);
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF6366F1),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: const Text("Send Audio", style: TextStyle(color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _sendAudio(String path) {
     final currentUser = context.read<AuthProvider>().user;
     if (currentUser?.id == null || widget.otherUser.id == null) return;

     context.read<ChatProvider>().sendMessage(
      widget.otherUser.id!, 
      "Sent an audio message",
      currentUser!.id!,
      attachmentPath: path,
    );
  }

  Future<void> _handleLocation() async {
    Navigator.pop(context);
    
    bool serviceEnabled;
    LocationPermission permission;

    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Location services are disabled.')));
      return;
    }

    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) {
        if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Location permissions are denied')));
        return;
      }
    }
    
    if (permission == LocationPermission.deniedForever) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Location permissions are permanently denied, we cannot request permissions.')));
      return;
    }

    try {
      Position position = await Geolocator.getCurrentPosition();
      final locationUrl = "https://www.google.com/maps/search/?api=1&query=${position.latitude},${position.longitude}";
      
      if (!mounted) return;
      final currentUser = context.read<AuthProvider>().user;
      if (currentUser?.id == null || widget.otherUser.id == null) return;

      context.read<ChatProvider>().sendMessage(
        widget.otherUser.id!, 
        "Location: $locationUrl",
        currentUser!.id!,
      );
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error getting location: $e')));
    }
  }

  void _handleAIAssistant() {
    Navigator.pop(context);
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => AIOptionsSheet(
        onOptionSelected: (text) {
          setState(() {
            _messageController.text = text;
          });
        },
        currentText: _messageController.text,
        isCustomer: false,
      ),
    );
  }

  void _handleCreateOffer() {
    Navigator.pop(context);
    showDialog(
      context: context, 
      builder: (context) => CreateOfferDialog(otherUserId: int.parse(widget.otherUser.id!.toString()))
    );
  }

  void _showAttachmentSheet() {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) => Container(
        margin: const EdgeInsets.all(16),
        padding: const EdgeInsets.symmetric(vertical: 24, horizontal: 16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(24),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Wrap(
              spacing: 24,
              runSpacing: 24,
              alignment: WrapAlignment.center,
              children: [
                _buildAttachmentOption(Icons.image_rounded, const Color(0xFF9C27B0), "Gallery", () => _handleAttachment('image')),
                _buildAttachmentOption(Icons.camera_alt_rounded, const Color(0xFFE91E63), "Camera", () => _handleAttachment('camera')),
                _buildAttachmentOption(Icons.location_on_rounded, const Color(0xFF4CAF50), "Location", _handleLocation),
                _buildAttachmentOption(Icons.mic_rounded, const Color(0xFFFF9800), "Audio", _handleAudio),
                _buildAttachmentOption(Icons.description_rounded, const Color(0xFF5E35B1), "Document", () => _handleAttachment('file')),
                _buildAttachmentOption(Icons.emoji_emotions_rounded, const Color(0xFFFFC107), "Emoji", () {
                  Navigator.pop(context);
                  setState(() {
                    _showEmojiPicker = !_showEmojiPicker;
                    if (_showEmojiPicker) {
                      _focusNode.unfocus();
                    }
                  });
                }),
                _buildAttachmentOption(Icons.local_offer_rounded, const Color(0xFF2196F3), "Offer", _handleCreateOffer),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAttachmentOption(IconData icon, Color color, String label, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: color,
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: Colors.white, size: 28),
          ),
          const SizedBox(height: 8),
          Text(label, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500, color: Colors.black87)),
        ],
      ),
    );
  }

  void _showBlockConfirmation() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Block User"),
        content: const Text("Are you sure you want to block this user? You won't receive any messages from them."),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text("Cancel"),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              _blockUser();
            },
            child: const Text("Block", style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  void _blockUser() async {
    try {
      if (widget.otherUser.id == null) return;
      await context.read<ChatProvider>().blockUser(int.parse(widget.otherUser.id!.toString()));
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("User blocked successfully")));
        Navigator.pop(context); // Go back to list
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Failed to block user: $e")));
      }
    }
  }

  void _showReportDialog() {
    final TextEditingController reasonController = TextEditingController();
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Report User"),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text("Please provide a reason for reporting this user:"),
            const SizedBox(height: 10),
            TextField(
              controller: reasonController,
              decoration: const InputDecoration(
                hintText: "Reason (e.g. spam, abuse)",
                border: OutlineInputBorder(),
              ),
              maxLines: 3,
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text("Cancel"),
          ),
          ElevatedButton(
            onPressed: () {
              if (reasonController.text.isNotEmpty) {
                Navigator.pop(context);
                _reportUser(reasonController.text);
              }
            },
            child: const Text("Report"),
          ),
        ],
      ),
    );
  }

  void _reportUser(String reason) async {
    try {
      if (widget.otherUser.id == null) return;
      await context.read<ChatProvider>().reportUser(int.parse(widget.otherUser.id!.toString()), reason);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("User reported successfully")));
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Failed to report user: $e")));
      }
    }
  }

  void _showClearChatConfirmation() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Clear Chat"),
        content: const Text("Are you sure you want to clear this chat history? This action cannot be undone."),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text("Cancel"),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              _clearChat();
            },
            child: const Text("Clear", style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  void _clearChat() async {
    try {
      if (widget.otherUser.id == null) return;
      await context.read<ChatProvider>().clearChat(int.parse(widget.otherUser.id!.toString()));
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Chat cleared successfully")));
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Failed to clear chat: $e")));
      }
    }
  }
}

class CreateOfferDialog extends StatefulWidget {
  final int otherUserId;
  const CreateOfferDialog({super.key, required this.otherUserId});

  @override
  State<CreateOfferDialog> createState() => _CreateOfferDialogState();
}

class _CreateOfferDialogState extends State<CreateOfferDialog> {
  final _titleController = TextEditingController();
  final _priceController = TextEditingController();
  final _descController = TextEditingController();

  @override
  void dispose() {
    _titleController.dispose();
    _priceController.dispose();
    _descController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      title: const Text("Create Custom Offer", style: TextStyle(fontWeight: FontWeight.bold)),
      content: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextField(
              controller: _titleController,
              decoration: InputDecoration(
                labelText: "Service Title",
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                filled: true,
                fillColor: Colors.grey[50],
              ),
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _priceController,
              decoration: InputDecoration(
                labelText: "Price (\$)",
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                filled: true,
                fillColor: Colors.grey[50],
              ),
              keyboardType: TextInputType.number,
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _descController,
              decoration: InputDecoration(
                labelText: "Description",
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                filled: true,
                fillColor: Colors.grey[50],
              ),
              maxLines: 3,
            ),
          ],
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.pop(context), 
          child: const Text("Cancel", style: TextStyle(color: Colors.grey))
        ),
        ElevatedButton(
          onPressed: () {
            final title = _titleController.text;
            final price = _priceController.text;
            final desc = _descController.text;
            
            if (title.isEmpty || price.isEmpty) {
              ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Please fill required fields")));
              return;
            }

            Navigator.pop(context);
            
            final offerMessage = "✨ SPECIAL OFFER ✨\n\n📌 Service: $title\n💰 Price: \$$price\n📝 Details: $desc\n\nReply to accept this offer.";
            
            final currentUser = context.read<AuthProvider>().user;
            if (currentUser?.id != null) {
              context.read<ChatProvider>().sendMessage(
                widget.otherUserId, 
                offerMessage, 
                currentUser!.id!
              );
              ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Offer Sent Successfully!")));
            }
          },
          style: ElevatedButton.styleFrom(
            backgroundColor: const Color(0xFF6366F1),
            foregroundColor: Colors.white,
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
          ),
          child: const Text("Send Offer"),
        ),
      ],
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      backgroundColor: Colors.white,
      surfaceTintColor: Colors.white,
    );
  }
}


