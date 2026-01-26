import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import '../providers/service_provider.dart';
import '../../../auth/presentation/providers/auth_provider.dart';
import 'create_service_page.dart';

class ServicesPage extends StatefulWidget {
  const ServicesPage({super.key});

  @override
  State<ServicesPage> createState() => _ServicesPageState();
}

class _ServicesPageState extends State<ServicesPage> {
  bool _isInit = true;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (_isInit) {
      Future.microtask(() => context.read<ServiceProvider>().fetchServices());
      _isInit = false;
    }
  }

  @override
  Widget build(BuildContext context) {
    final serviceProvider = context.watch<ServiceProvider>();
    final authProvider = context.watch<AuthProvider>();
    final isFreelancer = authProvider.user?.mode == 'freelancer';
    final term = isFreelancer ? 'Gigs' : 'Services';

    return Scaffold(
      appBar: AppBar(
        title: Text(
          'My $term',
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.bold,
            color: const Color(0xFF1E293B),
          ),
        ),
        actions: [
          IconButton(
            onPressed: () {
              if (isFreelancer) {
                context.push('/create-gig');
              } else {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => const CreateServicePage(),
                  ),
                );
              }
            },
            icon: const Icon(Icons.add_circle_outline_rounded),
          ),
        ],
      ),
      body: serviceProvider.isLoading && serviceProvider.services.isEmpty
          ? const Center(child: CircularProgressIndicator())
          : serviceProvider.services.isEmpty
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    isFreelancer ? Icons.laptop_mac : Icons.cleaning_services,
                    size: 64,
                    color: Colors.grey[300],
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'No $term yet',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 18,
                      color: Colors.grey[500],
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  TextButton(
                    onPressed: () {
                      if (isFreelancer) {
                        context.push('/create-gig');
                      } else {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => const CreateServicePage(),
                          ),
                        );
                      }
                    },
                    child: Text('Create your first $term'),
                  ),
                ],
              ),
            )
          : RefreshIndicator(
              onRefresh: () => serviceProvider.fetchServices(),
              child: ListView.separated(
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
                itemCount: serviceProvider.services.length,
                separatorBuilder: (context, index) =>
                    const SizedBox(height: 16),
                itemBuilder: (context, index) {
                  final service = serviceProvider.services[index];
                  return Container(
                    padding: const EdgeInsets.all(12),
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
                    ),
                    child: Row(
                      children: [
                        Container(
                          width: 80,
                          height: 80,
                          decoration: BoxDecoration(
                            color: Colors.grey[100],
                            borderRadius: BorderRadius.circular(12),
                            image: service.image != null
                                ? DecorationImage(
                                    image: NetworkImage(service.image!),
                                    fit: BoxFit.cover,
                                  )
                                : null,
                          ),
                          child: service.image == null
                              ? Icon(
                                  isFreelancer
                                      ? Icons.code
                                      : Icons.cleaning_services,
                                  color: Theme.of(context).primaryColor,
                                  size: 30,
                                )
                              : null,
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                service.name,
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                                style: GoogleFonts.plusJakartaSans(
                                  fontWeight: FontWeight.bold,
                                  fontSize: 16,
                                  color: const Color(0xFF1E293B),
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                '\$${service.price.toStringAsFixed(2)}',
                                style: GoogleFonts.plusJakartaSans(
                                  color: Theme.of(context).primaryColor,
                                  fontWeight: FontWeight.bold,
                                  fontSize: 14,
                                ),
                              ),
                              const SizedBox(height: 8),
                              if (service.category != null)
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 8,
                                    vertical: 2,
                                  ),
                                  decoration: BoxDecoration(
                                    color: Colors.grey[100],
                                    borderRadius: BorderRadius.circular(4),
                                  ),
                                  child: Text(
                                    service.category!.name,
                                    style: GoogleFonts.plusJakartaSans(
                                      color: Colors.grey[600],
                                      fontSize: 10,
                                    ),
                                  ),
                                ),
                            ],
                          ),
                        ),
                        IconButton(
                          onPressed: () {},
                          icon: const Icon(
                            Icons.edit_outlined,
                            color: Colors.grey,
                          ),
                        ),
                      ],
                    ),
                  ).animate().fadeIn(delay: (index * 100).ms).slideX();
                },
              ),
            ),
    );
  }
}
