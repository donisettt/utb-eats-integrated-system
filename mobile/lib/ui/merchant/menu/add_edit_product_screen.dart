import 'dart:io';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import '../../../core/app_theme.dart';
import '../../../core/api_constants.dart';
import '../../../data/models/product_model.dart';
import '../../../providers/merchant_dashboard_provider.dart';

class AddEditProductScreen extends StatefulWidget {
  final ProductModel? product;

  const AddEditProductScreen({super.key, this.product});

  @override
  State<AddEditProductScreen> createState() => _AddEditProductScreenState();
}

class _AddEditProductScreenState extends State<AddEditProductScreen> {
  final _nameController = TextEditingController();
  final _priceController = TextEditingController();
  final _descController = TextEditingController();
  bool _isAvailable = true;

  File? _selectedImage;
  String? _currentImageUrl;
  int? _selectedCategoryId; // ID Kategori yang dipilih

  @override
  void initState() {
    super.initState();

    // 1. Load Daftar Kategori dari API
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<MerchantDashboardProvider>(
        context,
        listen: false,
      ).loadCategories();
    });

    // 2. Isi Form jika Edit Mode
    if (widget.product != null) {
      _nameController.text = widget.product!.name;
      _priceController.text = widget.product!.price.toString();
      _descController.text =
          widget.product!.description; // Hapus null check string
      _isAvailable = widget.product!.isAvailable;
      _currentImageUrl = widget.product!.image;
      _selectedCategoryId = widget.product!.categoryId; // Set kategori lama
    }
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 70,
    );
    if (picked != null) {
      setState(() => _selectedImage = File(picked.path));
    }
  }

  Future<void> _saveProduct() async {
    // Validasi input
    if (_nameController.text.isEmpty ||
        _priceController.text.isEmpty ||
        _selectedCategoryId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Nama, Harga, dan Kategori wajib diisi"),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    final price = int.tryParse(_priceController.text);
    if (price == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Harga harus berupa angka"),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    final provider = Provider.of<MerchantDashboardProvider>(
      context,
      listen: false,
    );

    bool success;
    if (widget.product == null) {
      // Add
      success = await provider.addProduct(
        _nameController.text,
        price,
        _descController.text,
        _selectedCategoryId!, // Kirim ID Kategori
        _isAvailable,
        _selectedImage,
      );
    } else {
      // Edit
      success = await provider.updateProduct(
        widget.product!.id,
        _nameController.text,
        price,
        _descController.text,
        _selectedCategoryId!, // Kirim ID Kategori
        _isAvailable,
        _selectedImage,
      );
    }

    if (success && mounted) {
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(
            widget.product == null ? "Produk Ditambahkan!" : "Produk Diupdate!",
          ),
          backgroundColor: Colors.green,
        ),
      );
      provider.loadMerchantProducts();
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text("Gagal menyimpan produk"),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final isEditing = widget.product != null;
    final provider = Provider.of<MerchantDashboardProvider>(context);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          isEditing ? "Edit Produk" : "Tambah Produk Baru",
          style: GoogleFonts.plusJakartaSans(
            fontWeight: FontWeight.bold,
            color: Colors.black,
          ),
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.black),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // --- GAMBAR ---
            Center(
              child: GestureDetector(
                onTap: _pickImage,
                child: Container(
                  width: 150,
                  height: 150,
                  decoration: BoxDecoration(
                    color: Colors.grey.shade100,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(
                      color: AppColors.primary.withOpacity(0.2),
                      width: 2,
                    ),
                    image: _selectedImage != null
                        ? DecorationImage(
                            image: FileImage(_selectedImage!),
                            fit: BoxFit.cover,
                          )
                        : (_currentImageUrl != null &&
                                  _currentImageUrl!.isNotEmpty
                              ? DecorationImage(
                                  image: NetworkImage(
                                    _currentImageUrl!.startsWith('http')
                                        ? _currentImageUrl!
                                        : "${ApiConstants.baseImage}$_currentImageUrl",
                                  ),
                                  fit: BoxFit.cover,
                                )
                              : null),
                  ),
                  child:
                      _selectedImage == null &&
                          (_currentImageUrl == null ||
                              _currentImageUrl!.isEmpty)
                      ? const Icon(
                          Icons.add_photo_alternate,
                          size: 50,
                          color: Colors.grey,
                        )
                      : null,
                ),
              ),
            ),
            const SizedBox(height: 12),
            Center(
              child: Text(
                isEditing
                    ? "Klik untuk ganti gambar"
                    : "Klik untuk tambah gambar",
                style: GoogleFonts.plusJakartaSans(
                  color: Colors.grey,
                  fontSize: 12,
                ),
              ),
            ),

            const SizedBox(height: 30),

            Text(
              "Detail Produk",
              style: GoogleFonts.plusJakartaSans(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
            const SizedBox(height: 16),

            _buildTextField("Nama Produk", _nameController, Icons.fastfood),
            const SizedBox(height: 16),

            // --- DROPDOWN KATEGORI ---
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: Colors.grey.shade300),
              ),
              child: DropdownButtonHideUnderline(
                child: DropdownButton<int>(
                  value: _selectedCategoryId,
                  hint: Text(
                    "Pilih Kategori",
                    style: GoogleFonts.plusJakartaSans(color: Colors.grey),
                  ),
                  isExpanded: true,
                  icon: const Icon(Icons.arrow_drop_down_rounded),
                  items: provider.categories.map((category) {
                    return DropdownMenuItem<int>(
                      value: category.id,
                      child: Text(
                        category.name,
                        style: GoogleFonts.plusJakartaSans(),
                      ),
                    );
                  }).toList(),
                  onChanged: (value) {
                    setState(() {
                      _selectedCategoryId = value;
                    });
                  },
                ),
              ),
            ),

            // -------------------------
            const SizedBox(height: 16),
            _buildTextField(
              "Harga (Rp)",
              _priceController,
              Icons.attach_money,
              keyboardType: TextInputType.number,
            ),
            const SizedBox(height: 16),
            _buildTextField(
              "Deskripsi Produk (Opsional)",
              _descController,
              Icons.description,
              maxLines: 3,
            ),

            const SizedBox(height: 24),

            Row(
              children: [
                Expanded(
                  child: Text(
                    "Produk Tersedia?",
                    style: GoogleFonts.plusJakartaSans(
                      fontWeight: FontWeight.bold,
                      fontSize: 16,
                    ),
                  ),
                ),
                Switch.adaptive(
                  value: _isAvailable,
                  onChanged: (val) => setState(() => _isAvailable = val),
                  activeColor: AppColors.primary,
                ),
              ],
            ),
            const SizedBox(height: 40),

            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: provider.isLoading ? null : _saveProduct,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: provider.isLoading
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(color: Colors.white),
                      )
                    : Text(
                        isEditing ? "Update Produk" : "Tambah Produk",
                        style: GoogleFonts.plusJakartaSans(
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTextField(
    String label,
    TextEditingController controller,
    IconData icon, {
    int maxLines = 1,
    TextInputType keyboardType = TextInputType.text,
  }) {
    return TextField(
      controller: controller,
      maxLines: maxLines,
      keyboardType: keyboardType,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, color: Colors.grey),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: Colors.grey.shade300),
        ),
        contentPadding: const EdgeInsets.symmetric(
          horizontal: 16,
          vertical: 14,
        ),
      ),
    );
  }
}
