import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';
import 'package:image_cropper/image_cropper.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:shimmer/shimmer.dart';
import 'package:flutter_svg/flutter_svg.dart'; // Assuming available, otherwise Icon
import 'package:dio/dio.dart';
import '../../../../features/freelancer/gigs/data/datasources/gig_remote_data_source.dart';
import '../../../../features/freelancer/gigs/data/models/gig_model.dart';
import '../../../../features/freelancer/gigs/presentation/pages/gig_details_page.dart';
import '../../../../features/auth/data/models/user_model.dart';
import '../../../../features/auth/presentation/providers/auth_provider.dart';
import '../../data/profile_service.dart';

class MyProfilePage extends StatefulWidget {
  const MyProfilePage({Key? key}) : super(key: key);

  @override
  State<MyProfilePage> createState() => _MyProfilePageState();
}

class _MyProfilePageState extends State<MyProfilePage> {
  final ProfileService _profileService = ProfileService();
  final GigRemoteDataSource _gigDataSource = GigRemoteDataSourceImpl(dio: Dio(BaseOptions(
    headers: {'Accept': 'application/json'},
    connectTimeout: const Duration(seconds: 10),
    receiveTimeout: const Duration(seconds: 10),
  )));
  bool _isLoading = false;
  List<dynamic> _portfolios = [];
  List<GigModel> _gigs = [];

  @override
  void initState() {
    super.initState();
    _loadPortfolios();
    _loadGigs();
  }

  Future<void> _loadGigs() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (authProvider.user?.token == null) return;

