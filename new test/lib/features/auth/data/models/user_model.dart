class UserModel {
  final int? id;
  final String? name;
  final String? email;
  final String? token;
  final String? role;
  final String? mode;
  final String? serviceRule;
  final String? profilePhotoUrl;
  
  final String? companyName;
  final String? about;
  final String? address;
  final String? country;
  final List<String>? languages;
  final List<String>? skills;
  
  // New Stats Fields
  final String? createdAt;
  final String? avgResponseTime;
  final String? lastDelivery;
  final String? lastActive;
  
  // New Rating & Level Fields
  final double? rating;
  final int? totalReviews;
  final int? yearsExperience;
  final String? level;
  final bool? isOnline;

  String? get image => profilePhotoUrl;
  String? get profileImage => profilePhotoUrl;

  UserModel({
    this.id,
    this.name,
    this.email,
    this.token,
    this.role,
    this.mode,
    this.serviceRule,
    this.profilePhotoUrl,
    this.companyName,
    this.about,
    this.address,
    this.country,
    this.languages,
    this.skills,
    this.createdAt,
    this.avgResponseTime,
    this.lastDelivery,
    this.lastActive,
    this.rating,
    this.totalReviews,
    this.yearsExperience,
    this.level,
    this.isOnline,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    // Robust parsing logic to handle various API response structures
    dynamic data = json;

    // Try to extract token from various possible locations
    String? token = json['token'] ?? json['access_token'];

    if (token == null && json['data'] != null && json['data'] is Map) {
      token = json['data']['token'] ?? json['data']['access_token'];
    }

    // 1. Unwrap 'data' field if present
    if (data['data'] != null) {
      data = data['data'];
    }

    // 2. Unwrap 'user' field if present
    if (data['user'] != null) {
      data = data['user'];
    }

    // Now 'data' should contain the user properties directly

    // Extract service_rule safely
    final String? serviceRuleVal = data['service_rule'];

    // Extract provider_profile
    final providerProfile = data['provider_profile'];

    // Extract mode from provider_profile or fallback
    final String? modeVal = (providerProfile != null)
        ? providerProfile['mode']
        : (data['mode'] ?? null);

    return UserModel(
      id: data['id'],
      name: data['name'],
      email: data['email'],
      role: data['role'],
      serviceRule: serviceRuleVal,
      // Prefer service_rule, then provider_profile mode, then legacy
      mode: serviceRuleVal ?? modeVal ?? data['mode'],
      // Use extracted token or fallback to data['token'] if user object has it
      token: token ?? data['token'] ?? data['access_token'],
      profilePhotoUrl: data['profile_photo_url'],
      
      companyName: providerProfile?['company_name'],
      about: providerProfile?['about'],
      address: providerProfile?['address'],
      country: providerProfile?['country'],
      languages: _parseList(providerProfile?['languages']),
      skills: _parseList(providerProfile?['skills']),
      
      createdAt: data['created_at'],
      // Try to get from provider_profile first, then data, or default for demo
      avgResponseTime: providerProfile?['avg_response_time'] ?? data['avg_response_time'] ?? '1 Hour', 
      lastDelivery: providerProfile?['last_delivery'] ?? data['last_delivery'] ?? 'N/A',
      lastActive: providerProfile?['last_active'] ?? data['last_active'] ?? 'Online',
      
      rating: double.tryParse((providerProfile?['rating'] ?? data['rating']).toString()),
      totalReviews: int.tryParse((providerProfile?['reviews_count'] ?? data['reviews_count']).toString()),
      yearsExperience: int.tryParse((providerProfile?['years_experience'] ?? data['years_experience']).toString()),
      level: providerProfile?['level'] ?? data['level'] ?? 'New Seller',
    );
  }

  static List<String>? _parseList(dynamic list) {
    if (list == null) return null;
    if (list is List) {
      return list.map((e) => e.toString()).toList();
    }
    // Handle JSON string if necessary
    return null;
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'mode': mode,
      'service_rule': serviceRule,
      'token': token,
      'profile_photo_url': profilePhotoUrl,
      'provider_profile': {
        'company_name': companyName,
        'about': about,
        'address': address,
        'country': country,
        'languages': languages,
        'skills': skills,
      }
    };
  }

  UserModel copyWith({
    int? id,
    String? name,
    String? email,
    String? token,
    String? role,
    String? mode,
    String? serviceRule,
    String? profilePhotoUrl,
    String? companyName,
    String? about,
    String? address,
    String? country,
    List<String>? languages,
    List<String>? skills,
    String? createdAt,
    String? avgResponseTime,
    String? lastDelivery,
    String? lastActive,
    double? rating,
    int? totalReviews,
    int? yearsExperience,
    String? level,
    bool? isOnline,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      token: token ?? this.token,
      role: role ?? this.role,
      mode: mode ?? this.mode,
      serviceRule: serviceRule ?? this.serviceRule,
      profilePhotoUrl: profilePhotoUrl ?? this.profilePhotoUrl,
      companyName: companyName ?? this.companyName,
      about: about ?? this.about,
      address: address ?? this.address,
      country: country ?? this.country,
      languages: languages ?? this.languages,
      skills: skills ?? this.skills,
      createdAt: createdAt ?? this.createdAt,
      avgResponseTime: avgResponseTime ?? this.avgResponseTime,
      lastDelivery: lastDelivery ?? this.lastDelivery,
      lastActive: lastActive ?? this.lastActive,
      rating: rating ?? this.rating,
      totalReviews: totalReviews ?? this.totalReviews,
      yearsExperience: yearsExperience ?? this.yearsExperience,
      level: level ?? this.level,
      isOnline: isOnline ?? this.isOnline,
    );
  }
}
