import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:file_picker/file_picker.dart';
import 'package:go_router/go_router.dart';
import '../../data/models/gig_model.dart';
import '../../data/models/gig_faq_model.dart';
import '../../data/models/tag_model.dart';
import '../../../../services/data/models/category_model.dart';
import '../providers/gig_provider.dart';

class CreateGigPage extends ConsumerStatefulWidget {
  final GigModel? gig;
  const CreateGigPage({super.key, this.gig});

  @override
  ConsumerState<CreateGigPage> createState() => _CreateGigPageState();
}

class _CreateGigPageState extends ConsumerState<CreateGigPage> {
  int _currentStep = 0;
  final _formKey = GlobalKey<FormState>();

  // Form Data
  final TextEditingController _titleController = TextEditingController();
  final TextEditingController _descriptionController = TextEditingController();
  int? _selectedCategoryId;
  int? _selectedServiceTypeId;
  final List<String> _selectedTags = [];

  // Packages
  List<GigPackageModel> _packages = [
    GigPackageModel(
      tier: 'Basic',
      name: '',
      description: '',
      price: 0,
      deliveryDays: 1,
      revisions: 0,
      features: [],
    ),
    GigPackageModel(
      tier: 'Standard',
      name: '',
      description: '',
      price: 0,
      deliveryDays: 3,
      revisions: 1,
      features: [],
    ),
    GigPackageModel(
      tier: 'Premium',
      name: '',
      description: '',
      price: 0,
      deliveryDays: 5,
      revisions: 2,
      features: [],
    ),
  ];

  // Media
  final List<File> _images = [];
  final List<String> _existingImages = [];
  File? _thumbnail;
  String? _existingThumbnail;
  File? _video;
  final List<File> _documents = [];

  // FAQs
  final List<GigFaqModel> _faqs = [];

  // Extras
  List<GigExtraModel> _extras = [
    GigExtraModel(title: 'Extra Fast Delivery', price: 0, additionalDays: 0),
    GigExtraModel(title: 'Additional Revision', price: 0, additionalDays: 0),
    GigExtraModel(title: 'Source File', price: 0, additionalDays: 0),
    GigExtraModel(title: 'Commercial Use', price: 0, additionalDays: 0),
  ];

  // Metadata
  final Map<String, dynamic> _metadata = {
    'delivery_time': '1 Day',
    'revisions': 'Unlimited',
  };

  @override
  void initState() {
    super.initState();
    if (widget.gig != null) {
      _initGigData();
    }
  }

  void _initGigData() {
    final gig = widget.gig!;
    _titleController.text = gig.title;
    _descriptionController.text = gig.description;
    _selectedCategoryId = gig.categoryId;
    _selectedServiceTypeId = gig.serviceTypeId;
    _selectedTags.addAll(gig.tags);
    _existingThumbnail = gig.thumbnail;
    _existingImages.addAll(gig.images);
    _faqs.addAll(gig.faqs);
    _metadata.addAll(gig.metadata);

    // Map packages
    if (gig.packages.isNotEmpty) {
       // We try to match by tier or index. Assuming standard 3 packages.
       for (var pkg in gig.packages) {
         int index = _packages.indexWhere((p) => p.tier.toLowerCase() == pkg.tier.toLowerCase());
         if (index != -1) {
           _packages[index] = pkg;
         }
       }
    }

    // Map extras
    if (gig.extras.isNotEmpty) {
      // Clear default extras or merge?
      // Best to overwrite defaults if titles match, or add new ones?
      // The default list has specific items. If gig has them, update them.
      for (var extra in gig.extras) {
        int index = _extras.indexWhere((e) => e.title == extra.title);
        if (index != -1) {
          _extras[index] = extra;
        } else {
           _extras.add(extra);
        }
      }
    }
  }