    try {
      final gigs = await _gigDataSource.getProviderGigs(authProvider.user!.token!);
      if (mounted) {
        setState(() {
          _gigs = gigs;
        });
      }
    } catch (e) {
      debugPrint('Error loading gigs: $e');
    }
  }

  Future<void> _loadPortfolios() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (authProvider.user?.token == null) return;

    try {
      final portfolios = await _profileService.getPortfolios(authProvider.user!.token!);
      if (mounted) {
        setState(() {
          _portfolios = portfolios;
        });
      }
    } catch (e) {
      debugPrint('Error loading portfolios: $e');
    }
  }

  void _openEditPage() async {
    await Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => const EditMyProfilePage()),
    );
    _loadPortfolios();
  }

  void _openAddPortfolio() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => _AddPortfolioSheet(onSuccess: _loadPortfolios),
    );
  }

  void _deletePortfolio(int id) async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (authProvider.user?.token == null) return;

    try {
      await _profileService.deletePortfolio(authProvider.user!.token!, id);
      _loadPortfolios();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Portfolio deleted')),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    if (user == null) return const Scaffold(body: Center(child: CircularProgressIndicator()));

    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FD),
      body: DefaultTabController(
        length: 4,
        child: NestedScrollView(
          headerSliverBuilder: (context, innerBoxIsScrolled) {
            return [
              SliverAppBar(
                expandedHeight: 340,
                pinned: true,
                stretch: true,
                backgroundColor: const Color(0xFF2E3192),
                flexibleSpace: FlexibleSpaceBar(
                  collapseMode: CollapseMode.parallax,
                  background: Container(
                    decoration: const BoxDecoration(
                      gradient: LinearGradient(
                        colors: [Color(0xFF1BFFFF), Color(0xFF2E3192)],
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                      ),
                    ),
                    child: SafeArea(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const SizedBox(height: 20),
                          Container(
                            padding: const EdgeInsets.all(4),
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              border: Border.all(color: Colors.white.withOpacity(0.3), width: 2),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withOpacity(0.2),
                                  blurRadius: 20,
                                  spreadRadius: 5,
                                ),
                              ],
                            ),
                            child: CircleAvatar(
                              radius: 50,
                              backgroundImage: user.image != null ? NetworkImage(user.image!) : null,
                              backgroundColor: Colors.white,
                              child: user.image == null ? const Icon(Icons.person, size: 50, color: Colors.grey) : null,
                            ),
                          ),
                          const SizedBox(height: 16),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Text(
                                user.name ?? 'No Name',
                                style: GoogleFonts.plusJakartaSans(
                                  fontSize: 24,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.white,
                                  shadows: [Shadow(color: Colors.black45, blurRadius: 10)],
                                ),
                              ),
                              if (user.level != null) ...[
                                const SizedBox(width: 8),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                  decoration: BoxDecoration(
                                    color: Colors.white.withOpacity(0.2),
                                    borderRadius: BorderRadius.circular(12),
                                    border: Border.all(color: Colors.white.withOpacity(0.3)),
                                  ),
                                  child: Text(
                                    user.level!,
                                    style: GoogleFonts.plusJakartaSans(
                                      fontSize: 10,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.white,
                                    ),
                                  ),
                                ),
                              ],
                            ],
                          ),
                          const SizedBox(height: 4),
                          Text(
                            user.email ?? '',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 14,
                              color: Colors.white.withOpacity(0.9),
                            ),
                          ),
                          const SizedBox(height: 24),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              _buildStatItem(user.rating?.toStringAsFixed(1) ?? 'N/A', 'Rating (${user.totalReviews ?? 0})'),
                              _buildVerticalDivider(),
                              _buildStatItem('${_portfolios.length}', 'Projects'),
                              _buildVerticalDivider(),
                              _buildStatItem('${user.yearsExperience ?? 0}+', 'Years Exp'),
                            ],
                          ),
                          const SizedBox(height: 20),
                        ],
                      ),
                    ),
                  ),
                ),
                leading: IconButton(
                  icon: const Icon(Icons.arrow_back, color: Colors.white),
                  onPressed: () => Navigator.pop(context),
                ),
                actions: [
                  IconButton(
                    icon: const Icon(Icons.edit_outlined, color: Colors.white),
                    onPressed: _openEditPage,
                  ),
                ],
              ),
              SliverPersistentHeader(
                delegate: _StickyTabBarDelegate(
                  TabBar(
                    labelColor: const Color(0xFF2E3192),
                    unselectedLabelColor: Colors.grey,
                    indicatorColor: const Color(0xFF2E3192),
                    indicatorWeight: 3,
                    labelStyle: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 15),
                    unselectedLabelStyle: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.w600, fontSize: 15),
                    tabs: const [
                      Tab(text: 'About'),
                      Tab(text: 'Gigs'),
                      Tab(text: 'Portfolio'),
                      Tab(text: 'Reviews'),
                    ],
                  ),
                ),
                pinned: true,
              ),
            ];
          },
          body: TabBarView(
            children: [
              _buildAboutTab(user),
              _buildGigsTab(),
              _buildPortfolioTab(),
              _buildReviewsTab(),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildVerticalDivider() {
    return Container(
      height: 30,
      width: 1,
      margin: const EdgeInsets.symmetric(horizontal: 20),
      color: Colors.white.withOpacity(0.3),
    );
  }

  Widget _buildStatItem(String value, String label) {
    return Column(
      children: [
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 20,
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 12,
            color: Colors.white.withOpacity(0.8),
          ),
        ),
      ],
    );
  }

  Widget _buildAboutTab(UserModel user) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // New Stats Grid
          _buildStatsGrid(user),
          const SizedBox(height: 24),
          
          Text('Biography', style: GoogleFonts.plusJakartaSans(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          Text(
            user.about ?? 'No biography provided yet.',
            style: GoogleFonts.plusJakartaSans(color: Colors.grey.shade700, height: 1.6, fontSize: 15),
          ),
          const SizedBox(height: 24),
          
          Text('Personal Details', style: GoogleFonts.plusJakartaSans(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 16),
          _buildDetailRow(Icons.business, 'Company', user.companyName ?? 'Freelancer'),
          _buildDetailRow(Icons.location_on, 'Address', user.address ?? 'Not set'),
          _buildDetailRow(Icons.public, 'Country', user.country ?? 'Not set'),
          
          const SizedBox(height: 24),
          Text('Languages', style: GoogleFonts.plusJakartaSans(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          if (user.languages == null || user.languages!.isEmpty)
             Text('No languages added', style: GoogleFonts.plusJakartaSans(color: Colors.grey))
          else
            Wrap(
              spacing: 10,
              runSpacing: 10,
              children: user.languages!.map((lang) => _buildChip(lang, Colors.blue)).toList(),
            ),

          const SizedBox(height: 24),
          Text('Skills', style: GoogleFonts.plusJakartaSans(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          if (user.skills == null || user.skills!.isEmpty)
             Text('No skills added', style: GoogleFonts.plusJakartaSans(color: Colors.grey))
          else
            Wrap(
              spacing: 10,
              runSpacing: 10,
              children: user.skills!.map((skill) => _buildChip(skill, Colors.purple)).toList(),
            ),
            
          const SizedBox(height: 40),
        ],
      ),
    );
  }

  Widget _buildStatsGrid(UserModel user) {
    // Format date nicely
    String memberSince = 'Unknown';
    if (user.createdAt != null) {
      try {
        final date = DateTime.parse(user.createdAt!);
        final months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        memberSince = '${months[date.month - 1]} ${date.year}';
      } catch (e) {
        memberSince = user.createdAt!.split('T').first.split(' ').first;
      }
    }
        
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 15,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Column(
        children: [
          Row(
            children: [
              Expanded(child: _buildStatCard(Icons.calendar_today_rounded, 'Member Since', memberSince, Colors.blue)),
              const SizedBox(width: 16),
              Expanded(child: _buildStatCard(Icons.timer_outlined, 'Avg Response', user.avgResponseTime ?? '1 Hour', Colors.orange)),
            ],
          ),
          const SizedBox(height: 20),
          Divider(color: Colors.grey.shade100, height: 1),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(child: _buildStatCard(Icons.local_shipping_outlined, 'Last Delivery', user.lastDelivery ?? 'N/A', Colors.green)),
              const SizedBox(width: 16),
              Expanded(child: _buildStatCard(Icons.offline_bolt_outlined, 'Last Active', user.lastActive ?? 'Online', Colors.purple)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildStatCard(IconData icon, String label, String value, MaterialColor color) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Icon(icon, size: 18, color: color),
            const SizedBox(width: 8),
            Text(
              label,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 12,
                color: Colors.grey.shade600,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
        const SizedBox(height: 8),
        Text(
          value,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 15,
            fontWeight: FontWeight.bold,
            color: Colors.black87,
          ),
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
      ],
    );
  }

  Widget _buildDetailRow(IconData icon, String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.grey.shade100,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(icon, color: const Color(0xFF2E3192), size: 22),
          ),
          const SizedBox(width: 16),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(label, style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey)),
              Text(value, style: GoogleFonts.plusJakartaSans(fontSize: 15, fontWeight: FontWeight.w600)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildChip(String label, MaterialColor color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        border: Border.all(color: color.shade100),
        boxShadow: [
          BoxShadow(
            color: color.withOpacity(0.05),
            blurRadius: 5,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Text(
        label,
        style: GoogleFonts.plusJakartaSans(
          color: color.shade700,
          fontWeight: FontWeight.w600,
          fontSize: 13,
        ),
      ),
    );
  }

  Widget _buildGigsTab() {
    if (_gigs.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.work_off_outlined, size: 64, color: Colors.grey[300]),
            const SizedBox(height: 16),
            Text(
              'No gigs published yet',
              style: GoogleFonts.plusJakartaSans(
                color: Colors.grey,
                fontSize: 16,
                fontWeight: FontWeight.w500,
              ),
            ),
          ],
        ),
      );
    }

    return GridView.builder(
      padding: const EdgeInsets.all(20),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        childAspectRatio: 0.75,
        crossAxisSpacing: 16,
        mainAxisSpacing: 16,
      ),
      itemCount: _gigs.length,
      itemBuilder: (context, index) {
        final gig = _gigs[index];
        return _buildGigCard(gig);
      },
    );
  }

  Widget _buildGigCard(GigModel gig) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => GigDetailsPage(gig: gig),
          ),
        );
      },
      child: Container(
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
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              child: ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                child: gig.images.isNotEmpty
                    ? Image.network(
                        gig.images.first,
                        width: double.infinity,
                        fit: BoxFit.cover,
                        errorBuilder: (context, error, stackTrace) => Container(
                          color: Colors.grey[100],
                          child: const Icon(Icons.image_not_supported, color: Colors.grey),
                        ),
                      )
                    : Container(
                        color: Colors.grey[100],
                        child: const Icon(Icons.image, color: Colors.grey),
                      ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    gig.title,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        'Starting at',
                        style: GoogleFonts.plusJakartaSans(
                          color: Colors.grey,
                          fontSize: 12,
                        ),
                      ),
                      Text(
                        '\$${gig.packages.isNotEmpty ? gig.packages.first.price : 0}',
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold,
                          fontSize: 14,
                          color: const Color(0xFF2E3192),
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
    );
  }

  Widget _buildPortfolioTab() {
    if (_portfolios.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.folder_open_rounded, size: 80, color: Colors.grey.shade200),
            const SizedBox(height: 16),
            Text('No projects yet', style: GoogleFonts.plusJakartaSans(color: Colors.grey, fontSize: 16)),
            const SizedBox(height: 24),
            OutlinedButton.icon(
              onPressed: _openAddPortfolio,
              icon: const Icon(Icons.add),
              label: const Text('Add Project'),
              style: OutlinedButton.styleFrom(
                foregroundColor: const Color(0xFF2E3192),
                side: const BorderSide(color: Color(0xFF2E3192)),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
              ),
            ),
          ],
        ),
      );
    }

    return ListView(
      padding: const EdgeInsets.all(20),
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text('${_portfolios.length} Projects', style: GoogleFonts.plusJakartaSans(color: Colors.grey, fontWeight: FontWeight.bold)),
            TextButton.icon(
              onPressed: _openAddPortfolio,
              icon: const Icon(Icons.add_circle),
              label: const Text('Add New'),
              style: TextButton.styleFrom(foregroundColor: const Color(0xFF2E3192)),
            ),
          ],
        ),
        const SizedBox(height: 16),
        ..._portfolios.map((item) => Container(
          margin: const EdgeInsets.only(bottom: 20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.05),
                blurRadius: 15,
                offset: const Offset(0, 5),
              ),
            ],
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
                child: Image.network(
                  item['image_path'] ?? '',
                  width: double.infinity,
                  height: 200,
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => Container(
                    height: 200,
                    color: Colors.grey.shade100,
                    child: const Icon(Icons.broken_image, color: Colors.grey),
                  ),
                ),
              ),
              Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Expanded(
                          child: Text(
                            item['title'] ?? 'Untitled',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.delete_outline, color: Colors.red),
                          onPressed: () => _deletePortfolio(item['id']),
                        ),
                      ],
                    ),
                    if (item['description'] != null) ...[
                      const SizedBox(height: 8),
                      Text(
                        item['description'],
                        style: GoogleFonts.plusJakartaSans(color: Colors.grey.shade600, height: 1.5),
                      ),
                    ],
                  ],
                ),
              ),
            ],
          ),
        )).toList(),
      ],
    );
  }

  Widget _buildReviewsTab() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.star_border_rounded, size: 80, color: Colors.grey.shade200),
          const SizedBox(height: 16),
          Text('No reviews received yet', style: GoogleFonts.plusJakartaSans(color: Colors.grey, fontSize: 16)),
        ],
      ),
    );
  }
}

