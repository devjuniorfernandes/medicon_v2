import 'specialty.dart';
import 'review.dart';

class Hospital {
  final int id;
  final String name;
  final String slug;
  final String? province;
  final String? municipality;
  final String? phone;
  final String? address;
  final String? description;
  final String? openingHours;
  final List<Specialty> specialties;
  final List<String> galleries;
  final double averageRating;
  final int totalReviews;
  final List<Review> reviews;

  Hospital({
    required this.id,
    required this.name,
    required this.slug,
    this.province,
    this.municipality,
    this.phone,
    this.address,
    this.description,
    this.openingHours,
    required this.specialties,
    required this.galleries,
    this.averageRating = 0.0,
    this.totalReviews = 0,
    this.reviews = const [],
  });

  factory Hospital.fromJson(Map<String, dynamic> json) {
    var specList = json['specialties'] as List? ?? [];
    var galList = json['galleries'] as List? ?? [];
    var revList = json['reviews'] as List? ?? [];
    double parseDouble(dynamic v) {
      if (v == null) return 0.0;
      if (v is num) return v.toDouble();
      if (v is String) return double.tryParse(v) ?? 0.0;
      return 0.0;
    }

    List<String> parseGalleries(List list) {
      return list
          .map((g) {
            if (g == null) return '';
            if (g is String) return g;
            if (g is Map && g.containsKey('image_url')) {
              return g['image_url']?.toString() ?? '';
            }
            return g.toString();
          })
          .where((s) => s.isNotEmpty)
          .toList();
    }

    return Hospital(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      province: json['province'],
      municipality: json['municipality'],
      phone: json['phone'],
      address: json['address'],
      description: json['description'],
      openingHours: json['opening_hours'],
      specialties: specList.map((s) => Specialty.fromJson(s)).toList(),
      galleries: parseGalleries(galList),
      averageRating: parseDouble(json['average_rating']),
      totalReviews: json['total_reviews'] ?? 0,
      reviews: revList.map((r) => Review.fromJson(r)).toList(),
    );
  }
}
