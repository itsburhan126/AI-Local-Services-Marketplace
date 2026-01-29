class CategoryModel {
  final int id;
  final String name;
  final String slug;
  final String? image;
  final String? type;
  final int? parentId;

  CategoryModel({
    required this.id,
    required this.name,
    required this.slug,
    this.image,
    this.type,
    this.parentId,
  });

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      image: json['image'],
      type: json['type'],
      parentId: json['parent_id'],
    );
  }
}
