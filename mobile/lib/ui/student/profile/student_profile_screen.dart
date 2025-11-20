import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/app_theme.dart';
import '../../../core/api_constants.dart';
import '../../auth/login_screen.dart';
import '../order/order_history_screen.dart'; // Import History
import 'edit_profile_screen.dart';
import 'about_app_screen.dart';

class StudentProfileScreen extends StatefulWidget {
  const StudentProfileScreen({super.key});

  @override
  State<StudentProfileScreen> createState() => _StudentProfileScreenState();
}

class _StudentProfileScreenState extends State<StudentProfileScreen> {
  String _name = "Loading...";
  String _email = "Loading...";
  String? _photoPath; // Variable untuk menampung path foto
  String _initials = "U";

  @override
  void initState() {
    super.initState();
    _loadProfile();
  }

  Future<void> _loadProfile() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _name = prefs.getString('user_name') ?? "Mahasiswa";
      _email = prefs.getString('user_email') ?? "Belum ada email";
      _photoPath = prefs.getString('user_photo'); 
      
      if (_name.isNotEmpty) {
        _initials = _name[0].toUpperCase();
      }
    });
  }

  Future<void> _logout() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text("Keluar Aplikasi?", style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold)),
        content: Text("Anda harus login kembali untuk mengakses akun.", style: GoogleFonts.plusJakartaSans()),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text("Batal")),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.red,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
            ),
            child: const Text("Keluar", style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );

    if (confirm == true) {
      final prefs = await SharedPreferences.getInstance();
      await prefs.clear();
      if (!mounted) return;
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(builder: (context) => const LoginScreen()),
        (route) => false,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    // LOGIKA GAMBAR vs INISIAL
    ImageProvider? imageProvider;
    if (_photoPath != null && _photoPath!.isNotEmpty) {
      if (_photoPath!.startsWith('http')) {
        imageProvider = NetworkImage(_photoPath!);
      } else {
        imageProvider = NetworkImage("${ApiConstants.baseImage}$_photoPath");
      }
    }

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        title: Text(
          "Profile",
          style: GoogleFonts.plusJakartaSans(
            color: AppColors.textPrimary, fontWeight: FontWeight.bold, fontSize: 18
          ),
        ),
        centerTitle: true,
        automaticallyImplyLeading: false,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 20),
            
            // --- 1. HEADER PROFIL ---
            Row(
              children: [
                // Avatar
                Container(
                  width: 70, height: 70,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: AppColors.primary.withOpacity(0.1),
                    image: imageProvider != null 
                        ? DecorationImage(image: imageProvider, fit: BoxFit.cover)
                        : null,
                  ),
                  child: imageProvider == null
                      ? Center(
                          child: Text(
                            _initials,
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 28, fontWeight: FontWeight.bold, color: AppColors.primary
                            ),
                          ),
                        )
                      : null,
                ),
                const SizedBox(width: 16),
                
                // Info User
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        _name,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 18, fontWeight: FontWeight.bold, color: AppColors.textPrimary
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        _email,
                        style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey),
                      ),
                    ],
                  ),
                ),

                // Tombol Edit
                InkWell(
                  onTap: () async {
                    final result = await Navigator.push(
                      context,
                      MaterialPageRoute(builder: (context) => const EditProfileScreen()),
                    );
                    if (result == true) _loadProfile();
                  },
                  borderRadius: BorderRadius.circular(50),
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: Colors.grey.shade100, shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.edit_outlined, color: AppColors.textPrimary, size: 20),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 32),

            // --- 2. MY ACCOUNT SECTION ---
            Text("Akun Saya", style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.textPrimary)),
            const SizedBox(height: 16),

            Container(
              decoration: BoxDecoration(
                color: const Color(0xFFF9FAFB), borderRadius: BorderRadius.circular(16),
              ),
              child: Column(
                children: [
                  _buildMenuItem(Icons.location_on_outlined, "Alamat Tersimpan", Colors.pinkAccent, onTap: () {}),
                  _buildDivider(),
                  
                  // MENU RIWAYAT PESANAN
                  _buildMenuItem(Icons.history_rounded, "Riwayat Pesanan", Colors.purpleAccent, onTap: () {
                     Navigator.push(context, MaterialPageRoute(builder: (context) => const OrderHistoryScreen()));
                  }),
                  
                  _buildDivider(),
                  _buildMenuItem(Icons.payment_outlined, "Metode Pembayaran", Colors.orangeAccent, onTap: () {}),
                ],
              ),
            ),

            const SizedBox(height: 24),

             // --- 3. OTHER INFO SECTION ---
             Text("Lainnya", style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.textPrimary)),
             const SizedBox(height: 16),
            
            Container(
              decoration: BoxDecoration(
                color: const Color(0xFFF9FAFB), borderRadius: BorderRadius.circular(16),
              ),
              child: Column(
                children: [
                  _buildMenuItem(Icons.help_outline_rounded, "Pusat Bantuan", Colors.blueAccent, onTap: () {}),
                  _buildDivider(),
                  
                  // MENU TENTANG APLIKASI
                  _buildMenuItem(Icons.info_outline_rounded, "Tentang UTB Eats", Colors.teal, onTap: () {
                     Navigator.push(context, MaterialPageRoute(builder: (context) => const AboutAppScreen()));
                  }),
                ],
              ),
            ),

            const SizedBox(height: 40),

            // --- 4. LOGOUT BUTTON ---
            SizedBox(
              width: double.infinity, height: 55,
              child: ElevatedButton(
                onPressed: _logout,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFFF50057),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                child: Text(
                  "Keluar Aplikasi",
                  style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white),
                ),
              ),
            ),
            const SizedBox(height: 100),
          ],
        ),
      ),
    );
  }

  // --- WIDGET HELPERS ---

  // Parameter 'onTap' ditambahkan agar setiap menu bisa punya fungsi beda-beda
  Widget _buildMenuItem(IconData icon, String title, Color iconColor, {required VoidCallback onTap}) {
    return ListTile(
      onTap: onTap, // <--- Panggil fungsi onTap disini
      leading: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: iconColor.withOpacity(0.1), shape: BoxShape.circle,
        ),
        child: Icon(icon, color: iconColor, size: 20),
      ),
      title: Text(
        title,
        style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w600, color: AppColors.textPrimary),
      ),
      trailing: const Icon(Icons.arrow_forward_ios_rounded, size: 16, color: Colors.grey),
    );
  }

  Widget _buildDivider() {
    return const Divider(height: 1, thickness: 0.5, color: Colors.grey, indent: 60, endIndent: 20);
  }
}