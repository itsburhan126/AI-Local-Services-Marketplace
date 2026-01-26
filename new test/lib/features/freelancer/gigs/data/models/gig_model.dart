import 'gig_faq_model.dart';

class GigModel {
  final int? id;
  final int providerId;
  final int categoryId;
  final int? serviceTypeId;
  final String title;
  final String slug;
  final String description;
  final String? thumbnail;
  final List<String> images;
  final String? video;
  final List<String> documents;
  final List<String> tags;
  final Map<String, dynamic> metadata;
  final List<GigPackageModel> packages;
  final List<GigExtraModel> extras;
  final List<GigFaqModel> faqs;
  final String status;
  final String? adminNote;
  final bool isActive;
  final bool isFeatured;
  final int viewCount;
  final int bookingsCount;

  GigModel({
    this.id,
    required this.providerId,
    required this.categoryId,
    this.serviceTypeId,
    required this.title,
    required this.slug,
    required this.description,
    this.thumbnail,
    this.images = const [],
    this.video,
    this.documents = const [],
    this.tags = const [],
    this.metadata = const {},
    this.packages = const [],
    this.extras = const [],
    this.faqs = const [],
    this.status = 'pending',
    this.adminNote,
    this.isActive = true,
    this.isFeatured = false,
    this.viewCount = 0,
    this.bookingsCount = 0,
  });

  factory GigModel.fromJson(Map<String, dynamic> json) {
    return GigModel(
      id: int.tryParse(json['id'].toString()),
      providerId: int.tryParse(json['provider_id'].toString()) ?? 0,
      categoryId: int.tryParse(json['category_id'].toString()) ?? 0,
      serviceTypeId: json['service_type_id'] != null
          ? int.tryParse(json['service_type_id'].toString())
          : null,
      title: json['title'] ?? '',
      slug: json['slug'] ?? '',
      description: json['description'] ?? '',
      thumbnail: json['thumbnail_image'],
      images: json['images'] != null ? List<String>.from(json['images']) : [],
      video: json['video'],
      documents: json['documents'] != null
          ? List<String>.from(json['documents'])
          : [],
      tags: json['tags'] != null ? List<String>.from(json['tags']) : [],
      metadata: json['metadata'] ?? {},
      packages: json['packages'] != null
          ? (json['packages'] as List)
                .map((e) => GigPackageModel.fromJson(e))
                .toList()
          : [],
      extras: json['extras'] != null
          ? (json['extras'] as List)
                .map((e) => GigExtraModel.fromJson(e))
                .toList()
          : [],
      faqs: json['faqs'] != null
          ? (json['faqs'] as List).map((e) => GigFaqModel.fromJson(e)).toList()
          : [],
      status: json['status'] ?? 'pending',
      adminNote: json['admin_note'],
      isActive: json['is_active'] == 1 || json['is_active'] == true,
      isFeatured: json['is_featured'] == 1 || json['is_featured'] == true,
      viewCount: int.tryParse(json['view_count'].toString()) ?? 0,
      bookingsCount: int.tryParse(json['bookings_count'].toString()) ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'provider_id': providerId,
      'category_id': categoryId,
      'service_type_id': serviceTypeId,
      'title': title,
      'slug': slug,
      'description': description,
      'thumbnail_image': thumbnail,
      'images': images,
      'video': video,
      'documents': documents,
      'tags': tags,
      'metadata': metadata,
      'packages': packages.map((e) => e.toJson()).toList(),
      'extras': extras.map((e) => e.toJson()).toList(),
      'faqs': faqs.map((e) => e.toJson()).toList(),
      'status': status,
      'admin_note': adminNote,
      'is_active': isActive,
      'is_featured': isFeatured,
      'view_count': viewCount,
      'bookings_count': bookingsCount,
    };
  }
}

class GigPackageModel {
  final int? id;
  final String tier; // basic, standard, premium
  final String name;
  final String description;
  final double price;
  final int deliveryDays;
  final int revisions;
  final bool sourceCode;
  final List<String> features;

  GigPackageModel({
    this.id,
    required this.tier,
    required this.name,
    required this.description,
    required this.price,
    required this.deliveryDays,
    this.revisions = 0,
    this.sourceCode = false,
    this.features = const [],
  });

  factory GigPackageModel.fromJson(Map<String, dynamic> json) {
    return GigPackageModel(
      id: int.tryParse(json['id'].toString()),
      tier: json['tier'],
      name: json['name'],
      description: json['description'],
      price: double.parse(json['price'].toString()),
      deliveryDays: int.parse(json['delivery_days'].toString()),
      revisions: int.parse(json['revisions'].toString()),
      sourceCode: json['source_code'] == 1 || json['source_code'] == true,
      features: json['features'] != null
          ? List<String>.from(json['features'])
          : [],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'tier': tier,
      'name': name,
      'description': description,
      'price': price,
      'delivery_days': deliveryDays,
      'revisions': revisions,
      'source_code': sourceCode,
      'features': features,
    };
  }
}

class GigExtraModel {
  final int? id;
  final String title;
  final String? description;
  final double price;
  final int additionalDays;

  GigExtraModel({
    this.id,
    required this.title,
    this.description,
    required this.price,
    this.additionalDays = 0,
  });

  factory GigExtraModel.fromJson(Map<String, dynamic> json) {
    return GigExtraModel(
      id: int.tryParse(json['id'].toString()),
      title: json['title'],
      description: json['description'],
      price: double.parse(json['price'].toString()),
      additionalDays: int.parse(json['additional_days'].toString()),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'price': price,
      'additional_days': additionalDays,
    };
  }
}


