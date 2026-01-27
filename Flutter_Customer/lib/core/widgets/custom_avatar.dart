import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';

class CustomAvatar extends StatelessWidget {
  final String? imageUrl;
  final String? name;
  final double size;
  final double? fontSize;
  final Color? backgroundColor;
  final Color? textColor;

  const CustomAvatar({
    super.key,
    this.imageUrl,
    this.name,
    this.size = 40,
    this.fontSize,
    this.backgroundColor,
    this.textColor,
  });

  @override
  Widget build(BuildContext context) {
    // 1. Try to use provided image URL
    if (imageUrl != null && imageUrl!.isNotEmpty) {
      return ClipOval(
        child: CachedNetworkImage(
          imageUrl: imageUrl!,
          width: size,
          height: size,
          fit: BoxFit.cover,
          placeholder: (context, url) => _buildPlaceholder(),
          errorWidget: (context, url, error) => _buildFallbackAvatar(),
        ),
      );
    }

    // 2. Fallback to name-based avatar
    return _buildFallbackAvatar();
  }

  Widget _buildPlaceholder() {
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        color: Colors.grey[200],
        shape: BoxShape.circle,
      ),
    );
  }

  Widget _buildFallbackAvatar() {
    final displayName = (name != null && name!.isNotEmpty) ? name! : 'Anonymous';
    
    // Use UI Avatars API for consistent look with initials and random background
    final fallbackUrl = 'https://ui-avatars.com/api/?name=${Uri.encodeComponent(displayName)}&background=random&size=${size.toInt() * 2}';

    return ClipOval(
      child: CachedNetworkImage(
        imageUrl: fallbackUrl,
        width: size,
        height: size,
        fit: BoxFit.cover,
        placeholder: (context, url) => _buildPlaceholder(),
        errorWidget: (context, url, error) => Container(
           width: size,
           height: size,
           alignment: Alignment.center,
           decoration: BoxDecoration(
             color: backgroundColor ?? Colors.blue,
             shape: BoxShape.circle,
           ),
           child: Text(
             displayName.isNotEmpty ? displayName[0].toUpperCase() : 'A',
             style: TextStyle(
               color: textColor ?? Colors.white,
               fontWeight: FontWeight.bold,
               fontSize: fontSize ?? (size * 0.4),
             ),
           ),
        ),
      ),
    );
  }
}
