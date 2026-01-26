import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import '../../../auth/presentation/providers/auth_provider.dart';
import '../../data/models/chat_conversation_model.dart';
import '../providers/chat_provider.dart';

class ChatPage extends StatefulWidget {
  const ChatPage({super.key});

  @override
  State<ChatPage> createState() => _ChatPageState();
}

class _ChatPageState extends State<ChatPage> {
  final TextEditingController _searchController = TextEditingController();
  int _activeFilter = 0;
  String _searchQuery = '';

  @override
  void initState() {
    super.initState();
    _searchController.addListener(() {
      setState(() {
        _searchQuery = _searchController.text.trim().toLowerCase();
      });
    });
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final user = context.read<AuthProvider>().user;
      if (user?.id != null) {
        context.read<ChatProvider>().initPusher(user!.id!);
        context.read<ChatProvider>().loadConversations();
      }
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: Consumer<ChatProvider>(
        builder: (context, provider, child) {
          final conversations = provider.conversations;
          final filtered = _applyFilter(conversations, _activeFilter, _searchQuery);

          return CustomScrollView(
            physics: const BouncingScrollPhysics(),
            slivers: [
              _buildHeader(context),
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 12),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildSearch(),
                      const SizedBox(height: 12),
                      _buildFilters(),
                    ],
                  ),
                ),
              ),
              provider.isLoading
                  ? const SliverFillRemaining(
                      child: Center(child: CircularProgressIndicator()),
                    )
                  : filtered.isEmpty
                      ? SliverFillRemaining(
                          hasScrollBody: false,
                          child: Padding(
                            padding: const EdgeInsets.fromLTRB(20, 0, 20, 140),
                            child: _buildEmptyState(context),
                          ),
                        )
                      : SliverPadding(
                          padding: const EdgeInsets.fromLTRB(20, 8, 20, 140),
                          sliver: SliverList.separated(
                            itemCount: filtered.length,
                            separatorBuilder: (context, index) =>
                                const SizedBox(height: 12),
                            itemBuilder: (context, index) =>
                                _buildThreadCard(context, filtered[index], index),
                          ),
                        ),
            ],
          );
        },
      ),
    );
  }

  List<ChatConversationModel> _applyFilter(
    List<ChatConversationModel> items,
    int filter,
    String query,
  ) {
    var result = items;

    if (query.isNotEmpty) {
      result = result.where((t) {
        final name = (t.user.name ?? '').toLowerCase();
        final lastMsg = (t.lastMessageContent ?? '').toLowerCase();
        return name.contains(query) || lastMsg.contains(query);
      }).toList();
    }

    if (filter == 1) {
      result = result.where((t) => t.unreadCount > 0).toList();
    }

    return result;
  }

  Widget _buildHeader(BuildContext context) {
    return SliverAppBar(
      pinned: true,
      expandedHeight: 150,
      backgroundColor: Colors.transparent,
      elevation: 0,
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          fit: StackFit.expand,
          children: [
            Container(
              decoration: const BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [
                    Color(0xFFEEF2FF),
                    Color(0xFFF8FAFC),
                    Color(0xFFECFEFF),
                  ],
                ),
              ),
            ),
            Positioned(
              left: -40,
              top: -40,
              child: Container(
                width: 140,
                height: 140,
                decoration: BoxDecoration(
                  color: const Color(0xFF6366F1).withValues(alpha: 0.18),
                  shape: BoxShape.circle,
                ),
              ).animate().fadeIn(duration: 500.ms).scale(begin: const Offset(0.9, 0.9)),
            ),
            Positioned(
              right: -50,
              top: 20,
              child: Container(
                width: 170,
                height: 170,
                decoration: BoxDecoration(
                  color: const Color(0xFF8B5CF6).withValues(alpha: 0.14),
                  shape: BoxShape.circle,
                ),
              ).animate().fadeIn(duration: 650.ms).scale(begin: const Offset(0.9, 0.9)),
            ),
            Align(
              alignment: Alignment.bottomCenter,
              child: Padding(
                padding: const EdgeInsets.fromLTRB(20, 0, 20, 16),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(20),
                  child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 14, sigmaY: 14),
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 14,
                      ),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.65),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(
                          color: Colors.white.withValues(alpha: 0.9),
                          width: 1,
                        ),
                        boxShadow: [
                          BoxShadow(
                            color: Colors.black.withValues(alpha: 0.06),
                            blurRadius: 24,
                            offset: const Offset(0, 10),
                          ),
                        ],
                      ),
                      child: Row(
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Text(
                                  'Chat',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 20,
                                    fontWeight: FontWeight.w800,
                                    color: const Color(0xFF0F172A),
                                  ),
                                ),
                                const SizedBox(height: 2),
                                Text(
                                  'Fast replies build trust and increase orders',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 12.5,
                                    fontWeight: FontWeight.w600,
                                    color: const Color(0xFF64748B),
                                  ),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ],
                            ),
                          ),
                          const SizedBox(width: 12),
                          _buildHeaderAction(
                            icon: Icons.tune_rounded,
                            onTap: () {
                              HapticFeedback.selectionClick();
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('Filters coming soon'),
                                ),
                              );
                            },
                          ),
                          const SizedBox(width: 10),
                          _buildHeaderAction(
                            icon: Icons.edit_note_rounded,
                            onTap: () {
                              HapticFeedback.selectionClick();
                              ScaffoldMessenger.of(context).showSnackBar(
                                const SnackBar(
                                  content: Text('New message coming soon'),
                                ),
                              );
                            },
                          ),
                        ],
                      ),
                    ),
                  ),
                ).animate().fadeIn(duration: 350.ms).slideY(begin: 0.15, end: 0),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeaderAction({required IconData icon, required VoidCallback onTap}) {
    return Material(
      color: Colors.transparent,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(14),
        child: Container(
          width: 42,
          height: 42,
          decoration: BoxDecoration(
            color: Colors.black.withValues(alpha: 0.04),
            borderRadius: BorderRadius.circular(14),
            border: Border.all(color: Colors.white.withValues(alpha: 0.7)),
          ),
          child: Icon(
            icon,
            color: const Color(0xFF0F172A),
            size: 20,
          ),
        ),
      ),
    );
  }

  Widget _buildSearch() {
    return Container(
      height: 52,
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0), width: 1),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF64748B).withValues(alpha: 0.04),
            blurRadius: 16,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          const Icon(Icons.search_rounded, color: Color(0xFF94A3B8), size: 24),
          const SizedBox(width: 12),
          Expanded(
            child: TextField(
              controller: _searchController,
              style: GoogleFonts.plusJakartaSans(
                color: const Color(0xFF0F172A),
                fontWeight: FontWeight.w600,
                fontSize: 15,
              ),
              decoration: InputDecoration(
                hintText: 'Search clients or messages...',
                hintStyle: GoogleFonts.plusJakartaSans(
                  color: const Color(0xFF94A3B8),
                  fontWeight: FontWeight.w500,
                ),
                border: InputBorder.none,
                isDense: true,
                contentPadding: EdgeInsets.zero,
              ),
            ),
          ),
          if (_searchQuery.isNotEmpty)
            GestureDetector(
              onTap: () {
                _searchController.clear();
                FocusScope.of(context).unfocus();
              },
              child: Container(
                padding: const EdgeInsets.all(4),
                decoration: BoxDecoration(
                  color: const Color(0xFFF1F5F9),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.close_rounded, size: 16, color: Color(0xFF64748B)),
              ),
            ),
        ],
      ),
    ).animate().fadeIn(duration: 400.ms).slideY(begin: 0.1, end: 0);
  }

  Widget _buildFilters() {
    final filters = ['All', 'Unread', 'Orders'];
    return SizedBox(
      height: 38,
      child: ListView.separated(
        scrollDirection: Axis.horizontal,
        physics: const BouncingScrollPhysics(),
        itemCount: filters.length,
        separatorBuilder: (_, __) => const SizedBox(width: 10),
        itemBuilder: (context, index) {
          final isActive = _activeFilter == index;
          return GestureDetector(
            onTap: () {
              setState(() => _activeFilter = index);
              HapticFeedback.selectionClick();
            },
            child: AnimatedContainer(
              duration: 200.ms,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              decoration: BoxDecoration(
                color: isActive ? const Color(0xFF0F172A) : Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(
                  color: isActive ? const Color(0xFF0F172A) : const Color(0xFFE2E8F0),
                ),
                boxShadow: isActive
                    ? [
                        BoxShadow(
                          color: const Color(0xFF0F172A).withValues(alpha: 0.25),
                          blurRadius: 12,
                          offset: const Offset(0, 4),
                        ),
                      ]
                    : null,
              ),
              alignment: Alignment.center,
              child: Text(
                filters[index],
                style: GoogleFonts.plusJakartaSans(
                  color: isActive ? Colors.white : const Color(0xFF64748B),
                  fontWeight: FontWeight.w600,
                  fontSize: 13,
                ),
              ),
            ),
          );
        },
      ),
    ).animate().fadeIn(duration: 450.ms).slideX(begin: 0.05, end: 0);
  }

  Widget _buildEmptyState(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            width: 80,
            height: 80,
            decoration: BoxDecoration(
              color: const Color(0xFFF1F5F9),
              shape: BoxShape.circle,
              border: Border.all(color: Colors.white, width: 4),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.05),
                  blurRadius: 20,
                  offset: const Offset(0, 10),
                ),
              ],
            ),
            child: const Icon(Icons.mark_chat_unread_rounded,
                size: 32, color: Color(0xFF94A3B8)),
          ),
          const SizedBox(height: 24),
          Text(
            'No messages yet',
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF0F172A),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Your conversations with clients\nwill appear here.',
            textAlign: TextAlign.center,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: const Color(0xFF64748B),
              height: 1.5,
            ),
          ),
        ],
      ),
    ).animate().fadeIn(duration: 600.ms).scale();
  }

  Widget _buildThreadCard(BuildContext context, ChatConversationModel conversation, int index) {
    final otherUser = conversation.user;
    final lastMessageAt = conversation.lastMessageAt;
    final lastMessageContent = conversation.lastMessageContent;
    final unreadCount = conversation.unreadCount;
    
    // Generate a color based on name for fallback avatar
    final fallbackColor = Colors.indigo; // Simplified
    final isOnline = (otherUser.lastActive ?? '').toLowerCase() == 'online';

    return GestureDetector(
      onTap: () {
        if (otherUser.id != null) {
          context.push('/chat-details', extra: otherUser);
        }
      },
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: const Color(0xFFF1F5F9)),
          boxShadow: [
            BoxShadow(
              color: const Color(0xFF64748B).withValues(alpha: 0.04),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Row(
          children: [
            Stack(
              children: [
                Container(
                  width: 56,
                  height: 56,
                  decoration: BoxDecoration(
                    color: fallbackColor.withValues(alpha: 0.1),
                    shape: BoxShape.circle,
                    image: otherUser.image != null
                        ? DecorationImage(
                            image: NetworkImage(otherUser.image!),
                            fit: BoxFit.cover,
                          )
                        : null,
                  ),
                  alignment: Alignment.center,
                  child: otherUser.image == null
                      ? Text(
                          (otherUser.name ?? 'U')[0].toUpperCase(),
                          style: GoogleFonts.plusJakartaSans(
                            color: fallbackColor,
                            fontWeight: FontWeight.bold,
                            fontSize: 20,
                          ),
                        )
                      : null,
                ),
                if (isOnline)
                  Positioned(
                    right: 2,
                    bottom: 2,
                    child: Container(
                      width: 14,
                      height: 14,
                      decoration: BoxDecoration(
                        color: const Color(0xFF22C55E),
                        shape: BoxShape.circle,
                        border: Border.all(color: Colors.white, width: 2.5),
                      ),
                    ),
                  ),
              ],
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Expanded(
                        child: Text(
                          otherUser.name ?? 'Unknown',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: FontWeight.bold,
                            fontSize: 16,
                            color: const Color(0xFF0F172A),
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                      Text(
                        lastMessageAt != null ? _formatDate(lastMessageAt) : '',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.w600,
                          fontSize: 12,
                          color: unreadCount > 0
                              ? const Color(0xFF6366F1)
                              : const Color(0xFF94A3B8),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          lastMessageContent?.isNotEmpty == true ? lastMessageContent! : 'No messages yet',
                          style: GoogleFonts.plusJakartaSans(
                            fontWeight: unreadCount > 0
                                ? FontWeight.w600
                                : FontWeight.w500,
                            fontSize: 14,
                            color: unreadCount > 0
                                ? const Color(0xFF0F172A)
                                : const Color(0xFF64748B),
                          ),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                      if (unreadCount > 0)
                        Container(
                          margin: const EdgeInsets.only(left: 12),
                          padding: const EdgeInsets.symmetric(
                              horizontal: 10, vertical: 4),
                          decoration: BoxDecoration(
                            color: const Color(0xFF6366F1),
                            borderRadius: BorderRadius.circular(12),
                            boxShadow: [
                              BoxShadow(
                                color: const Color(0xFF6366F1).withValues(alpha: 0.3),
                                blurRadius: 8,
                                offset: const Offset(0, 3),
                              ),
                            ],
                          ),
                          child: Text(
                            unreadCount.toString(),
                            style: GoogleFonts.plusJakartaSans(
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                              fontSize: 11,
                            ),
                          ),
                        ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    ).animate().fadeIn(duration: 400.ms, delay: (50 * index).ms).slideY(begin: 0.1, end: 0);
  }

  String _formatDate(DateTime date) {
    final now = DateTime.now();
    if (date.day == now.day && date.month == now.month && date.year == now.year) {
      return DateFormat('HH:mm').format(date);
    }
    if (date.year == now.year) {
      return DateFormat('MMM d').format(date);
    }
    return DateFormat('yyyy/MM/dd').format(date);
  }
}
