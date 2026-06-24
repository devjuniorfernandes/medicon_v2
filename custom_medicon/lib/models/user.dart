class User {
  final int id;
  final String name;
  final String email;
  final String? role;
  final String? avatarUrl;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.role,
    this.avatarUrl,
  });

  bool get isHospital => role == 'hospital';

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      role: json['role'],
      avatarUrl: json['avatar_url'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'avatar_url': avatarUrl,
    };
  }
}
