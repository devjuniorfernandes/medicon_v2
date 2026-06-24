class MedicalRecord {
  final int? id;
  final int userId;
  final String? bloodType;
  final String? allergies;
  final String? chronicConditions;
  final String? currentMedication;
  final double? height;
  final double? weight;
  final String? emergencyContact;

  MedicalRecord({
    this.id,
    required this.userId,
    this.bloodType,
    this.allergies,
    this.chronicConditions,
    this.currentMedication,
    this.height,
    this.weight,
    this.emergencyContact,
  });

  factory MedicalRecord.fromJson(Map<String, dynamic> json) {
    return MedicalRecord(
      id: json['id'],
      userId: json['user_id'] ?? 0,
      bloodType: json['blood_type'],
      allergies: json['allergies'],
      chronicConditions: json['chronic_conditions'],
      currentMedication: json['current_medication'],
      height: json['height'] != null ? double.parse(json['height'].toString()) : null,
      weight: json['weight'] != null ? double.parse(json['weight'].toString()) : null,
      emergencyContact: json['emergency_contact'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'blood_type': bloodType,
      'allergies': allergies,
      'chronic_conditions': chronicConditions,
      'current_medication': currentMedication,
      'height': height,
      'weight': weight,
      'emergency_contact': emergencyContact,
    };
  }
}
