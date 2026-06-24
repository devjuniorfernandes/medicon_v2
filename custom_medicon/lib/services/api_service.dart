import 'dart:convert';
import 'package:http/http.dart' as http;
import '../core/constants.dart';
import '../models/hospital.dart';
import '../models/specialty.dart';

class ApiService {
  Future<List<Hospital>> fetchHospitals() async {
    final response = await http.get(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.hospitalsEndpoint}'),
    );
    if (response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      final List data = jsonResponse['data'];
      return data.map((json) => Hospital.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load hospitals');
    }
  }

  Future<List<Hospital>> searchHospitals({
    String? query,
    String? province,
    int? specialtyId,
  }) async {
    var uri = Uri.parse(
      '${ApiConstants.baseUrl}${ApiConstants.searchEndpoint}',
    );
    Map<String, String> queryParams = {};
    if (query != null && query.isNotEmpty) {
      queryParams['q'] = query;
    }
    if (province != null && province.isNotEmpty) {
      queryParams['province'] = province;
    }
    if (specialtyId != null) {
      queryParams['specialty'] = specialtyId.toString();
    }

    if (queryParams.isNotEmpty) {
      uri = uri.replace(queryParameters: queryParams);
    }

    final response = await http.get(uri);
    if (response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      final List data = jsonResponse['data'];
      return data.map((json) => Hospital.fromJson(json)).toList();
    } else {
      throw Exception('Failed to search hospitals');
    }
  }

  Future<List<Specialty>> fetchSpecialties() async {
    final response = await http.get(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.specialtiesEndpoint}'),
    );
    if (response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      final List data = jsonResponse['data'];
      return data.map((json) => Specialty.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load specialties');
    }
  }

  Future<Hospital> fetchHospitalDetails(String slug) async {
    final response = await http.get(
      Uri.parse(
        '${ApiConstants.baseUrl}${ApiConstants.hospitalsEndpoint}/$slug',
      ),
    );
    if (response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      return Hospital.fromJson(jsonResponse['data']);
    } else {
      throw Exception('Failed to load hospital details');
    }
  }

  Future<void> submitReview(
    String token,
    int hospitalId,
    int rating,
    String? comment,
  ) async {
    final response = await http.post(
      Uri.parse(
        '${ApiConstants.baseUrl}${ApiConstants.hospitalsEndpoint}/$hospitalId/reviews',
      ),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({'rating': rating, 'comment': comment}),
    );

    if (response.statusCode != 201 && response.statusCode != 200) {
      final error = json.decode(response.body);
      throw Exception(error['message'] ?? 'Failed to submit review');
    }
  }

  Future<String> updateAvatar(String token, String imagePath) async {
    final uri = Uri.parse('${ApiConstants.baseUrl}/user/avatar');
    final request = http.MultipartRequest('POST', uri)
      ..headers['Authorization'] = 'Bearer $token'
      ..headers['Accept'] = 'application/json'
      ..files.add(await http.MultipartFile.fromPath('avatar', imagePath));

    final response = await request.send();
    final responseData = await response.stream.bytesToString();

    if (response.statusCode == 200) {
      final jsonResponse = json.decode(responseData);
      return jsonResponse['avatar_url'];
    } else {
      throw Exception('Failed to update avatar: $responseData');
    }
  }

  Future<void> cancelAppointment(String token, int appointmentId) async {
    final response = await http.post(
      Uri.parse('${ApiConstants.baseUrl}/appointments/$appointmentId/cancel'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    if (response.statusCode != 200) {
      final error = json.decode(response.body);
      throw Exception(error['message'] ?? 'Failed to cancel appointment');
    }
  }

  // MEDICAL RECORD
  Future<Map<String, dynamic>> getMedicalRecord(String token) async {
    final response = await http.get(
      Uri.parse('${ApiConstants.baseUrl}/user/medical-record'),
      headers: {'Accept': 'application/json', 'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final jsonResponse = jsonDecode(response.body);
      return jsonResponse;
    } else {
      throw Exception('Falha ao carregar ficha médica');
    }
  }

  Future<Map<String, dynamic>> updateMedicalRecord(
    String token,
    Map<String, dynamic> data,
  ) async {
    final response = await http.post(
      Uri.parse('${ApiConstants.baseUrl}/user/medical-record'),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode(data),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body)['medical_record'];
    } else {
      final error = jsonDecode(response.body);
      throw Exception(error['message'] ?? 'Erro ao atualizar ficha médica');
    }
  }

  Future<void> sendFcmToken(String token, String fcmToken) async {
    final response = await http.post(
      Uri.parse('${ApiConstants.baseUrl}${ApiConstants.fcmTokenEndpoint}'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({'fcm_token': fcmToken}),
    );

    if (response.statusCode != 200 && response.statusCode != 201) {
      final error = jsonDecode(response.body);
      throw Exception(error['message'] ?? 'Failed to register FCM token');
    }
  }
}
