class Review {
  final int id;
  final int rating;
  final String? comment;
  final String userName;
  final String? userAvatar;
  final String createdAt;
  final String? hospitalResponse;
  final String? respondedAt;

  Review({
    required this.id,
    required this.rating,
    this.comment,
    required this.userName,
    this.userAvatar,
    required this.createdAt,
    this.hospitalResponse,
    this.respondedAt,
  });

  factory Review.fromJson(Map<String, dynamic> json) {
    return Review(
      id: json['id'],
      rating: json['rating'],
      comment: json['comment'],
      userName: json['user_name'] ?? 'Utilizador Anónimo',
      userAvatar: json['user_avatar'],
      createdAt: json['created_at'] ?? '',
      hospitalResponse: json['hospital_response'],
      respondedAt: json['responded_at'],
    );
  }
}
