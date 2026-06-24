import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/hospital.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import 'appointment_booking_screen.dart';

class HospitalDetailScreen extends StatefulWidget {
  final Hospital hospital;

  const HospitalDetailScreen({super.key, required this.hospital});

  @override
  State<HospitalDetailScreen> createState() => _HospitalDetailScreenState();
}

class _HospitalDetailScreenState extends State<HospitalDetailScreen> {
  late Hospital _hospital;
  final ApiService _apiService = ApiService();
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _hospital = widget.hospital;
    _refreshHospital();
  }

  Future<void> _refreshHospital() async {
    try {
      final updated = await _apiService.fetchHospitalDetails(_hospital.slug);
      setState(() {
        _hospital = updated;
      });
    } catch (e) {
      // Handle error implicitly
    }
  }

  Future<void> _showReviewDialog() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (!authProvider.isAuthenticated) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Inicie sessão para avaliar o hospital.')),
      );
      return;
    }

    int rating = 5;
    final commentController = TextEditingController();

    await showDialog(
      context: context,
      builder: (dialogParentContext) {
        return StatefulBuilder(
          builder: (dialogContext, setStateDialog) {
            return AlertDialog(
              title: const Text('Deixar Avaliação'),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text('Classificação (1 a 5)'),
                  Slider(
                    value: rating.toDouble(),
                    min: 1,
                    max: 5,
                    divisions: 4,
                    label: rating.toString(),
                    onChanged: (val) {
                      setStateDialog(() {
                        rating = val.toInt();
                      });
                    },
                  ),
                  TextField(
                    controller: commentController,
                    decoration: const InputDecoration(
                      labelText: 'Comentário (opcional)',
                    ),
                    maxLines: 3,
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(dialogContext),
                  child: const Text('Cancelar'),
                ),
                ElevatedButton(
                  onPressed: () async {
                    Navigator.pop(dialogContext);
                    if (!mounted) return;
                    setState(() => _isLoading = true);
                    final token = authProvider.token;
                    try {
                      await _apiService.submitReview(
                        token!,
                        _hospital.id,
                        rating,
                        commentController.text.trim(),
                      );
                      if (!mounted) return;
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Avaliação submetida com sucesso!'),
                        ),
                      );
                      _refreshHospital();
                    } catch (e) {
                      if (mounted) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: Text(
                              e.toString().replaceAll('Exception: ', ''),
                            ),
                          ),
                        );
                      }
                    } finally {
                      if (mounted) setState(() => _isLoading = false);
                    }
                  },
                  child: const Text('Submeter'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(_hospital.name),
        backgroundColor: Colors.blue[900],
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (_hospital.galleries.isNotEmpty)
                    Image.network(
                      _hospital.galleries.first,
                      width: double.infinity,
                      height: 250,
                      fit: BoxFit.cover,
                      errorBuilder: (c, e, s) => Container(
                        width: double.infinity,
                        height: 250,
                        color: Colors.blue[100],
                        child: const Icon(
                          Icons.local_hospital,
                          size: 80,
                          color: Colors.blue,
                        ),
                      ),
                    ),
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          _hospital.name,
                          style: const TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            const Icon(
                              Icons.location_on,
                              color: Colors.grey,
                              size: 20,
                            ),
                            const SizedBox(width: 4),
                            Text(
                              '${_hospital.province ?? ''} - ${_hospital.municipality ?? ''}',
                              style: const TextStyle(
                                color: Colors.grey,
                                fontSize: 16,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            const Icon(
                              Icons.star,
                              color: Colors.orange,
                              size: 20,
                            ),
                            const SizedBox(width: 4),
                            Text(
                              '${_hospital.averageRating.toStringAsFixed(1)} (${_hospital.totalReviews} avaliações)',
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 24),

                        if (_hospital.description != null &&
                            _hospital.description!.isNotEmpty) ...[
                          const Text(
                            'Sobre a Instituição',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 8),
                          Text(_hospital.description!),
                          const SizedBox(height: 24),
                        ],

                        const Text(
                          'Especialidades Clínicas',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Wrap(
                          spacing: 8,
                          runSpacing: 4,
                          children: _hospital.specialties
                              .map(
                                (spec) => Chip(
                                  label: Text(spec.name),
                                  backgroundColor: Colors.blue[50],
                                ),
                              )
                              .toList(),
                        ),
                        const SizedBox(height: 24),

                        const Text(
                          'Contactos',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 8),
                        if (_hospital.phone != null)
                          ListTile(
                            leading: const Icon(
                              Icons.phone,
                              color: Colors.blue,
                            ),
                            title: Text(_hospital.phone!),
                            contentPadding: EdgeInsets.zero,
                          ),
                        if (_hospital.address != null)
                          ListTile(
                            leading: const Icon(Icons.map, color: Colors.blue),
                            title: Text(_hospital.address!),
                            contentPadding: EdgeInsets.zero,
                          ),
                        if (_hospital.openingHours != null)
                          ListTile(
                            leading: const Icon(
                              Icons.access_time,
                              color: Colors.blue,
                            ),
                            title: Text(_hospital.openingHours!),
                            contentPadding: EdgeInsets.zero,
                          ),
                        const SizedBox(height: 24),

                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            const Text(
                              'Avaliações',
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            Consumer<AuthProvider>(
                              builder: (context, auth, child) {
                                if (auth.user?.isHospital == true) {
                                  return const SizedBox.shrink();
                                }
                                return TextButton.icon(
                                  onPressed: _showReviewDialog,
                                  icon: const Icon(Icons.rate_review),
                                  label: const Text('Avaliar'),
                                );
                              },
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        if (_hospital.reviews.isEmpty)
                          const Text(
                            'Ainda sem avaliações.',
                            style: TextStyle(color: Colors.grey),
                          )
                        else
                          ..._hospital.reviews.map(
                            (r) => Card(
                              margin: const EdgeInsets.only(bottom: 8),
                              child: ListTile(
                                leading: r.userAvatar != null
                                    ? CircleAvatar(
                                        backgroundImage: NetworkImage(r.userAvatar!),
                                        backgroundColor: Colors.blue[100],
                                      )
                                    : CircleAvatar(
                                        backgroundColor: Colors.blue[100],
                                        child: Text(
                                          r.userName.substring(0, 1).toUpperCase(),
                                          style: TextStyle(
                                            color: Colors.blue[800],
                                            fontWeight: FontWeight.bold,
                                          ),
                                        ),
                                      ),
                                title: Row(
                                  children: [
                                    Text(
                                      r.userName,
                                      style: const TextStyle(
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                    const Spacer(),
                                    const Icon(
                                      Icons.star,
                                      color: Colors.orange,
                                      size: 16,
                                    ),
                                    Text(' ${r.rating}'),
                                  ],
                                ),
                                subtitle: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    if (r.comment != null && r.comment!.isNotEmpty)
                                      Padding(
                                        padding: const EdgeInsets.only(top: 4.0),
                                        child: Text(r.comment!),
                                      ),
                                    if (r.hospitalResponse != null && r.hospitalResponse!.isNotEmpty)
                                      Container(
                                        margin: const EdgeInsets.only(top: 8.0, left: 8.0),
                                        padding: const EdgeInsets.all(8.0),
                                        decoration: BoxDecoration(
                                          color: Colors.blue[50],
                                          borderRadius: BorderRadius.circular(8.0),
                                          border: Border(left: BorderSide(color: Colors.blue[500]!, width: 4)),
                                        ),
                                        child: Column(
                                          crossAxisAlignment: CrossAxisAlignment.start,
                                          children: [
                                            const Text('Resposta do Hospital:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.blue)),
                                            const SizedBox(height: 2),
                                            Text(r.hospitalResponse!, style: const TextStyle(fontSize: 13, color: Colors.black87)),
                                          ],
                                        ),
                                      ),
                                  ],
                                ),
                              ),
                            ),
                          ),

                        const SizedBox(height: 80), // Extra space for FAB
                      ],
                    ),
                  ),
                ],
              ),
            ),
      floatingActionButton: _isLoading
          ? null
          : Consumer<AuthProvider>(
              builder: (context, authProvider, child) {
                if (authProvider.user?.isHospital == true) {
                  return const SizedBox.shrink();
                }
                return FloatingActionButton.extended(
                  onPressed: () {
                    if (!authProvider.isAuthenticated) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text(
                            'Por favor, inicie sessão no Perfil para agendar consultas.',
                          ),
                        ),
                      );
                    } else {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) =>
                              AppointmentBookingScreen(hospital: _hospital),
                        ),
                      );
                    }
                  },
                  label: const Text(
                    'Agendar Consulta',
                    style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                  ),
                  icon: const Icon(Icons.calendar_today, color: Colors.white),
                  backgroundColor: Colors.blue[800],
                );
              },
            ),
    );
  }
}
