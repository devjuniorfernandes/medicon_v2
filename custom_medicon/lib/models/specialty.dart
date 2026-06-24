class Specialty {
  final int id;
  final String name;
  final String? description;

  Specialty({required this.id, required this.name, this.description});

  factory Specialty.fromJson(Map<String, dynamic> json) {
    return Specialty(
      id: json['id'],
      name: json['name'],
      description: json['description'],
    );
  }
}