class _StickyTabBarDelegate extends SliverPersistentHeaderDelegate {
  final TabBar _tabBar;

  _StickyTabBarDelegate(this._tabBar);

  @override
  double get minExtent => _tabBar.preferredSize.height;

  @override
  double get maxExtent => _tabBar.preferredSize.height;

  @override
  Widget build(BuildContext context, double shrinkOffset, bool overlapsContent) {
    return Container(
      color: Colors.white,
      child: Container(
        decoration: BoxDecoration(
          border: Border(bottom: BorderSide(color: Colors.grey.shade200)),
        ),
        child: _tabBar,
      ),
    );
  }

  @override
  bool shouldRebuild(_StickyTabBarDelegate oldDelegate) {
    return false;
  }
}

class EditMyProfilePage extends StatefulWidget {
  const EditMyProfilePage({Key? key}) : super(key: key);

  @override
  State<EditMyProfilePage> createState() => _EditMyProfilePageState();
}

class _EditMyProfilePageState extends State<EditMyProfilePage> {
  final _formKey = GlobalKey<FormState>();
  File? _imageFile;
  final ImagePicker _picker = ImagePicker();
  late TextEditingController _nameCtrl;
  late TextEditingController _companyCtrl;
  late TextEditingController _aboutCtrl;
  late TextEditingController _addressCtrl;
  late TextEditingController _yearsExpCtrl;

