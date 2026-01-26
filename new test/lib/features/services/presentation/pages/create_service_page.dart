import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../data/models/category_model.dart';
import '../providers/service_provider.dart';
import '../../../auth/presentation/providers/auth_provider.dart';

class CreateServicePage extends StatefulWidget {
  const CreateServicePage({super.key});

  @override
  State<CreateServicePage> createState() => _CreateServicePageState();
}

class _CreateServicePageState extends State<CreateServicePage> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _descriptionController = TextEditingController();
  final _priceController = TextEditingController();
  final _durationController = TextEditingController();
  final _deliveryTimeController = TextEditingController();
  final _revisionsController = TextEditingController();
  final _tagsController = TextEditingController();

  CategoryModel? _selectedCategory;
  bool _isInit = true;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      // Fetch categories on load
      Future.microtask(() => context.read<ServiceProvider>().fetchCategories());
      _isInit = false;
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _descriptionController.dispose();
    _priceController.dispose();
    _durationController.dispose();
    super.dispose();
  }

  void _submit() async {
    if (_formKey.currentState!.validate()) {
      if (_selectedCategory == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Please select a category')),
        );
        return;
      }

      final authProvider = context.read<AuthProvider>();
      final isFreelancer = authProvider.user?.mode == 'freelancer';

      final Map<String, dynamic> data = {
        'name': _nameController.text.trim(),
        'description': _descriptionController.text.trim(),
        'price': double.parse(_priceController.text),
        'duration_minutes': int.tryParse(_durationController.text) ?? 60,
        'category_id': _selectedCategory!.id,
        'type': isFreelancer ? 'freelancer' : 'local_service',
      };

      if (isFreelancer) {
        data['metadata'] = {
          'delivery_time': _deliveryTimeController.text,
          'revisions': _revisionsController.text,
        };
        if (_tagsController.text.isNotEmpty) {
          data['tags'] = _tagsController.text
              .split(',')
              .map((e) => e.trim())
              .toList();
        }
      }

      final success = await context.read<ServiceProvider>().createService(data);

      if (success && mounted) {
        context.pop();
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(const SnackBar(content: Text('Created successfully')));
      } else if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(
              context.read<ServiceProvider>().error ?? 'Failed to create',
            ),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final serviceProvider = context.watch<ServiceProvider>();

    final isFreelancer = authProvider.user?.mode == 'freelancer';
    final term = isFreelancer ? 'Gig' : 'Service';

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          'Add New $term',
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.bold,
            color: const Color(0xFF1E293B),
          ),
        ),
        elevation: 0,
        backgroundColor: Colors.white,
        leading: IconButton(
          icon: const Icon(
            Icons.arrow_back_ios_new_rounded,
            color: Colors.black,
          ),
          onPressed: () => context.pop(),
        ),
      ),
      body: serviceProvider.isLoading && serviceProvider.categories.isEmpty
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSectionLabel('Basic Information'),
                    const SizedBox(height: 16),
                    _buildTextField(
                      controller: _nameController,
                      label: '$term Title',
                      hint:
                          'e.g. ${isFreelancer ? 'I will design a logo' : 'Deep House Cleaning'}',
                      validator: (v) => v?.isEmpty == true ? 'Required' : null,
                    ),
                    const SizedBox(height: 16),

                    DropdownButtonFormField<CategoryModel>(
                      value: _selectedCategory,
                      decoration: InputDecoration(
                        labelText: 'Category',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: BorderSide(color: Colors.grey[300]!),
                        ),
                        enabledBorder: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(12),
                          borderSide: BorderSide(color: Colors.grey[300]!),
                        ),
                      ),
                      items: serviceProvider.categories.map((c) {
                        return DropdownMenuItem(value: c, child: Text(c.name));
                      }).toList(),
                      onChanged: (v) => setState(() => _selectedCategory = v),
                      validator: (v) => v == null ? 'Required' : null,
                    ),

                    const SizedBox(height: 24),
                    _buildSectionLabel('Pricing & Duration'),
                    const SizedBox(height: 16),

                    Row(
                      children: [
                        Expanded(
                          child: _buildTextField(
                            controller: _priceController,
                            label: 'Price (\$)',
                            hint: '0.00',
                            keyboardType: TextInputType.number,
                            validator: (v) =>
                                v?.isEmpty == true ? 'Required' : null,
                          ),
                        ),
                        if (!isFreelancer) ...[
                          const SizedBox(width: 16),
                          Expanded(
                            child: _buildTextField(
                              controller: _durationController,
                              label: 'Duration (min)',
                              hint: '60',
                              keyboardType: TextInputType.number,
                            ),
                          ),
                        ],
                      ],
                    ),

                    if (isFreelancer) ...[
                      const SizedBox(height: 24),
                      _buildSectionLabel('Gig Details'),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: _buildTextField(
                              controller: _deliveryTimeController,
                              label: 'Delivery (Days)',
                              hint: 'e.g. 3',
                              keyboardType: TextInputType.number,
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: _buildTextField(
                              controller: _revisionsController,
                              label: 'Revisions',
                              hint: 'e.g. Unlimited',
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _tagsController,
                        label: 'Search Tags',
                        hint: 'Comma separated (e.g. logo, design, minimal)',
                      ),
                    ],

                    const SizedBox(height: 24),
                    _buildSectionLabel('Description'),
                    const SizedBox(height: 16),

                    _buildTextField(
                      controller: _descriptionController,
                      label: 'Description',
                      hint: 'Describe your $term in detail...',
                      maxLines: 5,
                      validator: (v) => v?.isEmpty == true ? 'Required' : null,
                    ),

                    const SizedBox(height: 32),
                    SizedBox(
                      width: double.infinity,
                      height: 56,
                      child: ElevatedButton(
                        onPressed: serviceProvider.isLoading ? null : _submit,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Theme.of(context).primaryColor,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(16),
                          ),
                        ),
                        child: serviceProvider.isLoading
                            ? const CircularProgressIndicator(
                                color: Colors.white,
                              )
                            : Text(
                                'Create $term',
                                style: const TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.white,
                                ),
                              ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildSectionLabel(String label) {
    return Text(
      label,
      style: GoogleFonts.plusJakartaSans(
        fontSize: 18,
        fontWeight: FontWeight.bold,
        color: const Color(0xFF1E293B),
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    String? hint,
    TextInputType? keyboardType,
    String? Function(String?)? validator,
    int maxLines = 1,
  }) {
    return TextFormField(
      controller: controller,
      keyboardType: keyboardType,
      maxLines: maxLines,
      validator: validator,
      decoration: InputDecoration(
        labelText: label,
        hintText: hint,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Colors.grey[300]!),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Colors.grey[300]!),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Theme.of(context).primaryColor),
        ),
      ),
    );
  }
}