  @override
  void dispose() {
    _titleController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _pickImages() async {
    final ImagePicker picker = ImagePicker();
    final List<XFile> images = await picker.pickMultiImage();
    if (images.isNotEmpty) {
      setState(() {
        _images.addAll(images.map((e) => File(e.path)));
      });
    }
  }

  Future<void> _pickThumbnail() async {
    final ImagePicker picker = ImagePicker();
    final XFile? image = await picker.pickImage(source: ImageSource.gallery);
    if (image != null) {
      setState(() {
        _thumbnail = File(image.path);
      });
    }
  }

  Future<void> _pickVideo() async {
    final ImagePicker picker = ImagePicker();
    final XFile? video = await picker.pickVideo(source: ImageSource.gallery);
    if (video != null) {
      setState(() {
        _video = File(video.path);
      });
    }
  }

  Future<void> _pickDocuments() async {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      allowMultiple: true,
      type: FileType.custom,
      allowedExtensions: ['pdf', 'doc', 'docx'],
    );

    if (result != null) {
      setState(() {
        _documents.addAll(result.paths.map((path) => File(path!)).toList());
      });
    }
  }

  void _submitGig() {
    if (_formKey.currentState!.validate()) {
      if (_images.isEmpty && _existingImages.isEmpty) {
        _showErrorSnackBar(context, 'Please add at least one image');
        return;
      }

      final gig = GigModel(
        id: widget.gig?.id,
        providerId: widget.gig?.providerId ?? 0,
        categoryId: _selectedCategoryId!,
        serviceTypeId: _selectedServiceTypeId,
        title: _titleController.text,
        slug: widget.gig?.slug ?? '',
        description: _descriptionController.text,
        tags: _selectedTags,
        packages: _packages,
        extras: _extras.where((e) => e.price > 0).toList(),
        metadata: _metadata,
        faqs: _faqs,
        status: widget.gig?.status ?? 'pending',
      );

      if (widget.gig != null) {
        ref
            .read(gigControllerProvider.notifier)
            .updateGig(
              id: widget.gig!.id!,
              gig: gig,
              thumbnail: _thumbnail,
              images: _images,
              video: _video,
              documents: _documents,
            );
      } else {
        ref
            .read(gigControllerProvider.notifier)
            .createGig(
              gig: gig,
              thumbnail: _thumbnail,
              images: _images,
              video: _video,
              documents: _documents,
            );
      }
    }
  }