  String? _selectedCountry;
  List<String> _selectedLanguages = [];
  List<String> _selectedSkills = [];

  bool _isLoading = false;
  final ProfileService _profileService = ProfileService();

  List<dynamic> _countries = [];
  List<dynamic> _languagesList = [];
  List<dynamic> _skillsList = [];
  bool _loadingData = true;

  @override
  void initState() {
    super.initState();
    final user = Provider.of<AuthProvider>(context, listen: false).user;
    _nameCtrl = TextEditingController(text: user?.name);
    _companyCtrl = TextEditingController(text: user?.companyName);
    _aboutCtrl = TextEditingController(text: user?.about);
    _addressCtrl = TextEditingController(text: user?.address);
    _yearsExpCtrl = TextEditingController(text: user?.yearsExperience?.toString() ?? '');
    
    _selectedCountry = user?.country;
    _selectedLanguages = List.from(user?.languages ?? []);
    _selectedSkills = List.from(user?.skills ?? []);

    _loadData();
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _companyCtrl.dispose();
    _aboutCtrl.dispose();
    _addressCtrl.dispose();
    _yearsExpCtrl.dispose();
    super.dispose();
  }

  Future<void> _pickImage() async {
    try {
      final XFile? pickedFile = await _picker.pickImage(source: ImageSource.gallery);
      if (pickedFile != null) {
        _cropImage(File(pickedFile.path));
      }
    } catch (e) {
      debugPrint('Error picking image: $e');
    }
  }

