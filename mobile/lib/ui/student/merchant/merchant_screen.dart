import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../../../core/app_theme.dart';
import '../../../core/api_constants.dart';
import '../../../data/models/merchant_model.dart';
import '../../../providers/home_provider.dart';
import 'merchant_detail_screen.dart';

class MerchantScreen extends StatefulWidget {
  const MerchantScreen({super.key});

  @override
  State<MerchantScreen> createState() => _MerchantScreenState();
}

class _MerchantScreenState extends State<MerchantScreen> {
  final TextEditingController _searchController = TextEditingController();
  List<MerchantModel> _filteredMerchants = [];
  bool _isSearching = false;

  @override
  void initState() {
    super.initState();
    // Pastikan data termuat
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<HomeProvider>(context, listen: false);
      if (provider.merchants.isEmpty) {
        provider.getHomeData();
      } else {
        // Isi data awal
        setState(() {
          _filteredMerchants = provider.merchants;
        });
      }
    });
  }

  // Logika Pencarian Lokal (Cepat & Realtime)
  void _filterMerchants(String query, List<MerchantModel> allMerchants) {
    setState(() {
      if (query.isEmpty) {
        _filteredMerchants = allMerchants;
        _isSearching = false;
      } else {
        _isSearching = true;
        _filteredMerchants = allMerchants
            .where((merchant) =>
                merchant.shopName.toLowerCase().contains(query.toLowerCase()) ||
                merchant.description.toLowerCase().contains(query.toLowerCase()))
            .toList();
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: false,
        title: Text(
          "Jelajah Warung",
          style: GoogleFonts.plusJakartaSans(
            color: AppColors.textPrimary,
            fontWeight: FontWeight.bold,
            fontSize: 20,
          ),
        ),
        actions: [
          IconButton(
            onPressed: () {}, 
            icon: const Icon(Icons.filter_list_rounded, color: AppColors.textPrimary),
          ),
        ],
      ),
      body: Consumer<HomeProvider>(
        builder: (context, provider, child) {
          // Sinkronisasi data awal jika belum di-set (case saat pertama load)
          if (!_isSearching && _filteredMerchants.length != provider.merchants.length) {
             _filteredMerchants = provider.merchants;
          }

          if (provider.isLoading) {
            return const Center(child: CircularProgressIndicator(color: AppColors.secondary));
          }

          return Column(
            children: [
              // 1. SEARCH BAR
              Container(
                padding: const EdgeInsets.fromLTRB(20, 10, 20, 20),
                color: Colors.white,
                child: Container(
                  decoration: BoxDecoration(
                    color: const Color(0xFFF1F5F9),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: TextField(
                    controller: _searchController,
                    onChanged: (value) => _filterMerchants(value, provider.merchants),
                    decoration: InputDecoration(
                      hintText: "Cari nama warung...",
                      hintStyle: GoogleFonts.plusJakartaSans(color: Colors.grey.shade500),
                      prefixIcon: const Icon(Icons.search_rounded, color: Colors.grey),
                      border: InputBorder.none,
                      contentPadding: const EdgeInsets.symmetric(vertical: 14),
                    ),
                  ),
                ),
              ),

              // 2. LIST CONTENT
              Expanded(
                child: _filteredMerchants.isEmpty
                    ? _buildEmptyState()
                    : ListView.builder(
                        padding: const EdgeInsets.all(20),
                        itemCount: _filteredMerchants.length,
                        itemBuilder: (context, index) {
                          final merchant = _filteredMerchants[index];
                          return _buildMerchantCard(merchant)
                              .animate()
                              .fadeIn(delay: (100 * index).ms)
                              .slideX(begin: 0.1, end: 0);
                        },
                      ),
              ),
            ],
          );
        },
      ),
    );
  }

  // --- WIDGET: MERCHANT CARD PREMIUM ---
  Widget _buildMerchantCard(MerchantModel merchant) {
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => MerchantDetailScreen(merchant: merchant)),
        );
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.grey.withOpacity(0.06),
              blurRadius: 15,
              offset: const Offset(0, 5),
            ),
          ],
          border: Border.all(color: Colors.grey.shade100),
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // GAMBAR (KIRI)
            Hero(
              tag: 'merchant_list_${merchant.id}', // Tag unik beda dari home
              child: Container(
                width: 90,
                height: 90,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(12),
                  color: Colors.grey.shade200,
                  image: DecorationImage(
                    image: _getImageProvider(merchant.image),
                    fit: BoxFit.cover,
                  ),
                ),
              ),
            ),
            const SizedBox(width: 16),
            
            // INFO (KANAN)
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      // Kategori Kecil
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: AppColors.primary.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          "Kantin",
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 10, fontWeight: FontWeight.bold, color: AppColors.primary
                          ),
                        ),
                      ),
                      // Status
                      if (!merchant.isOpen)
                        Text(
                          "TUTUP",
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 10, fontWeight: FontWeight.bold, color: Colors.red
                          ),
                        ),
                    ],
                  ),
                  const SizedBox(height: 6),
                  
                  Text(
                    merchant.shopName,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16, fontWeight: FontWeight.bold, color: AppColors.textPrimary
                    ),
                    maxLines: 1, overflow: TextOverflow.ellipsis,
                  ),
                  
                  const SizedBox(height: 4),
                  Text(
                    merchant.description.isNotEmpty ? merchant.description : "Menyediakan makanan lezat.",
                    style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey.shade500),
                    maxLines: 1, overflow: TextOverflow.ellipsis,
                  ),
                  
                  const SizedBox(height: 10),
                  // Footer Info Row
                  Row(
                    children: [
                      const Icon(Icons.star_rounded, size: 16, color: Colors.orange),
                      const SizedBox(width: 4),
                      Text("4.8", style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold, fontSize: 12)),
                      const SizedBox(width: 12),
                      Icon(Icons.timer_outlined, size: 14, color: Colors.grey.shade400),
                      const SizedBox(width: 4),
                      Text("10 min", style: GoogleFonts.plusJakartaSans(fontSize: 12, color: Colors.grey)),
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

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.store_mall_directory_outlined, size: 60, color: Colors.grey.shade300),
          const SizedBox(height: 16),
          Text(
            "Warung tidak ditemukan",
            style: GoogleFonts.plusJakartaSans(
              fontSize: 16, fontWeight: FontWeight.bold, color: Colors.grey.shade600
            ),
          ),
        ],
      ),
    );
  }

  ImageProvider _getImageProvider(String? path) {
    if (path != null && path.isNotEmpty) {
      if (path.startsWith('http')) {
        return NetworkImage(path);
      }
      return NetworkImage("${ApiConstants.baseImage}$path");
    }
    return const AssetImage('assets/images/login-ill.png');
  }
}