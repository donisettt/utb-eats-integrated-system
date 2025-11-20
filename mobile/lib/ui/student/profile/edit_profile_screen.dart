import 'dart:io';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart'; // Import Image Picker
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/app_theme.dart';
import '../../../core/api_constants.dart'; // Untuk URL gambar
import '../../../services/auth_service.dart'; // Panggil service langsung biar cepat

class EditProfileScreen extends StatefulWidget {
  const EditProfileScreen({super.key});

  @override
  State<EditProfileScreen> createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final _authService = AuthService();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  
  File? _selectedImage; // Untuk nampung gambar yang baru dipilih
  String? _currentImageUrl; // URL gambar dari database
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _loadCurrentData();
  }

  Future<void> _loadCurrentData() async {
    final prefs = await SharedPreferences.getInstance();
    setState(() {
      _nameController.text = prefs.getString('user_name') ?? "";
      _emailController.text = prefs.getString('user_email') ?? ""; // Nanti kita simpan email saat login
      _phoneController.text = prefs.getString('user_phone') ?? "";
      
      // Ambil URL foto lama jika ada
      String? photoPath = prefs.getString('user_photo');
      if (photoPath != null && photoPath.isNotEmpty) {
         _currentImageUrl = "${ApiConstants.baseImage}$photoPath";
      }
    });
  }

  // Fungsi Pilih Gambar
  Future<void> _pickImage() async {
    final picker = ImagePicker();
    
    final pickedFile = await picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 50, // Kompres kualitas foto jadi 50%
      maxWidth: 600,    // Kecilkan resolusi agar tidak terlalu lebar
    );

    if (pickedFile != null) {
      setState(() {
        _selectedImage = File(pickedFile.path);
      });
    }
  }

  Future<void> _saveProfile() async {
    setState(() => _isLoading = true);
    
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('token');

      if (token == null) throw "Token tidak ditemukan, silakan login ulang";

      // Panggil API
      final response = await _authService.updateProfile(
        token: token,
        name: _nameController.text,
        email: _emailController.text,
        phone: _phoneController.text,
        imageFile: _selectedImage,
      );

      // Update data lokal (Shared Preferences) agar tampilan langsung berubah
      final user = response['user'];
      await prefs.setString('user_name', user['name']);
      await prefs.setString('user_email', user['email']);
      await prefs.setString('user_phone', user['phone'] ?? "");
      if (user['profile_photo_path'] != null) {
        await prefs.setString('user_photo', user['profile_photo_path']);
      }

      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Profil berhasil diperbarui!"), backgroundColor: Colors.green),
      );
      
      Navigator.pop(context, true); // Kembali sukses

    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Gagal: $e"), backgroundColor: Colors.red),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.black),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          "Edit Profil",
          style: GoogleFonts.plusJakartaSans(color: AppColors.textPrimary, fontWeight: FontWeight.bold),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          children: [
            // --- FOTO PROFIL (BISA DIKLIK) ---
            GestureDetector(
              onTap: _pickImage,
              child: Stack(
                children: [
                  Container(
                    width: 100, height: 100,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: Colors.grey.shade200,
                      border: Border.all(color: Colors.grey.shade300, width: 2),
                      image: _getDecorationImage(),
                    ),
                    child: (_selectedImage == null && _currentImageUrl == null)
                        ? const Icon(Icons.person, size: 50, color: Colors.grey)
                        : null,
                  ),
                  // Ikon Kamera Kecil
                  Positioned(
                    bottom: 0, right: 0,
                    child: Container(
                      padding: const EdgeInsets.all(6),
                      decoration: BoxDecoration(
                        color: AppColors.primary,
                        shape: BoxShape.circle,
                        border: Border.all(color: Colors.white, width: 2),
                      ),
                      child: const Icon(Icons.camera_alt, color: Colors.white, size: 16),
                    ),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 30),

            _buildTextField("Nama Lengkap", _nameController, Icons.person_outline),
            const SizedBox(height: 20),
            _buildTextField("Email", _emailController, Icons.email_outlined),
            const SizedBox(height: 20),
            _buildTextField("Nomor HP", _phoneController, Icons.phone_android),
            
            const SizedBox(height: 40),
            
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: _isLoading ? null : _saveProfile,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: _isLoading 
                  ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white))
                  : Text("Simpan Perubahan", style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Helper untuk menampilkan gambar (Lokal vs Network vs Kosong)
  DecorationImage? _getDecorationImage() {
    if (_selectedImage != null) {
      return DecorationImage(image: FileImage(_selectedImage!), fit: BoxFit.cover);
    }
    if (_currentImageUrl != null) {
      return DecorationImage(image: NetworkImage(_currentImageUrl!), fit: BoxFit.cover);
    }
    return null;
  }

  Widget _buildTextField(String label, TextEditingController controller, IconData icon) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.w600, fontSize: 14)),
        const SizedBox(height: 8),
        TextField(
          controller: controller,
          decoration: InputDecoration(
            prefixIcon: Icon(icon, color: Colors.grey),
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          ),
        ),
      ],
    );
  }
}