  Future<void> _cropImage(File imageFile) async {
    try {
      final croppedFile = await ImageCropper().cropImage(
        sourcePath: imageFile.path,
        // aspectRatioPresets: [
        //   CropAspectRatioPreset.square,
        // ],
        uiSettings: [
          AndroidUiSettings(
              toolbarTitle: 'Edit Photo',
              toolbarColor: const Color(0xFF2E3192),
              toolbarWidgetColor: Colors.white,
              initAspectRatio: CropAspectRatioPreset.square,
              lockAspectRatio: true),
          IOSUiSettings(
            title: 'Edit Photo',
          ),
        ],
      );
      if (croppedFile != null) {
        setState(() {
          _imageFile = File(croppedFile.path);
        });
      }
    } catch (e) {
      debugPrint('Error cropping image: $e');
    }
  }

  Future<void> _loadData() async {
    try {
      final countries = await _profileService.getCountries();
      final languages = await _profileService.getLanguages();
      final skills = await _profileService.getSkills();
      
      if (mounted) {
        setState(() {
          _countries = countries;
          _languagesList = languages;
          _skillsList = skills;
          _loadingData = false;
        });
      }
    } catch (e) {
      debugPrint('Error loading data: $e');
      if (mounted) setState(() => _loadingData = false);
    }
  }



  Future<void> _save() async {
    if (!_formKey.currentState!.validate()) return;
    
    setState(() => _isLoading = true);
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    
    try {
      final updatedUser = await _profileService.updateProviderProfile(
        authProvider.user!.token!,
        name: _nameCtrl.text,
        companyName: _companyCtrl.text,
        about: _aboutCtrl.text,
        address: _addressCtrl.text,
        country: _selectedCountry,
        languages: _selectedLanguages,
        skills: _selectedSkills,
        yearsExperience: int.tryParse(_yearsExpCtrl.text),
        image: _imageFile,
      );

      // Update local user state
      authProvider.setUser(UserModel.fromJson(updatedUser));
      
      _showToast('Profile updated successfully', isError: false);
      Navigator.pop(context);
      
    } catch (e) {
      _showToast('Error: $e', isError: true);
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  void _showToast(String message, {bool isError = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: isError ? Colors.red.withOpacity(0.1) : Colors.green.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(
                isError ? Icons.error_rounded : Icons.check_circle_rounded,
                color: isError ? Colors.red : Colors.green,
                size: 20,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    isError ? 'Error' : 'Success',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      color: Colors.black87,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    message,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 13,
                      color: Colors.black54,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
        backgroundColor: Colors.white,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        margin: const EdgeInsets.all(20),
        elevation: 8,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        duration: const Duration(seconds: 4),
      ),
    );
  }

  void _openCountryPicker() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => _CountryPickerSheet(
        countries: _countries,
        selected: _selectedCountry,
        onSelect: (val) => setState(() => _selectedCountry = val),
      ),
    );
  }

