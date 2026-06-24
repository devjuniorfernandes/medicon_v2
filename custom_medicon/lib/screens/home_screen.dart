import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/hospital_provider.dart';
import 'hospital_detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final TextEditingController _searchController = TextEditingController();
  String? _selectedProvince;
  int? _selectedSpecialtyId;
  bool _isSearching = false;

  final List<String> _provinces = [
    'Luanda',
    'Benguela',
    'Huambo',
    'Huíla',
    'Cabinda',
    // Outras províncias se necessário
  ];

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<HospitalProvider>(context, listen: false).loadInitialData();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _performSearch() {
    final provider = Provider.of<HospitalProvider>(context, listen: false);
    if (_searchController.text.isEmpty &&
        _selectedProvince == null &&
        _selectedSpecialtyId == null) {
      setState(() => _isSearching = false);
    } else {
      setState(() => _isSearching = true);
      provider.searchHospitals(
        _searchController.text,
        _selectedProvince,
        _selectedSpecialtyId,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'MEDICON',
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        backgroundColor: Colors.blue[900],
        foregroundColor: Colors.white,
      ),
      body: Consumer<HospitalProvider>(
        builder: (context, provider, child) {
          if (provider.isLoading && provider.hospitals.isEmpty) {
            return const Center(child: CircularProgressIndicator());
          }

          final hospitalsToShow = _isSearching
              ? provider.searchResults
              : provider.hospitals;

          return RefreshIndicator(
            onRefresh: () async {
              if (_isSearching) {
                _performSearch();
              } else {
                await provider.loadInitialData();
              }
            },
            child: ListView(
              padding: const EdgeInsets.all(16.0),
              children: [
                _buildSearchForm(provider),
                const SizedBox(height: 24),
                if (!_isSearching) ...[
                  const Text(
                    'Especialidades',
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    height: 40,
                    child: ListView.builder(
                      scrollDirection: Axis.horizontal,
                      itemCount: provider.specialties.length,
                      itemBuilder: (context, index) {
                        final specialty = provider.specialties[index];
                        return Container(
                          margin: const EdgeInsets.only(right: 8),
                          child: ChoiceChip(
                            label: Text(
                              specialty.name,
                              overflow: TextOverflow.ellipsis,
                              maxLines: 1,
                            ),
                            selected: _selectedSpecialtyId == specialty.id,
                            onSelected: (selected) {
                              setState(() {
                                _selectedSpecialtyId = selected
                                    ? specialty.id
                                    : null;
                              });
                              _performSearch();
                            },
                            selectedColor: Colors.blue[200],
                            backgroundColor: Colors.blue[50],
                          ),
                        );
                      },
                    ),
                  ),
                  const SizedBox(height: 24),
                ],
                Text(
                  _isSearching
                      ? 'Resultados da Pesquisa'
                      : 'Hospitais em Destaque',
                  style: const TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                if (_isSearching &&
                    hospitalsToShow.isEmpty &&
                    !provider.isLoading)
                  const Padding(
                    padding: EdgeInsets.all(32.0),
                    child: Center(child: Text('Nenhum hospital encontrado.')),
                  ),
                if (provider.isLoading && _isSearching)
                  const Center(child: CircularProgressIndicator()),
                if (!provider.isLoading || !_isSearching)
                  ...hospitalsToShow.map(
                    (hospital) => Card(
                      margin: const EdgeInsets.only(bottom: 16),
                      child: ListTile(
                        contentPadding: const EdgeInsets.all(16),
                        leading: hospital.galleries.isNotEmpty
                            ? ClipRRect(
                                borderRadius: BorderRadius.circular(8),
                                child: Image.network(
                                  hospital.galleries.first,
                                  width: 60,
                                  height: 60,
                                  fit: BoxFit.cover,
                                  errorBuilder: (c, e, s) => const Icon(
                                    Icons.local_hospital,
                                    size: 40,
                                  ),
                                ),
                              )
                            : const Icon(
                                Icons.local_hospital,
                                size: 40,
                                color: Colors.blue,
                              ),
                        title: Text(
                          hospital.name,
                          style: const TextStyle(fontWeight: FontWeight.bold),
                        ),
                        subtitle: Text(
                          '${hospital.province} - ${hospital.municipality}',
                        ),
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) =>
                                  HospitalDetailScreen(hospital: hospital),
                            ),
                          );
                        },
                      ),
                    ),
                  ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildSearchForm(HospitalProvider provider) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: const Color.fromRGBO(0, 0, 0, 0.05),
            blurRadius: 10,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Column(
        children: [
          TextField(
            controller: _searchController,
            decoration: InputDecoration(
              hintText: 'Nome do Hospital/Clínica...',
              prefixIcon: const Icon(Icons.search),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(8),
              ),
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 16,
                vertical: 0,
              ),
            ),
            onSubmitted: (_) => _performSearch(),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: DropdownButtonFormField<String>(
                  initialValue: _selectedProvince,
                  decoration: InputDecoration(
                    hintText: 'Província',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    contentPadding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 0,
                    ),
                  ),
                  items: [
                    const DropdownMenuItem(value: null, child: Text('Todas')),
                    ..._provinces.map(
                      (p) => DropdownMenuItem(value: p, child: Text(p)),
                    ),
                  ],
                  onChanged: (val) {
                    setState(() => _selectedProvince = val);
                    _performSearch();
                  },
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: DropdownButtonFormField<int>(
                  initialValue: _selectedSpecialtyId,
                  decoration: InputDecoration(
                    hintText: 'Especialidade',
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    contentPadding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 0,
                    ),
                  ),
                  items: [
                    const DropdownMenuItem(value: null, child: Text('Todas')),
                    ...provider.specialties.map(
                      (s) => DropdownMenuItem(value: s.id, child: Text(s.name)),
                    ),
                  ],
                  onChanged: (val) {
                    setState(() => _selectedSpecialtyId = val);
                    _performSearch();
                  },
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
