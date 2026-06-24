import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:http/http.dart' as http;
import '../models/hospital.dart';
import '../models/specialty.dart';
import '../providers/auth_provider.dart';
import '../services/appointment_service.dart';
import '../core/constants.dart';

class AppointmentBookingScreen extends StatefulWidget {
  final Hospital hospital;

  const AppointmentBookingScreen({super.key, required this.hospital});

  @override
  State<AppointmentBookingScreen> createState() =>
      _AppointmentBookingScreenState();
}

class _AppointmentBookingScreenState extends State<AppointmentBookingScreen> {
  final _formKey = GlobalKey<FormState>();
  final _appointmentService = AppointmentService();

  Specialty? _selectedSpecialty;
  DateTime? _selectedDate;
  // removed unused _selectedTime
  final _notesController = TextEditingController();
  bool _isLoading = false;
  bool _isLoadingSlots = false;
  List<String> _availableSlots = [];
  String? _selectedSlot;

  @override
  void dispose() {
    _notesController.dispose();
    super.dispose();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now().add(const Duration(days: 1)),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 90)),
    );
    if (picked != null && picked != _selectedDate) {
      setState(() {
        _selectedDate = picked;
        _selectedSlot = null;
        _availableSlots = [];
      });
      _fetchAvailableSlots(picked);
    }
  }

  Future<void> _fetchAvailableSlots(DateTime date) async {
    setState(() {
      _isLoadingSlots = true;
    });

    final String formattedDate =
        "${date.year.toString().padLeft(4, '0')}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2, '0')}";
    final authProvider = Provider.of<AuthProvider>(context, listen: false);

    try {
      final response = await http.get(
        Uri.parse(
          '${ApiConstants.baseUrl}/hospitals/${widget.hospital.id}/available-slots?date=$formattedDate',
        ),
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ${authProvider.token ?? ''}',
        },
      );

      if (response.statusCode == 200) {
        final List data = json.decode(response.body);
        setState(() {
          _availableSlots = data.cast<String>();
        });
      } else {
        throw Exception(
          'Failed to load slots: ${response.statusCode} - ${response.body}',
        );
      }
    } catch (e) {
      print('Erro ao carregar vagas: $e');
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Erro ao carregar vagas: $e')));
      }
    } finally {
      setState(() {
        _isLoadingSlots = false;
      });
    }
  }

  // _selectTime removed — using available slots ChoiceChip instead

  void _bookAppointment() async {
    if (_formKey.currentState!.validate() &&
        _selectedDate != null &&
        _selectedSlot != null) {
      setState(() {
        _isLoading = true;
      });

      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      if (authProvider.token == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Por favor, inicie sessão primeiro.')),
        );
        setState(() {
          _isLoading = false;
        });
        return;
      }

      // Format as YYYY-MM-DD HH:MM:SS for Laravel
      final String formattedDate =
          "${_selectedDate!.year.toString().padLeft(4, '0')}-${_selectedDate!.month.toString().padLeft(2, '0')}-${_selectedDate!.day.toString().padLeft(2, '0')} $_selectedSlot:00";

      try {
        await _appointmentService.createAppointment(
          authProvider.token!,
          widget.hospital.id,
          _selectedSpecialty?.id,
          formattedDate,
          _notesController.text,
        );

        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Consulta agendada com sucesso!')),
          );
          Navigator.pop(context); // Go back to hospital details
        }
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(e.toString().replaceAll('Exception: ', ''))),
          );
        }
      } finally {
        if (mounted) {
          setState(() {
            _isLoading = false;
          });
        }
      }
    } else {
      if (_selectedDate == null || _selectedSlot == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Por favor, selecione uma data e hora.'),
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Agendar Consulta')),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Form(
          key: _formKey,
          child: SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Text(
                  widget.hospital.name,
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                  textAlign: TextAlign.center,
                ),
                const SizedBox(height: 32),

                // Specialty Dropdown
                DropdownButtonFormField<Specialty>(
                  decoration: const InputDecoration(
                    labelText: 'Especialidade',
                    border: OutlineInputBorder(),
                    prefixIcon: Icon(Icons.medical_services),
                  ),
                  initialValue: _selectedSpecialty,
                  items: widget.hospital.specialties.map((Specialty specialty) {
                    return DropdownMenuItem<Specialty>(
                      value: specialty,
                      child: Text(specialty.name),
                    );
                  }).toList(),
                  onChanged: (Specialty? newValue) {
                    setState(() {
                      _selectedSpecialty = newValue;
                    });
                  },
                  validator: (value) => value == null
                      ? 'Por favor selecione uma especialidade'
                      : null,
                ),
                const SizedBox(height: 16),

                // Date Picker
                ListTile(
                  contentPadding: EdgeInsets.zero,
                  title: Text(
                    _selectedDate == null
                        ? 'Selecionar Data'
                        : 'Data: ${_selectedDate!.day}/${_selectedDate!.month}/${_selectedDate!.year}',
                  ),
                  leading: const Icon(Icons.calendar_today, color: Colors.blue),
                  shape: RoundedRectangleBorder(
                    side: const BorderSide(color: Colors.grey, width: 1),
                    borderRadius: BorderRadius.circular(4),
                  ),
                  onTap: () => _selectDate(context),
                ),
                const SizedBox(height: 16),
                // Time Slots (Instead of TimePicker)
                if (_isLoadingSlots)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.all(16.0),
                      child: CircularProgressIndicator(),
                    ),
                  )
                else if (_selectedDate != null && _availableSlots.isEmpty)
                  const Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Text(
                      'Não existem vagas disponíveis para esta data.',
                      style: TextStyle(color: Colors.red),
                    ),
                  )
                else if (_selectedDate != null && _availableSlots.isNotEmpty)
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Selecione uma hora:',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Wrap(
                        spacing: 8.0,
                        runSpacing: 8.0,
                        children: _availableSlots.map((slot) {
                          return ChoiceChip(
                            label: Text(
                              slot,
                              overflow: TextOverflow.ellipsis,
                              maxLines: 1,
                            ),
                            selected: _selectedSlot == slot,
                            onSelected: (selected) {
                              setState(() {
                                _selectedSlot = selected ? slot : null;
                              });
                            },
                          );
                        }).toList(),
                      ),
                    ],
                  ),
                const SizedBox(height: 16),

                // Notes Field
                TextFormField(
                  controller: _notesController,
                  maxLines: 3,
                  decoration: const InputDecoration(
                    labelText: 'Notas (Opcional)',
                    alignLabelWithHint: true,
                    border: OutlineInputBorder(),
                    prefixIcon: Padding(
                      padding: EdgeInsets.only(bottom: 40.0),
                      child: Icon(Icons.notes),
                    ),
                  ),
                ),
                const SizedBox(height: 32),

                // Submit Button
                ElevatedButton(
                  onPressed: _isLoading ? null : _bookAppointment,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  child: _isLoading
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          'Confirmar Agendamento',
                          style: TextStyle(fontSize: 16),
                        ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
