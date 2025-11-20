import 'dart:async';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../../../core/app_theme.dart';
import '../../../core/api_constants.dart';
import '../../../data/models/merchant_model.dart';
import '../../../providers/home_provider.dart';
import '../merchant/merchant_detail_screen.dart';
import 'category_products_screen.dart';

class StudentHomeScreen extends StatefulWidget {
  const StudentHomeScreen({super.key});

  @override
  State<StudentHomeScreen> createState() => _StudentHomeScreenState();
}

class _StudentHomeScreenState extends State<StudentHomeScreen> {
  String _firstName = "M"; // Default inisial
  String _fullName = "Mahasiswa";
  
  // Variabel Carousel
  int _currentBannerIndex = 0;
  late PageController _pageController;
  Timer? _timer;

  @override
  void initState() {
    super.initState();
    _loadUserData();
    _pageController = PageController(initialPage: 0, viewportFraction: 0.92); // Viewport 0.92 biar banner samping ngintip dikit (Style Premium)

    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<HomeProvider>(context, listen: false);
      provider.getHomeData().then((_) {
        if (provider.banners.isNotEmpty) {
          _startAutoScroll(provider.banners.length);
        }
      });
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    _pageController.dispose();
    super.dispose();
  }

  void _startAutoScroll(int totalBanners) {
    _timer?.cancel();
    _timer = Timer.periodic(const Duration(seconds: 5), (Timer timer) {
      if (_currentBannerIndex < totalBanners - 1) {
        _currentBannerIndex++;
      } else {
        _currentBannerIndex = 0;
      }
      if (_pageController.hasClients) {
        _pageController.animateToPage(
          _currentBannerIndex,
          duration: const Duration(milliseconds: 800),
          curve: Curves.fastOutSlowIn, // Kurva animasi lebih smooth
        );
      }
    });
  }

