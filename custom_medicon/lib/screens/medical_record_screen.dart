import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/medical_record.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';

class MedicalRecordScreen extends StatefulWidget {
  const MedicalRecordScreen({super.key});

  @override
  State<MedicalRecordScreen> createState() => _MedicalRecordScreenState();
}

class _MedicalRecordScreenState extends State<MedicalRecordScreen> {
  final _formKey = GlobalKey<FormState>();
  bool _isLoading = true;
  bool _isSaving = false;

  final TextEditingController _bloodTypeController = TextEditingController();
  final TextEditingController _allergiesController = TextEditingController();
  final TextEditingController _chronicController = TextEditingController();
  final TextEditingController _medicationController = TextEditingController();
  final TextEditingController _heightController = TextEditingController();
  final TextEditingController _weightController = TextEditingController();
  final TextEditingController _emergencyController = TextEditingController();

  MedicalRecord? _record;

  @override
  void initState() {
    super.initState();
    _loadMedicalRecord();
  }

  Future<void> _loadMedicalRecord() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (!authProvider.isAuthenticated) return;

    try {
      final apiService = ApiService();
      final data = await apiService.getMedicalRecord(authProvider.token!);
      
      if (data.isNotEmpty && data['user_id'] != null) {
        _record = MedicalRecord.fromJson(data);
        _bloodTypeController.text = _record!.bloodType ?? '';
        _allergiesController.text = _record!.allergies ?? '';
        _chronicController.text = _record!.chronicConditions ?? '';
        _medicationController.text = _record!.currentMedication ?? '';
        _heightController.text = _record!.height?.toString() ?? '';
        _weightController.text = _record!.weight?.toString() ?? '';
        _emergencyController.text = _record!.emergencyContact ?? '';
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro ao carregar ficha médica: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  Future<void> _saveMedicalRecord() async {
    if (!_formKey.currentState!.validate()) return;

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (!authProvider.isAuthenticated) return;

    setState(() {
      _isSaving = true;
    });

    try {
      final apiService = ApiService();
      final data = {
        'blood_type': _bloodTypeController.text.trim(),
        'allergies': _allergiesController.text.trim(),
        'chronic_conditions': _chronicController.text.trim(),
        'current_medication': _medicationController.text.trim(),
        'height': _heightController.text.trim().isNotEmpty ? double.tryParse(_heightController.text.trim()) : null,
        'weight': _weightController.text.trim().isNotEmpty ? double.tryParse(_weightController.text.trim()) : null,
        'emergency_contact': _emergencyController.text.trim(),
      };

      await apiService.updateMedicalRecord(authProvider.token!, data);

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Ficha médica guardada com sucesso!')),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isSaving = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        appBar: AppBar(title: const Text('Ficha Médica')),
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text('Ficha Médica'),
        backgroundColor: Colors.blue[900],
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Mantenha as suas informações atualizadas para melhorar o seu atendimento.',
                style: TextStyle(color: Colors.grey),
              ),
              const SizedBox(height: 24),

              TextFormField(
                controller: _bloodTypeController,
                decoration: const InputDecoration(
                  labelText: 'Tipo Sanguíneo (Ex: O+)',
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.bloodtype),
                ),
              ),
              const SizedBox(height: 16),

              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _heightController,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: 'Altura (cm/m)',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.height),
                      ),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: TextFormField(
                      controller: _weightController,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: 'Peso (kg)',
                        border: OutlineInputBorder(),
                        prefixIcon: Icon(Icons.monitor_weight),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),

              TextFormField(
                controller: _allergiesController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Alergias (Medicamentos, Alimentos, etc)',
                  border: OutlineInputBorder(),
                  alignLabelWithHint: true,
                ),
              ),
              const SizedBox(height: 16),

              TextFormField(
                controller: _chronicController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Condições Crónicas (Asma, Diabetes, etc)',
                  border: OutlineInputBorder(),
                  alignLabelWithHint: true,
                ),
              ),
              const SizedBox(height: 16),

              TextFormField(
                controller: _medicationController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Medicação Atual (Nome, Dosagem)',
                  border: OutlineInputBorder(),
                  alignLabelWithHint: true,
                ),
              ),
              const SizedBox(height: 16),

              TextFormField(
                controller: _emergencyController,
                decoration: const InputDecoration(
                  labelText: 'Contacto de Emergência (Nome - Telefone)',
                  border: OutlineInputBorder(),
                  prefixIcon: Icon(Icons.contact_phone),
                ),
              ),
              const SizedBox(height: 32),

              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isSaving ? null : _saveMedicalRecord,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue[900],
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: _isSaving
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text('Guardar Ficha Médica', style: TextStyle(fontSize: 16)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _bloodTypeController.dispose();
    _allergiesController.dispose();
    _chronicController.dispose();
    _medicationController.dispose();
    _heightController.dispose();
    _weightController.dispose();
    _emergencyController.dispose();
    super.dispose();
  }
}
