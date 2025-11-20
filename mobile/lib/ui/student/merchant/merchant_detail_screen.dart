import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../../core/app_theme.dart';
import '../../../core/api_constants.dart';
import '../../../data/models/merchant_model.dart';
import '../../../providers/merchant_provider.dart';
import 'product_detail_screen.dart';

class MerchantDetailScreen extends StatefulWidget {
  final MerchantModel merchant;

  const MerchantDetailScreen({super.key, required this.merchant});

  @override
  State<MerchantDetailScreen> createState() => _MerchantDetailScreenState();
}

class _MerchantDetailScreenState extends State<MerchantDetailScreen> {

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      try {
        Provider.of<MerchantProvider>(context, listen: false)
            .getMenuByMerchant(widget.merchant.id);
      } catch (e) {
        debugPrint('Error loading menu: $e');
      }
    });
  }

  String formatRupiah(int price) {
    String priceStr = price.toString();
    String result = '';
    int count = 0;

    for (int i = priceStr.length - 1; i >= 0; i--) {
      result = priceStr[i] + result;
      count++;
      if (count == 3 && i > 0) {
        result = '.$result';
        count = 0;
      }
    }
    return "Rp $result";
  }

  @override
  Widget build(BuildContext context) {
    return Consumer<MerchantProvider>(
      builder: (context, provider, child) {
        return Scaffold(
          backgroundColor: Colors.white,
          body: CustomScrollView(
            slivers: [

              // =================================================
              // 1. HEADER GAMBAR PARALLAX
              // =================================================
              SliverAppBar(
                expandedHeight: 280,
                floating: false,
                pinned: true,
                backgroundColor: Colors.white,
                elevation: 0,
                leading: Container(
                  margin: const EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(color: Colors.black12, blurRadius: 8)
                    ],
                  ),
                  child: IconButton(
                    icon: const Icon(Icons.arrow_back_ios_new_rounded,
                        size: 18, color: Colors.black),
                    onPressed: () => Navigator.pop(context),
                  ),
                ),
                flexibleSpace: FlexibleSpaceBar(
                  background: Stack(
                    fit: StackFit.expand,
                    children: [
                      _buildSafeImage(widget.merchant.image),
                      Container(
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            begin: Alignment.topCenter,
                            end: Alignment.bottomCenter,
                            colors: [
                              Colors.transparent,
                              Colors.black.withOpacity(0.6)
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              // =================================================
              // 2. INFO WARUNG
              // =================================================
              SliverToBoxAdapter(
                child: Container(
                  padding: const EdgeInsets.fromLTRB(24, 8, 24, 0),
                  decoration: const BoxDecoration(
                    color: Colors.white,
                    borderRadius:
                        BorderRadius.vertical(top: Radius.circular(32)),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Center(
                        child: Container(
                          width: 40,
                          height: 4,
                          margin: const EdgeInsets.only(bottom: 20),
                          decoration: BoxDecoration(
                            color: Colors.grey.shade300,
                            borderRadius: BorderRadius.circular(2),
                          ),
                        ),
                      ),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Expanded(
                            child: Text(
                              widget.merchant.shopName,
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 24,
                                fontWeight: FontWeight.w800,
                                color: AppColors.textPrimary,
                              ),
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: widget.merchant.isOpen
                                  ? const Color(0xFFE8F5E9)
                                  : const Color(0xFFFFEBEE),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              widget.merchant.isOpen ? "BUKA" : "TUTUP",
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 12,
                                fontWeight: FontWeight.bold,
                                color: widget.merchant.isOpen
                                    ? Colors.green
                                    : Colors.red,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Text(
                        widget.merchant.description.isNotEmpty
                            ? widget.merchant.description
                            : "Kantin Kampus UTB",
                        style: GoogleFonts.plusJakartaSans(
                            color: Colors.grey, height: 1.5),
                      ),
                      const SizedBox(height: 20),

                      Row(
                        children: [
                          const Icon(Icons.star_rounded,
                              color: Colors.orange, size: 20),
                          const SizedBox(width: 4),
                          Text("4.8 (250+)",
                              style: GoogleFonts.plusJakartaSans(
                                  fontWeight: FontWeight.bold)),
                          const SizedBox(width: 16),
                          const Icon(Icons.timer_outlined,
                              color: Colors.grey, size: 18),
                          const SizedBox(width: 4),
                          Text("15 min",
                              style: GoogleFonts.plusJakartaSans(
                                  color: Colors.grey)),
                          const SizedBox(width: 16),
                          const Icon(Icons.delivery_dining,
                              color: Colors.grey, size: 18),
                          const SizedBox(width: 4),
                          Text("Free",
                              style: GoogleFonts.plusJakartaSans(
                                  color: Colors.grey)),
                        ],
                      ),

                      const SizedBox(height: 20),
                      const Divider(color: Color(0xFFF1F5F9)),
                    ],
                  ),
                ),
              ),

              // =================================================
              // 3. JUDUL MENU
              // =================================================
              SliverToBoxAdapter(
                child: Container(
                  color: Colors.white,
                  padding: const EdgeInsets.symmetric(
                      horizontal: 24, vertical: 16),
                  child: Text(
                    "Daftar Menu 🔥",
                    style: GoogleFonts.plusJakartaSans(
                        fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                ),
              ),

              // =================================================
              // 4. LIST MENU
              // =================================================

              if (provider.isLoading)
                const SliverFillRemaining(
                  hasScrollBody: false,
                  child: Center(
                      child: CircularProgressIndicator(
                    color: AppColors.secondary,
                  )),
                )
              else if (provider.menuList.isEmpty)
                SliverFillRemaining(
                  hasScrollBody: false,
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.no_food_rounded,
                            size: 60, color: Colors.grey),
                        const SizedBox(height: 16),
                        Text("Menu belum tersedia",
                            style: GoogleFonts.plusJakartaSans(
                                color: Colors.grey)),
                      ],
                    ),
                  ),
                )
              else
                SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (context, index) {
                      final product = provider.menuList[index];
                      return GestureDetector(
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => ProductDetailScreen(
                                product: product, 
                                merchantName: widget.merchant.shopName,
                              ),
                            ),
                          );
                        },
                        child: Container(
                          margin: const EdgeInsets.symmetric(
                              horizontal: 24, vertical: 8),
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(16),
                            border: Border.all(color: const Color(0xFFF1F5F9)),
                            boxShadow: [
                              BoxShadow(
                                color: Colors.grey.withOpacity(0.05),
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
                                  borderRadius: BorderRadius.circular(12),
                                  color: Colors.grey.shade100,
                                ),
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(12),
                                  child: Image(
                                    image: _getImageProvider(product.image),
                                    fit: BoxFit.cover,
                                    errorBuilder: (_, __, ___) => Icon(
                                      Icons.image_not_supported,
                                      color: Colors.grey,
                                    ),
                                  ),
                                ),
                              ),
                              const SizedBox(width: 16),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(product.name,
                                        style: GoogleFonts.plusJakartaSans(
                                            fontWeight: FontWeight.bold)),
                                    const SizedBox(height: 4),
                                    Text(
                                      product.description,
                                      style: GoogleFonts.plusJakartaSans(
                                          fontSize: 12, color: Colors.grey),
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                    const SizedBox(height: 8),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          formatRupiah(product.price),
                                          style: GoogleFonts.plusJakartaSans(
                                              fontWeight: FontWeight.bold,
                                              color: AppColors.primary),
                                        ),
                                        Container(
                                          padding: const EdgeInsets.all(6),
                                          decoration: BoxDecoration(
                                            color: AppColors.secondary,
                                            borderRadius:
                                                BorderRadius.circular(8),
                                          ),
                                          child: const Icon(Icons.add,
                                              color: Colors.white, size: 20),
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
                    },
                    childCount: provider.menuList.length,
                  ),
                ),

              const SliverToBoxAdapter(child: SizedBox(height: 80)),
            ],
          ),
        );
      },
    );
  }

  // --------------------- HELPERS ---------------------
  Widget _buildSafeImage(String? path) {
    return Image(
      image: _getImageProvider(path),
      fit: BoxFit.cover,
      errorBuilder: (_, __, ___) => Container(
        color: Colors.grey.shade300,
        child: const Icon(Icons.image, size: 50, color: Colors.grey),
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