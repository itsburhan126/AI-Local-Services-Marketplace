class ServiceTypeModel {
  final int id;
  final String name;
  final String slug;
  final bool isActive;
  final String? code; // keeping code just in case, but optional

  ServiceTypeModel({
    required this.id,
    required this.name,
    required this.slug,
    required this.isActive,
    this.code,
  });

  factory ServiceTypeModel.fromJson(Map<String, dynamic> json) {
    return ServiceTypeModel(
      id: int.parse(json['id'].toString()),
      name: json['name'],
      slug: json['slug'] ?? '',
      isActive: json['is_active'] == 1 || json['is_active'] == true,
      code: json['code'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'is_active': isActive,
      'code': code,
    };
  }
}