  void _openLanguagePicker() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => _MultiSelectSheet(
        title: 'Select Languages',
        items: _languagesList.map((e) => e['name'].toString()).toList(),
        selected: _selectedLanguages,
        onSelect: (val) => setState(() => _selectedLanguages = val),
      ),
    );
  }

  void _openSkillPicker() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => _MultiSelectSheet(
        title: 'Select Skills',
        items: _skillsList.map((e) => e['name'].toString()).toList(),
        selected: _selectedSkills,
        onSelect: (val) => setState(() => _selectedSkills = val),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;
    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FD),
      appBar: AppBar(
        title: Text('Edit Profile', style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, color: Colors.black)),
        centerTitle: true,
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.black),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 20,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: ElevatedButton(
              onPressed: _isLoading ? null : _save,
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF2E3192),
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                elevation: 0,
              ),
              child: _isLoading 
                ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : Text('Save Changes', style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold)),
            ),
          ),
        ),
      ),
      body: _loadingData 
        ? const Center(child: CircularProgressIndicator())
        : SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: Form(
              key: _formKey,
              child: Column(
                children: [
                  Center(
                    child: Stack(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(4),
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            border: Border.all(color: const Color(0xFF2E3192).withOpacity(0.2), width: 2),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.black.withOpacity(0.1),
                                blurRadius: 15,
                                spreadRadius: 2,
                              ),
                            ],
                          ),
                          child: CircleAvatar(
                            radius: 60,
                            backgroundColor: Colors.white,
                            backgroundImage: _imageFile != null
                                ? FileImage(_imageFile!)
                                : (user?.image != null
                                    ? NetworkImage(user!.image!)
                                    : null) as ImageProvider?,
                            child: (_imageFile == null && user?.image == null)
                                ? const Icon(Icons.person, size: 60, color: Colors.grey)
                                : null,
                          ),
                        ),
                        Positioned(
                          bottom: 0,
                          right: 0,
                          child: GestureDetector(
                            onTap: _pickImage,
                            child: Container(
                              padding: const EdgeInsets.all(10),
                              decoration: const BoxDecoration(
                                color: Color(0xFF2E3192),
                                shape: BoxShape.circle,
                              ),
                              child: const Icon(Icons.camera_alt, color: Colors.white, size: 20),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 32),
                  _buildSection(
                    title: 'Basic Information',
                    icon: Icons.person_outline,
                    children: [
                      _buildTextField(
                        controller: _nameCtrl,
                        label: 'Full Name',
                        hint: 'Enter your full name',
                        icon: Icons.person,
                        validator: (v) => v!.isEmpty ? 'Required' : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _aboutCtrl,
                        label: 'About Me',
                        hint: 'Tell us a bit about yourself...',
                        icon: Icons.description,
                        maxLines: 4,
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),
                  
                  _buildSection(
                    title: 'Professional Details',
                    icon: Icons.work_outline,
                    children: [
                      _buildTextField(
                        controller: _companyCtrl,
                        label: 'Company Name',
                        hint: 'e.g. Studio Design',
                        icon: Icons.business,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _yearsExpCtrl,
                        label: 'Years of Experience',
                        hint: 'e.g. 5',
                        icon: Icons.timer,
                        keyboardType: TextInputType.number,
                      ),
                      const SizedBox(height: 16),
                      _buildSelector(
                        label: 'Skills',
                        value: _selectedSkills.isEmpty ? 'Select Skills' : '${_selectedSkills.length} selected',
                        icon: Icons.auto_awesome,
                        onTap: _openSkillPicker,
                        isMulti: true,
                        items: _selectedSkills,
                        onDelete: (item) => setState(() => _selectedSkills.remove(item)),
                      ),
                      const SizedBox(height: 16),
                      _buildSelector(
                        label: 'Languages',
                        value: _selectedLanguages.isEmpty ? 'Select Languages' : '${_selectedLanguages.length} selected',
                        icon: Icons.translate,
                        onTap: _openLanguagePicker,
                        isMulti: true,
                        items: _selectedLanguages,
                        onDelete: (item) => setState(() => _selectedLanguages.remove(item)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 24),

                  _buildSection(
                    title: 'Location',
                    icon: Icons.location_on_outlined,
                    children: [
                      _buildTextField(
                        controller: _addressCtrl,
                        label: 'Address',
                        hint: 'Street address, City',
                        icon: Icons.location_city,
                      ),
                      const SizedBox(height: 16),
                      _buildSelector(
                        label: 'Country',
                        value: _selectedCountry ?? 'Select Country',
                        icon: Icons.public,
                        onTap: _openCountryPicker,
                      ),
                    ],
                  ),
                  const SizedBox(height: 120), // Extra padding for bottom bar
                ],
              ),
            ),
          ),
    );
  }

  Widget _buildSection({required String title, required IconData icon, required List<Widget> children}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 4, bottom: 12),
          child: Row(
            children: [
              Icon(icon, size: 20, color: const Color(0xFF2E3192)),
              const SizedBox(width: 8),
              Text(title, style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold, color: const Color(0xFF2E3192))),
            ],
          ),
        ),
        Container(
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.03),
                blurRadius: 15,
                offset: const Offset(0, 5),
              ),
            ],
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: children,
          ),
        ),
      ],
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required String hint,
    required IconData icon,
    int maxLines = 1,
    TextInputType? keyboardType,
    String? Function(String?)? validator,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w600, color: Colors.grey.shade700)),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          maxLines: maxLines,
          keyboardType: keyboardType,
          validator: validator,
          style: GoogleFonts.plusJakartaSans(fontSize: 15),
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: GoogleFonts.plusJakartaSans(color: Colors.grey.shade400),
            prefixIcon: Icon(icon, color: Colors.grey.shade400, size: 22),
            filled: true,
            fillColor: const Color(0xFFF5F6FA),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
            enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
            focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFF2E3192), width: 1.5)),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          ),
        ),
      ],
    );
  }

  Widget _buildSelector({
    required String label,
    required String value,
    required IconData icon,
    required VoidCallback onTap,
    bool isMulti = false,
    List<String>? items,
    Function(String)? onDelete,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w600, color: Colors.grey.shade700)),
        const SizedBox(height: 8),
        InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(12),
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            decoration: BoxDecoration(
              color: const Color(0xFFF5F6FA),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: Colors.transparent),
            ),
            child: Row(
              children: [
                Icon(icon, color: Colors.grey.shade400, size: 22),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(
                    value,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 15,
                      color: value.startsWith('Select') ? Colors.grey.shade400 : Colors.black87,
                    ),
                  ),
                ),
                Icon(Icons.arrow_forward_ios_rounded, size: 16, color: Colors.grey.shade400),
              ],
            ),
          ),
        ),
        if (isMulti && items != null && items.isNotEmpty) ...[
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: items.map((item) => Container(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: Colors.grey.shade200),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(item, style: GoogleFonts.plusJakartaSans(fontSize: 13, color: Colors.grey.shade800)),
                  const SizedBox(width: 4),
                  InkWell(
                    onTap: () => onDelete?.call(item),
                    child: Icon(Icons.close, size: 16, color: Colors.grey.shade400),
                  ),
                ],
              ),
            )).toList(),
          ),
        ],
      ],
    );
  }
}

