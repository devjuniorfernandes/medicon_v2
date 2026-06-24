import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';
import '../models/hospital.dart';
import '../models/specialty.dart';
import '../services/api_service.dart';

class HospitalProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  List<Hospital> _hospitals = [];
  List<Specialty> _specialties = [];
  List<Hospital> _searchResults = [];
  bool _isLoading = false;

  List<Hospital> get hospitals => _hospitals;
  List<Specialty> get specialties => _specialties;
  List<Hospital> get searchResults => _searchResults;
  bool get isLoading => _isLoading;

  Future<void> loadInitialData() async {
    _isLoading = true;
    notifyListeners();

    try {
      _hospitals = await _apiService.fetchHospitals();
      _specialties = await _apiService.fetchSpecialties();
    } catch (e) {
      debugPrint('Error loading initial data: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> searchHospitals(
    String query,
    String? province,
    int? specialtyId,
  ) async {
    _isLoading = true;
    notifyListeners();

    try {
      _searchResults = await _apiService.searchHospitals(
        query: query,
        province: province,
        specialtyId: specialtyId,
      );
    } catch (e) {
      debugPrint('Error searching hospitals: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<Hospital?> getHospitalDetails(String slug) async {
    try {
      return await _apiService.fetchHospitalDetails(slug);
    } catch (e) {
      debugPrint('Error fetching hospital details: $e');
      return null;
    }
  }
}