  Future<void> _loadUserData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _fullName = prefs.getString('user_name') ?? "Mahasiswa UTB";
      // Ambil huruf depan untuk Avatar
      if (_fullName.isNotEmpty) {
        _firstName = _fullName[0].toUpperCase();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF9FAFB), // Background sangat bersih
      body: Consumer<HomeProvider>(
        builder: (context, homeProvider, child) {
          if (homeProvider.isLoading) {
            return const Center(child: CircularProgressIndicator(color: AppColors.primary));
          }

          return SafeArea(
            child: CustomScrollView(
              physics: const BouncingScrollPhysics(),
              slivers: [
                // =================================================
                // 1. CUSTOM APP BAR (PREMIUM HEADER)
                // =================================================
                SliverToBoxAdapter(
                  child: Padding(
                    padding: const EdgeInsets.fromLTRB(20, 20, 20, 10),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              "Lokasi Kamu",
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 12, color: Colors.grey.shade500, letterSpacing: 0.5
                              ),
                            ),
                            Row(
                              children: [
                                const Icon(Icons.location_on_rounded, color: AppColors.secondary, size: 16),
                                const SizedBox(width: 4),
                                Text(
                                  "Kampus UTB Bandung",
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 14, fontWeight: FontWeight.w700, color: AppColors.textPrimary
                                  ),
                                ),
                                const Icon(Icons.keyboard_arrow_down_rounded, color: Colors.grey, size: 18),
                              ],
                            ),
                          ],
                        ),
                        
                        // --- DYNAMIC AVATAR (GMAIL STYLE) ---
                        Container(
                          width: 45, height: 45,
                          decoration: BoxDecoration(
                            gradient: const LinearGradient(
                              colors: [AppColors.primary, Color(0xFF4B7BE5)],
                              begin: Alignment.topLeft, end: Alignment.bottomRight
                            ),
                            shape: BoxShape.circle,
                            boxShadow: [
                              BoxShadow(color: AppColors.primary.withOpacity(0.3), blurRadius: 8, offset: const Offset(0, 4))
                            ],
                            border: Border.all(color: Colors.white, width: 2),
                          ),
                          child: Center(
                            child: Text(
                              _firstName, // Huruf Depan Nama
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white
                              ),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                // =================================================
                // 2. SEARCH BAR (FLOATING GLASS)
                // =================================================
                SliverPersistentHeader(
                  pinned: true, // Sticky saat scroll
                  delegate: _SliverSearchDelegate(
                    child: Container(
                      color: const Color(0xFFF9FAFB), // Samakan background
                      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                      child: Container(
                        height: 50,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          boxShadow: [
                            BoxShadow(color: Colors.grey.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 5)),
                          ],
                          border: Border.all(color: Colors.grey.shade200),
                        ),
                        child: TextField(
                          decoration: InputDecoration(
                            prefixIcon: const Icon(Icons.search_rounded, color: AppColors.primary),
                            hintText: "Cari makan apa hari ini?",
                            hintStyle: GoogleFonts.plusJakartaSans(color: Colors.grey.shade400, fontSize: 14),
                            border: InputBorder.none,
                            contentPadding: const EdgeInsets.symmetric(vertical: 14),
                          ),
                        ),
                      ),
                    ),
                  ),
                ),

                // =================================================
                // 3. BANNER SLIDER (SCALE EFFECT)
                // =================================================
                SliverToBoxAdapter(
                  child: Column(
                    children: [
                      const SizedBox(height: 10),
                      if (homeProvider.banners.isEmpty)
                         _buildEmptyState("Belum ada promo")
                      else
                        SizedBox(
                          height: 160,
                          child: PageView.builder(
                            controller: _pageController,
                            itemCount: homeProvider.banners.length,
                            onPageChanged: (index) => setState(() => _currentBannerIndex = index),
                            itemBuilder: (context, index) {
                              // Efek Scale (Yang aktif besar, yang samping kecil)
                              return AnimatedBuilder(
                                animation: _pageController,
                                builder: (context, child) {
                                  double value = 1.0;
                                  if (_pageController.position.haveDimensions) {
                                    value = _pageController.page! - index;
                                    value = (1 - (value.abs() * 0.1)).clamp(0.9, 1.0);
                                  }
                                  return Center(
                                    child: SizedBox(
                                      height: Curves.easeOut.transform(value) * 160,
                                      width: Curves.easeOut.transform(value) * 400,
                                      child: child,
                                    ),
                                  );
                                },
                                child: _buildBannerItem(homeProvider.banners[index]),
                              );
                            },
                          ),
                        ),
                        
                        const SizedBox(height: 12),
                        // Dots Indicator
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: List.generate(homeProvider.banners.length, (index) {
                            return AnimatedContainer(
                              duration: const Duration(milliseconds: 300),
                              margin: const EdgeInsets.symmetric(horizontal: 3),
                              height: 6,
                              width: _currentBannerIndex == index ? 20 : 6,
                              decoration: BoxDecoration(
                                color: _currentBannerIndex == index ? AppColors.primary : Colors.grey.shade300,
                                borderRadius: BorderRadius.circular(3),
                              ),
                            );
                          }),
                        ),
                    ],
                  ).animate().fadeIn(),
                ),

                // =================================================
                // 4. KATEGORI MENU (CIRCLE MODERN)
                // =================================================
                SliverToBoxAdapter(
                  child: Padding(
                    padding: const EdgeInsets.all(20.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text("Eksplor Kategori", style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 16),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            _buildCategoryItem("Nasi", Icons.rice_bowl_rounded, Colors.orange),
                            _buildCategoryItem("Ayam", Icons.dinner_dining_rounded, Colors.red),
                            _buildCategoryItem("Minum", Icons.local_cafe_rounded, Colors.brown),
                            _buildCategoryItem("Snack", Icons.fastfood_rounded, Colors.blue),
                            _buildCategoryItem("Sehat", Icons.eco_rounded, Colors.green),
                          ],
                        ),
                      ],
                    ).animate().fadeIn(delay: 200.ms),
                  ),
                ),

                // =================================================
                // 5. WARUNG PILIHAN (MASONRY STYLE GRID)
                // =================================================
                SliverPadding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),
                  sliver: SliverList(
                    delegate: SliverChildListDelegate([
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text("Warung Terlaris 🔥", style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold)),
                          Text("Lihat Semua", style: GoogleFonts.plusJakartaSans(fontSize: 12, color: AppColors.primary, fontWeight: FontWeight.w600)),
                        ],
                      ),
                      const SizedBox(height: 16),
                    ]),
                  ),
                ),
                
                // GRID DYNAMIC
                if (homeProvider.merchants.isEmpty)
                   SliverToBoxAdapter(child: _buildEmptyState("Belum ada warung"))
                else
                   SliverPadding(
                      padding: const EdgeInsets.symmetric(horizontal: 20),
                      sliver: SliverGrid(
                        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: 2,
                          mainAxisSpacing: 16,
                          crossAxisSpacing: 16,
                          childAspectRatio: 0.75, // Kartu memanjang ke bawah (TikTok Style)
                        ),
                        delegate: SliverChildBuilderDelegate(
                          (context, index) {
                            return _buildPremiumMerchantCard(homeProvider.merchants[index]);
                          },
                          childCount: homeProvider.merchants.length,
                        ),
                      ),
                   ),

                const SliverToBoxAdapter(child: SizedBox(height: 100)),
              ],
            ),
          );
        },
      ),
    );
  }

  // --- HELPER WIDGETS ---

  Widget _buildBannerItem(banner) {
    String imageUrl = banner.imagePath.startsWith('http') 
        ? banner.imagePath 
        : "${ApiConstants.baseImage}${banner.imagePath}";
        
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 5),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(20),
        image: DecorationImage(image: NetworkImage(imageUrl), fit: BoxFit.cover),
        boxShadow: [
          BoxShadow(color: AppColors.primary.withOpacity(0.2), blurRadius: 15, offset: const Offset(0, 8)),
        ],
      ),
      child: Container(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(20),
          gradient: LinearGradient(
            begin: Alignment.topCenter, end: Alignment.bottomCenter,
            colors: [Colors.transparent, Colors.black.withOpacity(0.7)],
          ),
        ),
        padding: const EdgeInsets.all(16),
        alignment: Alignment.bottomLeft,
        child: Text(
          banner.title,
          style: GoogleFonts.plusJakartaSans(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16),
        ),
      ),
    );
  }

  Widget _buildCategoryItem(String label, IconData icon, Color color) {
    return GestureDetector(
      onTap: () {
        // Navigasi ke Halaman Kategori
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) =>
                CategoryProductsScreen(categoryName: label),
          ),
        );
      },
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(14),
            decoration: BoxDecoration(
              color: Colors.white,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: Colors.grey.withOpacity(0.1),
                  blurRadius: 5,
                  offset: const Offset(0, 3),
                ),
              ],
            ),
            child: Icon(icon, color: color, size: 24),
          ),
          const SizedBox(height: 8),
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 11,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPremiumMerchantCard(MerchantModel merchant) {
    String imageUrl = (merchant.image != null && merchant.image!.startsWith('http'))
        ? merchant.image!
        : "${ApiConstants.baseImage}${merchant.image}";

    return GestureDetector(
      onTap: () {
        Navigator.push(context, MaterialPageRoute(builder: (_) => MerchantDetailScreen(merchant: merchant)));
      },
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(color: Colors.grey.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 4)),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // GAMBAR
            Expanded(
              flex: 4,
              child: Stack(
                children: [
                  ClipRRect(
                    borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
                    child: Image.network(
                      imageUrl, width: double.infinity, height: double.infinity, fit: BoxFit.cover,
                      errorBuilder: (_,__,___) => Container(color: Colors.grey.shade200, child: const Icon(Icons.store, color: Colors.grey)),
                    ),
                  ),
                  // Badge Status
                  Positioned(
                    top: 8, right: 8,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.white.withOpacity(0.9),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Text(
                        merchant.isOpen ? "Buka" : "Tutup",
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 10, fontWeight: FontWeight.bold,
                          color: merchant.isOpen ? Colors.green : Colors.red,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            // INFO
            Expanded(
              flex: 3,
              child: Padding(
                padding: const EdgeInsets.all(10.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          merchant.shopName,
                          style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.bold),
                          maxLines: 1, overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 2),
                        Text(
                          "Kantin Kampus",
                          style: GoogleFonts.plusJakartaSans(fontSize: 10, color: Colors.grey),
                        ),
                      ],
                    ),
                    Row(
                      children: [
                        const Icon(Icons.star_rounded, size: 14, color: Colors.orange),
                        const SizedBox(width: 2),
                        Text("4.8", style: GoogleFonts.plusJakartaSans(fontSize: 11, fontWeight: FontWeight.bold)),
                        const Spacer(),
                        const Icon(Icons.access_time, size: 12, color: Colors.grey),
                        const SizedBox(width: 2),
                        Text("10m", style: GoogleFonts.plusJakartaSans(fontSize: 10, color: Colors.grey)),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEmptyState(String text) {
    return Center(child: Text(text, style: const TextStyle(color: Colors.grey)));
  }
}

// --- STICKY SEARCH BAR DELEGATE ---
class _SliverSearchDelegate extends SliverPersistentHeaderDelegate {
  final Widget child;
  _SliverSearchDelegate({required this.child});
  @override
  double get minExtent => 70.0;
  @override
  double get maxExtent => 70.0;
  @override
  Widget build(BuildContext context, double shrinkOffset, bool overlapsContent) => child;
  @override
  bool shouldRebuild(_SliverSearchDelegate oldDelegate) => false;
}