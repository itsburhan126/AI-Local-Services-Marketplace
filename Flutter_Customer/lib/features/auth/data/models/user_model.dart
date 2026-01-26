class UserModel {
  final int? id;
  final String? name;
  final String? email;
  final String? token;
  final String? role;
  final String? serviceRule;
  final String? profileImage;
  final bool isOnline;
  final DateTime? lastSeen;

  UserModel({
    this.id,
    this.name,
    this.email,
    this.token,
    this.role,
    this.serviceRule,
    this.profileImage,
    this.isOnline = false,
    this.lastSeen,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    // Check if data is wrapped in "data" key (common in API resources)
    final data = json['data'] ?? json;
    // User data might be directly in "data" or nested in "data.user"
    final userData = data['user'] ?? data;
    
    return UserModel(
      id: userData['id'],
      name: userData['name'],
      email: userData['email'],
      role: userData['role'],
      serviceRule: userData['service_rule'],
      profileImage: userData['profile_image'] ?? userData['image'],
      isOnline: userData['is_online'] ?? false,
      lastSeen: userData['last_seen'] != null ? DateTime.tryParse(userData['last_seen']) : null,
      token: data['token'] ?? json['token'] ?? json['access_token'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'service_rule': serviceRule,
      'profile_image': profileImage,
      'is_online': isOnline,
      'last_seen': lastSeen?.toIso8601String(),
      'token': token,
    };
  }
}
