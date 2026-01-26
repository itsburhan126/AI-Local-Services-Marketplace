import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../auth/data/models/user_model.dart';

class ChatUserProfilePage extends StatelessWidget {
  final UserModel user;

  const ChatUserProfilePage({super.key, required this.user});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F6F8),
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 350,
            pinned: true,
            backgroundColor: Colors.white,
            elevation: 0,
            leading: IconButton(
              icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.black),
              onPressed: () => Navigator.pop(context),
            ),
            flexibleSpace: FlexibleSpaceBar(
              background: user.profileImage != null
                  ? CachedNetworkImage(
                      imageUrl: user.profileImage!,
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(color: Colors.grey[200]),
                      errorWidget: (context, url, error) => Container(
                        color: Colors.grey[200],
                        child: const Icon(Icons.person, size: 80, color: Colors.grey),
                      ),
                    )
                  : Container(
                      color: Colors.grey[200],
                      child: Center(
                        child: Text(
                          user.name?[0].toUpperCase() ?? 'U',
                          style: const TextStyle(fontSize: 80, fontWeight: FontWeight.bold, color: Colors.grey),
                        ),
                      ),
                    ),
            ),
          ),
          SliverToBoxAdapter(
            child: Column(
              children: [
                const SizedBox(height: 12),
                Container(
                  color: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
                  width: double.infinity,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        user.name ?? 'User',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 24,
                          fontWeight: FontWeight.w600,
                          color: Colors.black,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        user.email ?? 'No email info',
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 16,
                          color: Colors.grey[600],
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 12),
                
                // Encryption note
                Container(
                  color: Colors.white,
                  padding: const EdgeInsets.all(16),
                  child: Row(
                    children: [
                      const Icon(Icons.lock_outline_rounded, size: 20, color: Colors.grey),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              "Encryption",
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 16,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                            Text(
                              "Messages and calls are end-to-end encrypted.",
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 14,
                                color: Colors.grey[600],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 12),

                // Actions
                _buildActionTile(
                  icon: Icons.block_rounded,
                  text: "Block ${user.name ?? 'User'}",
                  color: Colors.redAccent,
                  onTap: () {
                    // TODO: Implement block from here if needed, or just show info
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text("Use the menu in chat to block user")),
                    );
                  },
                ),
                _buildActionTile(
                  icon: Icons.thumb_down_alt_outlined,
                  text: "Report ${user.name ?? 'User'}",
                  color: Colors.redAccent,
                  onTap: () {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text("Use the menu in chat to report user")),
                    );
                  },
                ),
                
                const SizedBox(height: 40),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildActionTile({
    required IconData icon,
    required String text,
    required Color color,
    required VoidCallback onTap,
  }) {
    return Container(
      color: Colors.white,
      margin: const EdgeInsets.only(bottom: 1),
      child: ListTile(
        onTap: onTap,
        leading: Icon(icon, color: color),
        title: Text(
          text,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 16,
            color: color,
            fontWeight: FontWeight.w500,
          ),
        ),
      ),
    );
  }
}