class _CountryPickerSheet extends StatefulWidget {
  final List<dynamic> countries;
  final String? selected;
  final ValueChanged<String> onSelect;

  const _CountryPickerSheet({
    Key? key,
    required this.countries,
    required this.selected,
    required this.onSelect,
  }) : super(key: key);

  @override
  State<_CountryPickerSheet> createState() => _CountryPickerSheetState();
}

class _CountryPickerSheetState extends State<_CountryPickerSheet> {
  final TextEditingController _search = TextEditingController();
  List<dynamic> _filtered = [];

  @override
  void initState() {
    super.initState();
    _filtered = widget.countries;
    _search.addListener(() {
      final query = _search.text.toLowerCase();
      setState(() {
        _filtered = widget.countries.where((c) {
          final name = c['name'].toString().toLowerCase();
          return name.contains(query);
        }).toList();
      });
    });
  }

  @override
  void dispose() {
    _search.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.8,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              controller: _search,
              decoration: InputDecoration(
                hintText: 'Search Country',
                prefixIcon: const Icon(Icons.search),
                filled: true,
                fillColor: Colors.grey.shade100,
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              itemCount: _filtered.length,
              itemBuilder: (context, index) {
                final country = _filtered[index];
                final name = country['name'];
                final isSelected = name == widget.selected;
                return ListTile(
                  title: Text(name),
                  trailing: isSelected ? const Icon(Icons.check, color: Colors.blue) : null,
                  onTap: () {
                    widget.onSelect(name);
                    Navigator.pop(context);
                  },
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

class _MultiSelectSheet extends StatefulWidget {
  final String title;
  final List<String> items;
  final List<String> selected;
  final ValueChanged<List<String>> onSelect;

  const _MultiSelectSheet({
    Key? key,
    required this.title,
    required this.items,
    required this.selected,
    required this.onSelect,
  }) : super(key: key);

  @override
  State<_MultiSelectSheet> createState() => _MultiSelectSheetState();
}

class _MultiSelectSheetState extends State<_MultiSelectSheet> {
  final TextEditingController _search = TextEditingController();
  List<String> _filtered = [];
  List<String> _tempSelected = [];

  @override
  void initState() {
    super.initState();
    _filtered = widget.items;
    _tempSelected = List.from(widget.selected);
    _search.addListener(() {
      final query = _search.text.toLowerCase();
      setState(() {
        _filtered = widget.items.where((i) => i.toLowerCase().contains(query)).toList();
      });
    });
  }

  @override
  void dispose() {
    _search.dispose();
    super.dispose();
  }

  void _toggle(String item) {
    setState(() {
      if (_tempSelected.contains(item)) {
        _tempSelected.remove(item);
      } else {
        _tempSelected.add(item);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.8,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Row(
              children: [
                Expanded(child: Text(widget.title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold))),
                TextButton(
                  onPressed: () {
                    widget.onSelect(_tempSelected);
                    Navigator.pop(context);
                  },
                  child: const Text('Done'),
                ),
              ],
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: TextField(
              controller: _search,
              decoration: InputDecoration(
                hintText: 'Search...',
                prefixIcon: const Icon(Icons.search),
                filled: true,
                fillColor: Colors.grey.shade100,
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              itemCount: _filtered.length,
              itemBuilder: (context, index) {
                final item = _filtered[index];
                final isSelected = _tempSelected.contains(item);
                return ListTile(
                  title: Text(item),
                  trailing: Checkbox(
                    value: isSelected,
                    onChanged: (_) => _toggle(item),
                  ),
                  onTap: () => _toggle(item),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

class _AddPortfolioSheet extends StatefulWidget {
  final VoidCallback onSuccess;
  const _AddPortfolioSheet({Key? key, required this.onSuccess}) : super(key: key);

  @override
  State<_AddPortfolioSheet> createState() => _AddPortfolioSheetState();
}

class _AddPortfolioSheetState extends State<_AddPortfolioSheet> {
  final _titleCtrl = TextEditingController();
  final _descCtrl = TextEditingController();
  final _linkCtrl = TextEditingController();
  List<File> _images = [];
  bool _isLoading = false;
  final ProfileService _profileService = ProfileService();

  Future<void> _pickImages() async {
    final picker = ImagePicker();
    final picked = await picker.pickMultiImage(limit: 5);
    if (picked.isNotEmpty) {
      setState(() {
        _images.addAll(picked.map((e) => File(e.path)));
        if (_images.length > 5) {
          _images = _images.sublist(0, 5);
        }
      });
    }
  }

  Future<void> _save() async {
    if (_titleCtrl.text.isEmpty || _images.isEmpty) {
      _showToast('Title and at least 1 image required', isError: true);
      return;
    }

    setState(() => _isLoading = true);
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    
    try {
      await _profileService.addPortfolio(
        authProvider.user!.token!,
        title: _titleCtrl.text,
        description: _descCtrl.text,
        link: _linkCtrl.text,
        images: _images,
      );
      widget.onSuccess();
      Navigator.pop(context);
    } catch (e) {
      _showToast('Error: $e', isError: true);
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  void _showToast(String message, {bool isError = false}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: isError ? Colors.red.withOpacity(0.1) : Colors.green.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: Icon(
                isError ? Icons.error_rounded : Icons.check_circle_rounded,
                color: isError ? Colors.red : Colors.green,
                size: 20,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    isError ? 'Error' : 'Success',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                      color: Colors.black87,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    message,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 13,
                      color: Colors.black54,
                    ),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ],
        ),
        backgroundColor: Colors.white,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        margin: const EdgeInsets.all(20),
        elevation: 8,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        duration: const Duration(seconds: 4),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.9,
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Add Portfolio', style: GoogleFonts.plusJakartaSans(fontSize: 20, fontWeight: FontWeight.bold)),
              IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(context)),
            ],
          ),
          const SizedBox(height: 20),
          Expanded(
            child: SingleChildScrollView(
              child: Column(
                children: [
                  TextField(
                    controller: _titleCtrl,
                    decoration: const InputDecoration(labelText: 'Title', border: OutlineInputBorder()),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _descCtrl,
                    maxLines: 3,
                    decoration: const InputDecoration(labelText: 'Description', border: OutlineInputBorder()),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _linkCtrl,
                    decoration: const InputDecoration(labelText: 'External Link', border: OutlineInputBorder()),
                  ),
                  const SizedBox(height: 20),
                  Row(
                    children: [
                      Text('Images (Max 5)', style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold)),
                      const Spacer(),
                      TextButton.icon(
                        onPressed: _images.length >= 5 ? null : _pickImages,
                        icon: const Icon(Icons.add_photo_alternate),
                        label: const Text('Add Images'),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  if (_images.isNotEmpty)
                    SizedBox(
                      height: 100,
                      child: ListView.separated(
                        scrollDirection: Axis.horizontal,
                        itemCount: _images.length,
                        separatorBuilder: (_, __) => const SizedBox(width: 10),
                        itemBuilder: (context, index) {
                          return Stack(
                            children: [
                              ClipRRect(
                                borderRadius: BorderRadius.circular(8),
                                child: Image.file(_images[index], width: 100, height: 100, fit: BoxFit.cover),
                              ),
                              Positioned(
                                top: 0,
                                right: 0,
                                child: IconButton(
                                  icon: const Icon(Icons.remove_circle, color: Colors.red),
                                  onPressed: () => setState(() => _images.removeAt(index)),
                                ),
                              ),
                            ],
                          );
                        },
                      ),
                    ),
                ],
              ),
            ),
          ),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _isLoading ? null : _save,
              style: ElevatedButton.styleFrom(
                padding: const EdgeInsets.symmetric(vertical: 16),
                backgroundColor: Colors.blue,
              ),
              child: _isLoading ? const CircularProgressIndicator(color: Colors.white) : const Text('Save Portfolio'),
            ),
          ),
        ],
      ),
    );
  }
}