  void _showSuccessSnackBar(BuildContext context) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              colors: [Color(0xFF10B981), Color(0xFF059669)], // Emerald Green
            ),
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF10B981).withOpacity(0.4),
                blurRadius: 12,
                offset: const Offset(0, 6),
              ),
            ],
          ),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  shape: BoxShape.circle,
                ),
                child: const Icon(
                  Icons.check_rounded,
                  color: Colors.white,
                  size: 20,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      'Success!',
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      widget.gig != null
                          ? 'Your gig has been updated successfully.'
                          : 'Your gig has been published successfully.',
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white.withOpacity(0.9),
                        fontSize: 14,
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
        behavior: SnackBarBehavior.floating,
        backgroundColor: Colors.transparent,
        elevation: 0,
        margin: const EdgeInsets.all(24),
        duration: const Duration(seconds: 2),
      ),
    );
  }

  void _showErrorSnackBar(BuildContext context, String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Container(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              colors: [Color(0xFFEF4444), Color(0xFFDC2626)], // Red
            ),
            borderRadius: BorderRadius.circular(16),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFFEF4444).withOpacity(0.4),
                blurRadius: 12,
                offset: const Offset(0, 6),
              ),
            ],
          ),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  shape: BoxShape.circle,
                ),
                child: const Icon(
                  Icons.error_outline_rounded,
                  color: Colors.white,
                  size: 20,
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Text(
                  message,
                  style: GoogleFonts.plusJakartaSans(
                    color: Colors.white,
                    fontWeight: FontWeight.w600,
                    fontSize: 14,
                  ),
                ),
              ),
            ],
          ),
        ),
        behavior: SnackBarBehavior.floating,
        backgroundColor: Colors.transparent,
        elevation: 0,
        margin: const EdgeInsets.all(24),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    ref.listen<AsyncValue<void>>(gigControllerProvider, (previous, next) {
      next.whenOrNull(
        data: (_) {
          if (previous?.isLoading == true) {
            _showSuccessSnackBar(context);
            // Delay popping to let the user see the success message
            Future.delayed(const Duration(seconds: 2), () {
              if (mounted) context.pop();
            });
          }
        },
        error: (error, stack) {
          _showErrorSnackBar(context, error.toString());
        },
      );
    });

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: Text(
          widget.gig != null ? 'Edit Gig' : 'Create New Gig',
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.w700,
            color: const Color(0xFF0F172A),
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Color(0xFF0F172A)),
          onPressed: () => context.pop(),
        ),
      ),
      body: Form(
        key: _formKey,
        child: Column(
          children: [
            _buildStepIndicator(),
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(24),
                child: Column(
                  children: [
                    if (_currentStep == 0) _buildOverviewStep(),
                    if (_currentStep == 1) _buildPricingStep(),
                    if (_currentStep == 2) _buildDescriptionStep(),
                    if (_currentStep == 3) _buildGalleryStep(),
                    if (_currentStep == 4) _buildFaqStep(),
                    const SizedBox(height: 32),
                  ],
                ),
              ),
            ),
            Container(
              padding: const EdgeInsets.all(24),
              color: const Color(0xFFF8FAFC),
              child: SafeArea(top: false, child: _buildBottomControls()),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStepIndicator() {
    final steps = ['Overview', 'Pricing', 'Desc.', 'Gallery', 'FAQ'];
    return Container(
      color: Colors.white,
      padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 24),
      child: Row(
        children: steps.asMap().entries.map((entry) {
          final index = entry.key;
          final title = entry.value;
          final isActive = index == _currentStep;
          final isCompleted = index < _currentStep;

          return Expanded(
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Container(
                        height: 2,
                        color: index == 0
                            ? Colors.transparent
                            : (index <= _currentStep
                                  ? const Color(0xFF6366F1)
                                  : Colors.grey[200]!),
                      ),
                    ),
                    Container(
                      width: 28,
                      height: 28,
                      decoration: BoxDecoration(
                        shape: BoxShape.circle,
                        color: isActive || isCompleted
                            ? const Color(0xFF6366F1)
                            : Colors.white,
                        border: Border.all(
                          color: isActive || isCompleted
                              ? const Color(0xFF6366F1)
                              : Colors.grey[300]!,
                        ),
                      ),
                      child: Center(
                        child: isCompleted
                            ? const Icon(
                                Icons.check,
                                size: 14,
                                color: Colors.white,
                              )
                            : Text(
                                '${index + 1}',
                                style: GoogleFonts.plusJakartaSans(
                                  color: isActive
                                      ? Colors.white
                                      : Colors.grey[500]!,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 12,
                                ),
                              ),
                      ),
                    ),
                    Expanded(
                      child: Container(
                        height: 2,
                        color: index == steps.length - 1
                            ? Colors.transparent
                            : (index < _currentStep
                                  ? const Color(0xFF6366F1)
                                  : Colors.grey[200]!),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Text(
                  title,
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 12,
                    color: isActive
                        ? const Color(0xFF1E293B)
                        : Colors.grey[500],
                    fontWeight: isActive ? FontWeight.bold : FontWeight.w500,
                  ),
                ),
              ],
            ),
          );
        }).toList(),
      ),
    );
  }

  Widget _buildBottomControls() {
    final gigState = ref.watch(gigControllerProvider);
    final isLoading = gigState.isLoading;

    return Row(
      children: [
        if (_currentStep > 0)
          Expanded(
            child: OutlinedButton(
              onPressed: isLoading
                  ? null
                  : () {
                      if (_currentStep > 0) {
                        setState(() => _currentStep -= 1);
                      }
                    },
              style: OutlinedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 16),
                side: const BorderSide(color: Color(0xFFE2E8F0)),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: Text(
                'Back',
                style: GoogleFonts.plusJakartaSans(
                  fontWeight: FontWeight.w600,
                  color: const Color(0xFF64748B),
                ),
              ),
            ),
          ),
        if (_currentStep > 0) const SizedBox(width: 16),
        Expanded(
          flex: 2,
          child: Container(
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(12),
              gradient: const LinearGradient(
                colors: [Color(0xFF6366F1), Color(0xFF8B5CF6)],
              ),
              boxShadow: [
                BoxShadow(
                  color: const Color(0xFF6366F1).withOpacity(0.3),
                  blurRadius: 8,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: ElevatedButton(
              onPressed: isLoading
                  ? null
                  : () {
                      if (_currentStep < 4) {
                        setState(() => _currentStep += 1);
                      } else {
                        _submitGig();
                      }
                    },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.transparent,
                shadowColor: Colors.transparent,
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: isLoading
                  ? const SizedBox(
                      height: 24,
                      width: 24,
                      child: CircularProgressIndicator(
                        color: Colors.white,
                        strokeWidth: 2.5,
                      ),
                    )
                  : Text(
                      _currentStep == 4
                          ? (widget.gig != null ? 'Update Gig' : 'Publish Gig')
                          : 'Continue',
                      style: GoogleFonts.plusJakartaSans(
                        fontWeight: FontWeight.w700,
                        color: Colors.white,
                      ),
                    ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildOverviewStep() {
    final categoriesAsync = ref.watch(categoriesProvider);
    final serviceTypesAsync = ref.watch(serviceTypesProvider);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _buildSectionTitle('Gig Title', 'Keep it short and catchy'),
        TextFormField(
          controller: _titleController,
          style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.w500),
          decoration: _inputDecoration('I will do...', Icons.title),
          validator: (value) => value!.isEmpty ? 'Please enter a title' : null,
        ),
        const SizedBox(height: 24),

        _buildSectionTitle('Category', 'Select the category that fits best'),
        categoriesAsync.when(
          data: (categories) => DropdownButtonFormField<int>(
            isExpanded: true,
            decoration: _inputDecoration(
              'Select Category',
              Icons.category_outlined,
            ),
            value: _selectedCategoryId,
            items: categories
                .map(
                  (c) => DropdownMenuItem(
                    value: c.id,
                    child: Text(
                      c.name,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                )
                .toList(),
            onChanged: (val) => setState(() => _selectedCategoryId = val),
            validator: (val) => val == null ? 'Required' : null,
          ),
          loading: () => const LinearProgressIndicator(),
          error: (_, __) => const Text('Failed to load categories'),
        ),
        const SizedBox(height: 24),

        _buildSectionTitle('Service Type', 'What type of service is this?'),
        serviceTypesAsync.when(
          data: (types) => DropdownButtonFormField<int>(
            isExpanded: true,
            decoration: _inputDecoration(
              'Select Service Type',
              Icons.work_outline,
            ),
            value: _selectedServiceTypeId,
            items: types
                .map(
                  (t) => DropdownMenuItem(
                    value: t.id,
                    child: Text(
                      t.name,
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                )
                .toList(),
            onChanged: (val) => setState(() => _selectedServiceTypeId = val),
          ),
          loading: () => const SizedBox(),
          error: (_, __) => const SizedBox(),
        ),
        const SizedBox(height: 24),

        _buildSectionTitle('Search Tags', 'Help buyers find your gig'),
        _buildTagInput(),
      ],
    ).animate().fadeIn().slideX(begin: 0.1);
  }

  Widget _buildTagInput() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Autocomplete<TagModel>(
          optionsBuilder: (TextEditingValue textEditingValue) async {
            if (textEditingValue.text.isEmpty) {
              return const Iterable<TagModel>.empty();
            }
            final repository = ref.read(gigRepositoryProvider);
            return await repository.getTags(query: textEditingValue.text);
          },
          displayStringForOption: (TagModel option) => option.name,
          onSelected: (TagModel selection) {
            if (!_selectedTags.contains(selection.name)) {
              setState(() {
                _selectedTags.add(selection.name);
              });
            }
          },
          fieldViewBuilder: (context, controller, focusNode, onFieldSubmitted) {
            return TextFormField(
              controller: controller,
              focusNode: focusNode,
              onFieldSubmitted: (value) {
                if (value.isNotEmpty && !_selectedTags.contains(value)) {
                  setState(() {
                    _selectedTags.add(value);
                    controller.clear();
                  });
                }
              },
              decoration: _inputDecoration('Type to search tags...', Icons.tag),
            );
          },
          optionsViewBuilder: (context, onSelected, options) {
            return Align(
              alignment: Alignment.topLeft,
              child: Material(
                elevation: 4,
                borderRadius: BorderRadius.circular(12),
                child: Container(
                  width: MediaQuery.of(context).size.width - 64, // Adjust width
                  constraints: const BoxConstraints(maxHeight: 200),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: ListView.builder(
                    padding: EdgeInsets.zero,
                    shrinkWrap: true,
                    itemCount: options.length,
                    itemBuilder: (BuildContext context, int index) {
                      final TagModel option = options.elementAt(index);
                      return ListTile(
                        title: Text(
                          option.name,
                          style: GoogleFonts.plusJakartaSans(),
                        ),
                        onTap: () {
                          onSelected(option);
                        },
                      );
                    },
                  ),
                ),
              ),
            );
          },
        ),
        const SizedBox(height: 12),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: _selectedTags
              .map(
                (tag) => Chip(
                  label: Text(tag),
                  backgroundColor: const Color(0xFFEEF2FF),
                  labelStyle: GoogleFonts.plusJakartaSans(
                    color: const Color(0xFF6366F1),
                    fontWeight: FontWeight.w600,
                  ),
                  deleteIcon: const Icon(
                    Icons.close,
                    size: 16,
                    color: Color(0xFF6366F1),
                  ),
                  onDeleted: () => setState(() => _selectedTags.remove(tag)),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                    side: const BorderSide(color: Color(0xFFE0E7FF)),
                  ),
                ),
              )
              .toList(),
        ),
      ],
    );
  }

  Widget _buildPricingStep() {
    return Column(
      children: [
        _buildSectionTitle('Packages', 'Define your pricing tiers'),
        ..._packages.map((package) {
          final index = _packages.indexOf(package);
          return Container(
            margin: const EdgeInsets.only(bottom: 16),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  package.tier,
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                    color: const Color(0xFF1E293B),
                  ),
                ),
                const SizedBox(height: 12),
                TextFormField(
                  initialValue: package.name,
                  onChanged: (v) => setState(() {
                    _packages[index] = GigPackageModel(
                      tier: package.tier,
                      name: v,
                      description: package.description,
                      price: package.price,
                      deliveryDays: package.deliveryDays,
                      revisions: package.revisions,
                      features: package.features,
                      id: package.id,
                    );
                  }),
                  decoration: _inputDecoration('Package Name', Icons.label_outline),
                ),
                const SizedBox(height: 12),
                TextFormField(
                  initialValue: package.description,
                  maxLines: 3,
                  onChanged: (v) => setState(() {
                    _packages[index] = GigPackageModel(
                      tier: package.tier,
                      name: package.name,
                      description: v,
                      price: package.price,
                      deliveryDays: package.deliveryDays,
                      revisions: package.revisions,
                      features: package.features,
                      id: package.id,
                    );
                  }),
                  decoration: _inputDecoration('What is included?', Icons.description_outlined),
                ),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: DropdownButtonFormField<int>(
                        value: package.deliveryDays,
                        decoration: _inputDecoration('Delivery', Icons.schedule),
                        items: [1, 2, 3, 4, 5, 6, 7, 10, 14, 21, 30]
                            .map((days) => DropdownMenuItem(
                                  value: days,
                                  child: Text('$days Days'),
                                ))
                            .toList(),
                        onChanged: (v) {
                          if (v != null) {
                            setState(() {
                              _packages[index] = GigPackageModel(
                                tier: package.tier,
                                name: package.name,
                                description: package.description,
                                price: package.price,
                                deliveryDays: v,
                                revisions: package.revisions,
                                features: package.features,
                                id: package.id,
                              );
                            });
                          }
                        },
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: DropdownButtonFormField<int>(
                        value: package.revisions,
                        decoration: _inputDecoration('Revisions', Icons.repeat),
                        items: List.generate(11, (i) => i)
                            .map((r) => DropdownMenuItem(
                                  value: r,
                                  child: Text(r == 0 ? 'No Rev.' : '$r Rev.'),
                                ))
                            .toList(),
                        onChanged: (v) {
                          if (v != null) {
                            setState(() {
                              _packages[index] = GigPackageModel(
                                tier: package.tier,
                                name: package.name,
                                description: package.description,
                                price: package.price,
                                deliveryDays: package.deliveryDays,
                                revisions: v,
                                features: package.features,
                                id: package.id,
                              );
                            });
                          }
                        },
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                TextFormField(
                  initialValue: package.price == 0 ? '' : package.price.toString(),
                  keyboardType: TextInputType.number,
                  onChanged: (v) => setState(() {
                    _packages[index] = GigPackageModel(
                      tier: package.tier,
                      name: package.name,
                      description: package.description,
                      price: double.tryParse(v) ?? 0,
                      deliveryDays: package.deliveryDays,
                      revisions: package.revisions,
                      features: package.features,
                      id: package.id,
                    );
                  }),
                  decoration: _inputDecoration('Price (\$)', Icons.attach_money),
                ),
              ],
            ),
          );
        }).toList(),

        const SizedBox(height: 24),
        _buildSectionTitle('Upgrade Order', 'Add optional extras'),
        ..._extras.map((extra) {
          final index = _extras.indexOf(extra);
          return Container(
            margin: const EdgeInsets.only(bottom: 12),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Row(
              children: [
                Expanded(
                  flex: 2,
                  child: Text(
                    extra.title,
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.w600,
                      color: const Color(0xFF1E293B),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: TextFormField(
                    initialValue: extra.price == 0 ? '' : extra.price.toString(),
                    keyboardType: TextInputType.number,
                    onChanged: (v) {
                      setState(() {
                        _extras[index] = GigExtraModel(
                          id: extra.id,
                          title: extra.title,
                          description: extra.description,
                          price: double.tryParse(v) ?? 0,
                          additionalDays: extra.additionalDays,
                        );
                      });
                    },
                    decoration: _inputDecoration('Price', Icons.attach_money).copyWith(
                      contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 12),
                    ),
                  ),
                ),
              ],
            ),
          );
        }).toList(),
      ],
    ).animate().fadeIn().slideX(begin: 0.1);
  }

  Widget _buildDescriptionStep() {
    return Column(
      children: [
        _buildSectionTitle('Description', 'Describe your gig in detail'),
        TextFormField(
          controller: _descriptionController,
          maxLines: 8,
          decoration: _inputDecoration(
            'Describe what you offer...',
            Icons.description_outlined,
          ),
          validator: (value) =>
              value!.isEmpty ? 'Description is required' : null,
        ),
      ],
    ).animate().fadeIn().slideX(begin: 0.1);
  }

  Widget _buildGalleryStep() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        _buildSectionTitle('Gallery', 'Showcase your work'),

        // Thumbnail
        Text(
          'Thumbnail Image',
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.w600,
            fontSize: 14,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 8),
        Container(
          width: double.infinity,
          height: 200,
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(
              color: const Color(0xFFE2E8F0),
              style: BorderStyle.solid,
            ),
          ),
          child: InkWell(
            onTap: _pickThumbnail,
            borderRadius: BorderRadius.circular(16),
            child: _thumbnail != null
                ? ClipRRect(
                    borderRadius: BorderRadius.circular(16),
                    child: Image.file(
                      _thumbnail!,
                      fit: BoxFit.cover,
                      width: double.infinity,
                      height: double.infinity,
                    ),
                  )
                : _existingThumbnail != null
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(16),
                        child: Image.network(
                          _existingThumbnail!,
                          fit: BoxFit.cover,
                          width: double.infinity,
                          height: double.infinity,
                          errorBuilder: (context, error, stackTrace) {
                            return const Center(
                              child: Icon(Icons.broken_image, color: Colors.grey),
                            );
                          },
                        ),
                      )
                    : Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(
                            Icons.add_photo_alternate_outlined,
                            size: 48,
                            color: Color(0xFF6366F1),
                          ),
                          const SizedBox(height: 12),
                          Text(
                            'Add Thumbnail',
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.w600,
                              color: const Color(0xFF6366F1),
                            ),
                          ),
                        ],
                      ),
          ),
        ),
        const SizedBox(height: 24),

        // Images
        Text(
          'Gig Images',
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.w600,
            fontSize: 14,
            color: const Color(0xFF1E293B),
          ),
        ),
        const SizedBox(height: 8),
        Container(
          width: double.infinity,
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(16),
            border: Border.all(
              color: const Color(0xFFE2E8F0),
              style: BorderStyle.solid,
            ),
          ),
          child: Column(
            children: [
              const Icon(
                Icons.cloud_upload_outlined,
                size: 48,
                color: Color(0xFF6366F1),
              ),
              const SizedBox(height: 12),
              TextButton(
                onPressed: _pickImages,
                child: Text(
                  'Upload Images',
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
              const SizedBox(height: 12),
              if (_existingImages.isNotEmpty)
                Padding(
                  padding: const EdgeInsets.only(bottom: 8),
                  child: Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: _existingImages
                        .map(
                          (url) => Stack(
                        children: [
                          ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: Image.network(
                              url,
                              width: 80,
                              height: 80,
                              fit: BoxFit.cover,
                              errorBuilder: (context, error, stackTrace) {
                                return Container(
                                  width: 80,
                                  height: 80,
                                  color: Colors.grey[200],
                                  child: const Icon(Icons.broken_image),
                                );
                              },
                            ),
                          ),
                          Positioned(
                            right: 0,
                            top: 0,
                            child: GestureDetector(
                              onTap: () => setState(() => _existingImages.remove(url)),
                              child: Container(
                                color: Colors.black54,
                                child: const Icon(
                                  Icons.close,
                                  color: Colors.white,
                                  size: 16,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    )
                        .toList(),
                  ),
                ),
              if (_images.isNotEmpty)
                Wrap(
                  spacing: 8,
                  runSpacing: 8,
                  children: _images
                      .map(
                        (f) => Stack(
                          children: [
                            ClipRRect(
                              borderRadius: BorderRadius.circular(8),
                              child: Image.file(
                                f,
                                width: 80,
                                height: 80,
                                fit: BoxFit.cover,
                              ),
                            ),
                            Positioned(
                              right: 0,
                              top: 0,
                              child: GestureDetector(
                                onTap: () => setState(() => _images.remove(f)),
                                child: Container(
                                  color: Colors.black54,
                                  child: const Icon(
                                    Icons.close,
                                    color: Colors.white,
                                    size: 16,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      )
                      .toList(),
                ),
            ],
          ),
        ),
      ],
    ).animate().fadeIn().slideX(begin: 0.1);
  }

  Widget _buildFaqStep() {
    return Column(
      children: [
        _buildSectionTitle(
          'Frequently Asked Questions',
          'Add questions and answers for your buyers',
        ),

        // List of FAQs
        if (_faqs.isNotEmpty)
          ListView.separated(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: _faqs.length,
            separatorBuilder: (context, index) => const SizedBox(height: 16),
            itemBuilder: (context, index) {
              final faq = _faqs[index];
              return Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Expanded(
                          child: Text(
                            faq.question,
                            style: GoogleFonts.plusJakartaSans(
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                              color: const Color(0xFF1E293B),
                            ),
                          ),
                        ),
                        Row(
                          children: [
                            IconButton(
                              icon: const Icon(
                                Icons.edit_outlined,
                                size: 20,
                                color: Color(0xFF64748B),
                              ),
                              onPressed: () =>
                                  _showFaqDialog(faq: faq, index: index),
                            ),
                            IconButton(
                              icon: const Icon(
                                Icons.delete_outline,
                                size: 20,
                                color: Color(0xFFEF4444),
                              ),
                              onPressed: () =>
                                  setState(() => _faqs.removeAt(index)),
                            ),
                          ],
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text(
                      faq.answer,
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 14,
                        color: const Color(0xFF64748B),
                        height: 1.5,
                      ),
                    ),
                  ],
                ),
              );
            },
          ),

        if (_faqs.isNotEmpty) const SizedBox(height: 24),

        // Add Button
        InkWell(
          onTap: () => _showFaqDialog(),
          borderRadius: BorderRadius.circular(16),
          child: Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(vertical: 16),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(16),
              border: Border.all(
                color: const Color(0xFF6366F1),
                style: BorderStyle.solid,
              ),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.add_circle_outline, color: Color(0xFF6366F1)),
                const SizedBox(width: 8),
                Text(
                  'Add FAQ',
                  style: GoogleFonts.plusJakartaSans(
                    fontWeight: FontWeight.w600,
                    color: const Color(0xFF6366F1),
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    ).animate().fadeIn().slideX(begin: 0.1);
  }

  void _showFaqDialog({GigFaqModel? faq, int? index}) {
    final questionController = TextEditingController(text: faq?.question);
    final answerController = TextEditingController(text: faq?.answer);

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text(
          faq == null ? 'Add FAQ' : 'Edit FAQ',
          style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold),
        ),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextFormField(
              controller: questionController,
              decoration: _inputDecoration('Question', Icons.help_outline),
              maxLines: 2,
            ),
            const SizedBox(height: 16),
            TextFormField(
              controller: answerController,
              decoration: _inputDecoration('Answer', Icons.chat_bubble_outline),
              maxLines: 4,
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text(
              'Cancel',
              style: GoogleFonts.plusJakartaSans(color: Colors.grey),
            ),
          ),
          ElevatedButton(
            onPressed: () {
              if (questionController.text.isNotEmpty &&
                  answerController.text.isNotEmpty) {
                final newFaq = GigFaqModel(
                  question: questionController.text,
                  answer: answerController.text,
                );
                setState(() {
                  if (index != null) {
                    _faqs[index] = newFaq;
                  } else {
                    _faqs.add(newFaq);
                  }
                });
                Navigator.pop(context);
              }
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF6366F1),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
            child: Text(
              'Save',
              style: GoogleFonts.plusJakartaSans(color: Colors.white),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title, String subtitle) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: const Color(0xFF1E293B),
            ),
          ),
          const SizedBox(height: 4),
          Text(
            subtitle,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 14,
              color: const Color(0xFF64748B),
            ),
          ),
        ],
      ),
    );
  }

  InputDecoration _inputDecoration(String hint, IconData icon) {
    return InputDecoration(
      hintText: hint,
      prefixIcon: Icon(icon, color: const Color(0xFF94A3B8)),
      filled: true,
      fillColor: Colors.white,
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFF6366F1), width: 2),
      ),
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
    );
  }
}
