import 'category_model.dart';

class ServiceModel {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final double price;
  final double? discountPrice;
  final String? image;
  final bool isActive;
  final String type;
  final CategoryModel? category;

  ServiceModel({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    required this.price,
    this.discountPrice,
    this.image,
    required this.isActive,
    required this.type,
    this.category,
  });

  factory ServiceModel.fromJson(Map<String, dynamic> json) {
    return ServiceModel(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      description: json['description'],
      price: (json['price'] as num).toDouble(),
      discountPrice: json['discount_price'] != null
          ? (json['discount_price'] as num).toDouble()
          : null,
      image: json['image'],
      isActive: json['is_active'] == 1 || json['is_active'] == true,
      type: json['type'] ?? 'local_service',
      category: json['category'] != null
          ? CategoryModel.fromJson(json['category'])
          : null,
    );
  }
}
