import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../../core/app_theme.dart';
import 'home/student_home_screen.dart';
import 'profile/student_profile_screen.dart';
import 'ai_chat_screen.dart';
import 'merchant/merchant_screen.dart';
import 'order/cart_screen.dart';

class StudentDashboard extends StatefulWidget {
  const StudentDashboard({super.key});

  @override
  State<StudentDashboard> createState() => _StudentDashboardState();
}

class _StudentDashboardState extends State<StudentDashboard> {
  int _currentIndex = 0;

  // Daftar Halaman
  final List<Widget> _pages = [
    const StudentHomeScreen(),
    const MerchantScreen(),
    const CartScreen(),
    const StudentProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBody: true,
      body: _pages[_currentIndex],

      // --- FLOATING ACTION BUTTON (FAB) ---
      floatingActionButton:
          FloatingActionButton(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const AiChatScreen()),
              );
            },
            backgroundColor: AppColors
                .primary, // Warna Aksen: PINK / Magenta (sesuai referensi)
            elevation: 6, // Menaikkan elevasi agar lebih menonjol
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(20),
            ), // Bentuk RoundedRectangle untuk FAB
            child: const Icon(
              Icons.auto_awesome_rounded,
              color: Colors.white,
              size: 30,
            ),
          ).animate().scale(
            delay: 500.ms,
            curve: Curves.elasticOut,
            duration: 800.ms,
          ), // Animasi lebih smooth

      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,

      // --- BOTTOM NAVIGATION BAR CUSTOM ---
      bottomNavigationBar: BottomAppBar(
        shape: const CircularNotchedRectangle(),
        notchMargin: 10.0, // Menaikkan notchMargin agar lekukan lebih dalam
        color: AppColors.primary, // Warna Biru UTB untuk BottomAppBar
        surfaceTintColor: AppColors.primary,
        elevation: 10,
        shadowColor: Colors.black.withOpacity(
          0.4,
        ), // Bayangan lebih gelap agar kontras
        height: 68, // Tinggi bar yang sedikit lebih tinggi
        padding: EdgeInsets.zero,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            // Sisi Kiri
            _buildNavItem(Icons.home_filled, "Home", 0), // Ikon Solid Home
            _buildNavItem(Icons.store_mall_directory_rounded, "Warung", 1),

            // Spasi Kosong untuk FAB di tengah
            const SizedBox(width: 48),

            // Sisi Kanan
            _buildNavItem(
              Icons.shopping_bag_rounded,
              "Keranjang",
              2,
            ), // Ikon Keranjang (lebih cocok)
            _buildNavItem(Icons.person_rounded, "Profil", 3),
          ],
        ),
      ),
    );
  }

  // --- WIDGET ITEM NAVIGASI ---
  Widget _buildNavItem(IconData icon, String label, int index) {
    final bool isSelected = _currentIndex == index;

    return Expanded(
      child: InkWell(
        onTap: () {
          setState(() => _currentIndex = index);
        },
        customBorder: const CircleBorder(), // Efek tap jadi lingkaran
        splashColor: Colors.white.withOpacity(0.2), // Efek splash putih
        highlightColor: Colors.transparent, // Hapus highlight default
        child: Column(
          mainAxisSize: MainAxisSize.min,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              icon,
              // Jika aktif: Putih, Jika tidak: Biru Muda
              color: isSelected ? Colors.white : Colors.blue.shade200,
              size: 26,
            ),
            const SizedBox(height: 4),
            Text(
              label,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 10,
                fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                color: isSelected ? Colors.white : Colors.blue.shade200,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
