class TagModel {
  final int id;
  final String name;
  final String slug;

  TagModel({required this.id, required this.name, required this.slug});

  factory TagModel.fromJson(Map<String, dynamic> json) {
    return TagModel(id: json['id'], name: json['name'], slug: json['slug']);
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name, 'slug': slug};
  }
}
