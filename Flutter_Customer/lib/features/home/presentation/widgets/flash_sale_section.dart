import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:google_fonts/google_fonts.dart';
import 'dart:async';
import '../pages/flash_sale_page.dart';

class FlashSaleSection extends StatefulWidget {
  final Map<String, dynamic> data;

  const FlashSaleSection({Key? key, required this.data}) : super(key: key);

  @override
  State<FlashSaleSection> createState() => _FlashSaleSectionState();
}

class _FlashSaleSectionState extends State<FlashSaleSection> {
  late Timer _timer;
  late Duration _timeLeft;

  @override
  void initState() {
    super.initState();
    DateTime end;
    final endTimeData = widget.data['end_time'];

    if (endTimeData != null && endTimeData is String) {
      try {
        end = DateTime.parse(endTimeData);
      } catch (e) {
        end = DateTime.now().add(const Duration(hours: 12));
      }
    } else if (endTimeData != null && endTimeData is int) {
      end = DateTime.fromMillisecondsSinceEpoch(endTimeData);
    } else {
      end = DateTime.now().add(const Duration(hours: 12));
    }
    
    _timeLeft = end.difference(DateTime.now());

    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      setState(() {
        _timeLeft = end.difference(DateTime.now());
        if (_timeLeft.isNegative) {
          _timer.cancel();
        }
      });
    });
  }

  @override
  void dispose() {
    _timer.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (_timeLeft.isNegative) return const SizedBox.shrink();

    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => FlashSalePage(data: widget.data),
          ),
        );
      },
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 24),
        child: Container(
          height: 180,
          width: double.infinity,
          clipBehavior: Clip.antiAlias,
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(24),
            color: Colors.black,
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF6366F1).withOpacity(0.3),
                blurRadius: 20,
                offset: const Offset(0, 10),
              ),
            ],
          ),
          child: Stack(
            children: [
              // Background Image
              if (widget.data['image'] != null)
                Positioned.fill(
                  child: CachedNetworkImage(
                    imageUrl: widget.data['image'],
                    fit: BoxFit.cover,
                    color: Colors.black.withOpacity(0.6),
                    colorBlendMode: BlendMode.darken,
                  ),
                ),

              // Content
              Padding(
                padding: const EdgeInsets.all(20), // Reduced padding slightly
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: const Color(0xFFFF4757),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        widget.data['discount'] ?? 'FLASH SALE',
                        style: GoogleFonts.plusJakartaSans(
                          color: Colors.white,
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            widget.data['title'] ?? 'Limited Time Offer',
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.plusJakartaSans(
                              color: Colors.white,
                              fontSize: 22, // Slightly reduced font size
                              fontWeight: FontWeight.w800,
                              height: 1.1,
                            ),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            widget.data['subtitle'] ?? 'Don\'t miss out!',
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                            style: GoogleFonts.plusJakartaSans(
                              color: Colors.white.withOpacity(0.8),
                              fontSize: 14,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    ),
                    // Timer Row
                    Row(
                      children: [
                        if (_timeLeft.inDays > 0)
                          _buildTimeBox(_timeLeft.inDays, 'Day')
                        else
                          _buildTimeBox(_timeLeft.inHours, 'Hrs'),
                          
                        const SizedBox(width: 8),
                        Text(':', style: GoogleFonts.plusJakartaSans(color: Colors.white, fontWeight: FontWeight.bold)),
                        const SizedBox(width: 8),
                        
                        _buildTimeBox(_timeLeft.inMinutes.remainder(60), 'Min'),
                        const SizedBox(width: 8),
                        Text(':', style: GoogleFonts.plusJakartaSans(color: Colors.white, fontWeight: FontWeight.bold)),
                        const SizedBox(width: 8),
                        
                        _buildTimeBox(_timeLeft.inSeconds.remainder(60), 'Sec'),
                        const Spacer(),
                        ElevatedButton(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => FlashSalePage(data: widget.data),
                              ),
                            );
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.white,
                            foregroundColor: Colors.black,
                            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                          ),
                          child: Text(
                            'Shop Now',
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.bold,
                              fontSize: 12,
                            ),
                          ),
                        )
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTimeBox(int value, String label) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          decoration: BoxDecoration(
            color: Colors.white.withOpacity(0.1),
            borderRadius: BorderRadius.circular(8),
            border: Border.all(color: Colors.white.withOpacity(0.2)),
          ),
          child: Text(
            value.toString().padLeft(2, '0'),
            style: GoogleFonts.plusJakartaSans(
              color: Colors.white,
              fontWeight: FontWeight.bold,
              fontSize: 14,
            ),
          ),
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            color: Colors.white.withOpacity(0.8),
            fontSize: 10,
            fontWeight: FontWeight.w500,
          ),
        ),
      ],
    );
  }
}
