import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';

class AIOptionsSheet extends StatefulWidget {
  final Function(String) onOptionSelected;
  final String currentText;
  final bool isCustomer;

  const AIOptionsSheet({
    super.key,
    required this.onOptionSelected,
    required this.currentText,
    required this.isCustomer,
  });

  @override
  State<AIOptionsSheet> createState() => _AIOptionsSheetState();
}

class _AIOptionsSheetState extends State<AIOptionsSheet> {
  bool _isGenerating = false;
  String? _generatedText;
  String _selectedOption = '';

  final List<Map<String, dynamic>> _customerOptions = [
    {
      'id': 'inquiry',
      'title': 'Draft Service Inquiry',
      'icon': Icons.edit_note_rounded,
      'color': Colors.blue,
      'prompt': "Hi, I'm interested in your service. Could you please provide a quote and your earliest availability?",
    },
    {
      'id': 'negotiate',
      'title': 'Negotiate Price',
      'icon': Icons.currency_exchange_rounded,
      'color': Colors.orange,
      'prompt': "Thanks for the quote. Is there any flexibility in the pricing if I book multiple sessions?",
    },
    {
      'id': 'followup',
      'title': 'Follow Up',
      'icon': Icons.access_time_filled_rounded,
      'color': Colors.purple,
      'prompt': "Just following up on my previous message. Let me know if you're available.",
    },
    {
      'id': 'polish',
      'title': 'Make Professional',
      'icon': Icons.auto_awesome_rounded,
      'color': Colors.indigo,
      'prompt': "Dynamic", // Handles current text
    },
  ];

  final List<Map<String, dynamic>> _providerOptions = [
    {
      'id': 'proposal',
      'title': 'Draft Proposal',
      'icon': Icons.description_rounded,
      'color': Colors.blue,
      'prompt': "Thank you for your inquiry. I can certainly help with that. My rate is \$X/hr and I am available starting tomorrow.",
    },
    {
      'id': 'accept',
      'title': 'Accept & Schedule',
      'icon': Icons.check_circle_rounded,
      'color': Colors.green,
      'prompt': "That sounds great! I've marked you down for that time. Looking forward to it.",
    },
    {
      'id': 'unavailable',
      'title': 'Decline Politely',
      'icon': Icons.event_busy_rounded,
      'color': Colors.red,
      'prompt': "Thank you for reaching out. Unfortunately, I am fully booked at that time. Would another time work for you?",
    },
    {
      'id': 'polish',
      'title': 'Make Professional',
      'icon': Icons.auto_awesome_rounded,
      'color': Colors.indigo,
      'prompt': "Dynamic",
    },
  ];

  Future<void> _generateResponse(String optionId, String prompt) async {
    setState(() {
      _isGenerating = true;
      _selectedOption = optionId;
      _generatedText = null;
    });

    // Simulate AI delay
    await Future.delayed(const Duration(seconds: 2));

    String result = prompt;
    if (optionId == 'polish') {
      if (widget.currentText.isEmpty) {
        result = "Please type something first to polish.";
      } else {
        // Mock polishing logic
        result = "Dear Service Provider, ${widget.currentText} Looking forward to your response.";
      }
    }

    if (mounted) {
      setState(() {
        _isGenerating = false;
        _generatedText = result;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final options = widget.isCustomer ? _customerOptions : _providerOptions;

    return Container(
      padding: const EdgeInsets.all(24),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
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
                child: const Icon(Icons.auto_awesome, color: Colors.indigo),
              ),
              const SizedBox(width: 12),
              Text(
                "AI Assistant",
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const Spacer(),
              IconButton(
                icon: const Icon(Icons.close),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),
          const SizedBox(height: 24),
          if (_isGenerating)
            Center(
              child: Column(
                children: [
                  const SizedBox(height: 20),
                  const CircularProgressIndicator(color: Colors.indigo),
                  const SizedBox(height: 16),
                  Text(
                    "AI is thinking...",
                    style: GoogleFonts.plusJakartaSans(
                      color: Colors.grey[600],
                      fontWeight: FontWeight.w500,
                    ),
                  ).animate(onPlay: (controller) => controller.repeat())
                   .shimmer(duration: 1200.ms, color: Colors.indigo),
                  const SizedBox(height: 20),
                ],
              ),
            )
          else if (_generatedText != null)
            Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.grey[50],
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.grey[200]!),
                  ),
                  child: Text(
                    _generatedText!,
                    style: GoogleFonts.plusJakartaSans(fontSize: 15, height: 1.5),
                  ),
                ),
                const SizedBox(height: 20),
                Row(
                  children: [
                    Expanded(
                      child: OutlinedButton(
                        onPressed: () {
                          setState(() {
                            _generatedText = null;
                          });
                        },
                        style: OutlinedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                          side: BorderSide(color: Colors.grey[300]!),
                        ),
                        child: const Text("Try Again", style: TextStyle(color: Colors.black)),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: ElevatedButton(
                        onPressed: () {
                          widget.onOptionSelected(_generatedText!);
                          Navigator.pop(context);
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: const Color(0xFF6366F1),
                          padding: const EdgeInsets.symmetric(vertical: 16),
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                        ),
                        child: const Text("Use This", style: TextStyle(color: Colors.white)),
                      ),
                    ),
                  ],
                ),
              ],
            )
          else
            Column(
              children: options.map((option) {
                return Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: InkWell(
                    onTap: () => _generateResponse(option['id'], option['prompt']),
                    borderRadius: BorderRadius.circular(16),
                    child: Container(
                      padding: const EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        border: Border.all(color: Colors.grey[200]!),
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: Row(
                        children: [
                          Container(
                            padding: const EdgeInsets.all(10),
                            decoration: BoxDecoration(
                              color: option['color'].withOpacity(0.1),
                              shape: BoxShape.circle,
                            ),
                            child: Icon(option['icon'], color: option['color'], size: 24),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  option['title'],
                                  style: GoogleFonts.plusJakartaSans(
                                    fontWeight: FontWeight.w600,
                                    fontSize: 16,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  _getSubtitle(option['id']),
                                  style: GoogleFonts.plusJakartaSans(
                                    color: Colors.grey[500],
                                    fontSize: 12,
                                  ),
                                ),
                              ],
                            ),
                          ),
                          const Icon(Icons.arrow_forward_ios_rounded, size: 16, color: Colors.grey),
                        ],
                      ),
                    ),
                  ),
                ).animate().fadeIn(duration: 400.ms).slideX(begin: 0.1, end: 0);
              }).toList(),
            ),
          const SizedBox(height: 20),
        ],
      ),
    );
  }

  String _getSubtitle(String id) {
    switch (id) {
      case 'polish': return "Improve grammar & tone";
      case 'inquiry': return "Ask about availability & price";
      case 'negotiate': return "Ask for a better price";
      case 'proposal': return "Send a professional quote";
      case 'accept': return "Confirm booking details";
      default: return "AI generated response";
    }
  }
}
