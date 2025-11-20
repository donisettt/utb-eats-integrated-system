import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../../../core/app_theme.dart';

class AboutAppScreen extends StatelessWidget {
  const AboutAppScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC), // Background premium soft grey
      extendBodyBehindAppBar: true, // Agar background bisa tembus ke atas
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: Container(
          margin: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: Colors.white,
            shape: BoxShape.circle,
            boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10)],
          ),
          child: IconButton(
            icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.black, size: 18),
            onPressed: () => Navigator.pop(context),
          ),
        ),
        title: Text(
          "Tentang Aplikasi",
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.bold, color: AppColors.textPrimary, fontSize: 16
          ),
        ),
        centerTitle: true,
      ),
      body: Stack(
        children: [
          // 1. DEKORASI BACKGROUND (Blob/Orbs)
          Positioned(
            top: -100, right: -100,
            child: Container(
              width: 300, height: 300,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.primary.withOpacity(0.05),
              ),
            ),
          ),
          Positioned(
            top: 200, left: -50,
            child: Container(
              width: 150, height: 150,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppColors.secondary.withOpacity(0.05),
              ),
            ),
          ),

          // 2. KONTEN SCROLLABLE
          SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(24, 120, 24, 40), // Top padding buat space AppBar
            physics: const BouncingScrollPhysics(),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                
                // --- APP LOGO SECTION ---
                Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: AppColors.primary.withOpacity(0.1),
                        blurRadius: 30,
                        offset: const Offset(0, 10),
                      ),
                    ],
                  ),
                  child: Image.asset('assets/images/logo.png', width: 60, height: 60),
                ).animate().scale(duration: 600.ms, curve: Curves.elasticOut),
                
                const SizedBox(height: 20),
                
                Text(
                  "UTB Eats",
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 28, fontWeight: FontWeight.w800, color: AppColors.textPrimary,
                    letterSpacing: -0.5
                  ),
                ).animate().fadeIn().slideY(begin: 0.5, end: 0),
                
                Container(
                  margin: const EdgeInsets.only(top: 8),
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: Colors.grey.shade200,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    "v1.0.0 Beta Release",
                    style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.w600, color: Colors.grey.shade600),
                  ),
                ).animate().fadeIn(delay: 200.ms),

                const SizedBox(height: 40),

                // --- DEVELOPER CARD (PREMIUM ID STYLE) ---
                Container(
                  width: double.infinity,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(24),
                    gradient: const LinearGradient(
                      colors: [Color(0xFF1E3A8A), Color(0xFF305089)],
                      begin: Alignment.topLeft, end: Alignment.bottomRight,
                    ),
                    boxShadow: [
                      BoxShadow(
                        color: const Color(0xFF305089).withOpacity(0.4),
                        blurRadius: 20,
                        offset: const Offset(0, 10),
                      ),
                    ],
                  ),
                  child: Stack(
                    children: [
                      // Dekorasi dalam kartu
                      Positioned(
                        top: -20, right: -20,
                        child: Icon(Icons.code_rounded, size: 150, color: Colors.white.withOpacity(0.05)),
                      ),
                      
                      Padding(
                        padding: const EdgeInsets.all(24.0),
                        child: Column(
                          children: [
                            Row(
                              children: [
                                // Avatar dengan Border Putih
                                Container(
                                  padding: const EdgeInsets.all(3),
                                  decoration: BoxDecoration(
                                    shape: BoxShape.circle,
                                    border: Border.all(color: Colors.white.withOpacity(0.5), width: 1),
                                  ),
                                  child: const CircleAvatar(
                                    radius: 30,
                                    backgroundImage: NetworkImage("https://avatars.githubusercontent.com/u/154044548?v=4") // Foto Developer
                                  ),
                                ),
                                const SizedBox(width: 16),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        "Doni Setiawan Wahyono",
                                        style: GoogleFonts.plusJakartaSans(
                                          fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Text(
                                        "NIM: 23552011146",
                                        style: GoogleFonts.plusJakartaSans(
                                          fontSize: 13, color: Colors.white70
                                        ),
                                      ),
                                      const SizedBox(height: 4),
                                      Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                                        decoration: BoxDecoration(
                                          color: Colors.white.withOpacity(0.2),
                                          borderRadius: BorderRadius.circular(4),
                                        ),
                                        child: Text(
                                          "Fullstack Engineer",
                                          style: GoogleFonts.plusJakartaSans(fontSize: 10, color: Colors.white, fontWeight: FontWeight.w600),
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 24),
                            const Divider(color: Colors.white24),
                            const SizedBox(height: 16),
                            
                            // Social Links Row
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceAround,
                              children: [
                                _buildSocialButton(Icons.code, "GitHub"),
                                _buildSocialButton(Icons.work_outline, "LinkedIn"),
                                _buildSocialButton(Icons.camera_alt_outlined, "Instagram"),
                                _buildSocialButton(Icons.email_outlined, "Email"),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ).animate().fadeIn(delay: 400.ms).scale(),

                const SizedBox(height: 40),

                // --- TECH STACK (MINIMALIST) ---
                Text(
                  "Built With Love & Code",
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 14, fontWeight: FontWeight.w600, color: Colors.grey.shade400
                  ),
                ),
                const SizedBox(height: 16),
                Wrap(
                  spacing: 12,
                  runSpacing: 12,
                  alignment: WrapAlignment.center,
                  children: [
                    _buildTechChip("Flutter 3", Colors.blue),
                    _buildTechChip("Laravel 12", Colors.red),
                    _buildTechChip("MySQL", Colors.orange),
                    _buildTechChip("Firebase", Colors.green),
                    _buildTechChip("Provider", Colors.purple),
                    _buildTechChip("Dio", Colors.teal),
                  ],
                ).animate().fadeIn(delay: 600.ms),

                const SizedBox(height: 60),
                
                Text(
                  "© 2025 Universitas Teknologi Bandung",
                  textAlign: TextAlign.center,
                  style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey.shade300),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  // --- WIDGET HELPERS ---

  Widget _buildSocialButton(IconData icon, String label) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: Colors.white.withOpacity(0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: Colors.white, size: 20),
        ),
        const SizedBox(height: 6),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(fontSize: 10, color: Colors.white70),
        ),
      ],
    );
  }

  Widget _buildTechChip(String label, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.grey.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, 2)),
        ],
        border: Border.all(color: Colors.grey.shade100),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 8, height: 8,
            decoration: BoxDecoration(color: color, shape: BoxShape.circle),
          ),
          const SizedBox(width: 8),
          Text(
            label,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 12, fontWeight: FontWeight.w600, color: AppColors.textPrimary
            ),
          ),
        ],
      ),
    );
  }
}