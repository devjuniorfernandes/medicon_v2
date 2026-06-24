import 'hospital.dart';
import 'specialty.dart';

class Appointment {
  final int id;
  final int hospitalId;
  final int? specialtyId;
  final String appointmentDate;
  final String status;
  final String? notes;
  final Hospital? hospital;
  final Specialty? specialty;

  Appointment({
    required this.id,
    required this.hospitalId,
    this.specialtyId,
    required this.appointmentDate,
    required this.status,
    this.notes,
    this.hospital,
    this.specialty,
  });

  Appointment copyWith({
    int? id,
    int? hospitalId,
    int? specialtyId,
    String? appointmentDate,
    String? status,
    String? notes,
    Hospital? hospital,
    Specialty? specialty,
  }) {
    return Appointment(
      id: id ?? this.id,
      hospitalId: hospitalId ?? this.hospitalId,
      specialtyId: specialtyId ?? this.specialtyId,
      appointmentDate: appointmentDate ?? this.appointmentDate,
      status: status ?? this.status,
      notes: notes ?? this.notes,
      hospital: hospital ?? this.hospital,
      specialty: specialty ?? this.specialty,
    );
  }

  factory Appointment.fromJson(Map<String, dynamic> json) {
    return Appointment(
      id: json['id'],
      hospitalId: json['hospital_id'],
      specialtyId: json['specialty_id'],
      appointmentDate: json['appointment_date'],
      status: json['status'],
      notes: json['notes'],
      hospital: json['hospital'] != null
          ? Hospital.fromJson(json['hospital'])
          : null,
      specialty: json['specialty'] != null
          ? Specialty.fromJson(json['specialty'])
          : null,
    );
  }
}
