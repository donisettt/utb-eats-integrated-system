import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../../core/app_theme.dart';
import 'home/merchant_home_screen.dart';
import 'menu/merchant_menu_screen.dart'; // Import Halaman Menu
import 'menu/add_edit_product_screen.dart'; // Import Halaman Tambah
import 'profile/merchant_profile_screen.dart';
import 'report/merchant_report_screen.dart';

class MerchantDashboard extends StatefulWidget {
  const MerchantDashboard({super.key});

  @override
  State<MerchantDashboard> createState() => _MerchantDashboardState();
}

class _MerchantDashboardState extends State<MerchantDashboard> {
  int _currentIndex = 0;

  // Daftar Halaman
  final List<Widget> _pages = [
    const MerchantHomeScreen(), // Index 0: Orderan
    const MerchantMenuScreen(), // Index 1: Produk (Menu)
    const MerchantReportScreen(),
    const MerchantProfileScreen(), // Index 3: Profil Toko
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // Extend body agar background menyatu dengan navbar
      extendBody: true,

      body: _pages[_currentIndex],

      // --- FAB TENGAH (Shortcut Tambah Produk) ---
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // Langsung buka halaman Tambah Produk
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => const AddEditProductScreen(),
            ),
          );
        },
        backgroundColor: AppColors.secondary, // Warna Aksen (Pink/Magenta)
        elevation: 4,
        shape: const CircleBorder(),
        child: const Icon(Icons.add_rounded, color: Colors.white, size: 32),
      ).animate().scale(delay: 500.ms, curve: Curves.elasticOut),

      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,

      // --- BOTTOM NAVIGATION BAR ---
      bottomNavigationBar: BottomAppBar(
        shape: const CircularNotchedRectangle(),
        notchMargin: 10.0, // Lekukan yang dalam
        color: Colors.white,
        surfaceTintColor: Colors.white,
        elevation: 20,
        shadowColor: Colors.black26,
        height: 65,
        padding: EdgeInsets.zero,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            // --- SISI KIRI ---
            _buildNavItem(
              Icons.dashboard_rounded,
              Icons.dashboard_outlined,
              "Orderan",
              0,
            ),
            _buildNavItem(
              Icons.restaurant_menu_rounded,
              Icons.restaurant_menu_outlined,
              "Produk",
              1,
            ), // <--- MENU PRODUK
            // Spasi untuk FAB
            const SizedBox(width: 40),

            // --- SISI KANAN ---
            _buildNavItem(
              Icons.analytics_rounded,
              Icons.analytics_outlined,
              "Laporan",
              2,
            ),
            _buildNavItem(
              Icons.storefront_rounded,
              Icons.storefront_outlined,
              "Toko",
              3,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNavItem(
    IconData activeIcon,
    IconData inactiveIcon,
    String label,
    int index,
  ) {
    final isSelected = _currentIndex == index;
    return Expanded(
      child: InkWell(
        onTap: () => setState(() => _currentIndex = index),
        // Efek sentuhan premium
        customBorder: const CircleBorder(),
        splashColor: AppColors.primary.withOpacity(0.1),

        child: Column(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Ikon dengan animasi transisi warna
            AnimatedContainer(
              duration: const Duration(milliseconds: 200),
              padding: const EdgeInsets.all(5),
              decoration: BoxDecoration(
                color: isSelected
                    ? AppColors.primary.withOpacity(0.1)
                    : Colors.transparent,
                shape: BoxShape.circle,
              ),
              child: Icon(
                isSelected ? activeIcon : inactiveIcon,
                color: isSelected ? AppColors.primary : Colors.grey.shade400,
                size: 24,
              ),
            ),

            // Label hanya muncul jika aktif (Opsional, biar bersih)
            if (isSelected)
              Text(
                label,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                  color: AppColors.primary,
                ),
              ).animate().fadeIn(),
          ],
        ),
      ),
    );
  }
}
