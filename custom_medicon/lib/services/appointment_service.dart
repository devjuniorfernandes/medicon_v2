import 'dart:convert';
import 'package:http/http.dart' as http;
import '../core/constants.dart';
import '../models/appointment.dart';

class AppointmentService {
  Future<List<Appointment>> fetchAppointments(String token) async {
    final response = await http.get(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.appointmentsEndpoint}'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      final List data = jsonResponse;
      return data.map((json) => Appointment.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load appointments');
    }
  }

  Future<Appointment> createAppointment(
      String token, int hospitalId, int? specialtyId, String appointmentDate, String? notes) async {
    final response = await http.post(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.appointmentsEndpoint}'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({
        'hospital_id': hospitalId,
        'specialty_id': specialtyId,
        'appointment_date': appointmentDate,
        'notes': notes,
      }),
    );

    if (response.statusCode == 201 || response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      return Appointment.fromJson(jsonResponse['appointment']);
    } else {
      final error = json.decode(response.body);
      throw Exception(error['message'] ?? 'Failed to create appointment');
    }
  }
}
