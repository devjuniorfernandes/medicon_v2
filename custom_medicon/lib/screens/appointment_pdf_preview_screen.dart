import 'package:flutter/material.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';
import 'package:provider/provider.dart';
import '../models/appointment.dart';
import '../providers/auth_provider.dart';

class AppointmentPdfPreviewScreen extends StatelessWidget {
  final Appointment appointment;

  const AppointmentPdfPreviewScreen({super.key, required this.appointment});

  String _formatDate(String dateString) {
    try {
      final date = DateTime.parse(dateString);
      return '${date.day.toString().padLeft(2, '0')}/${date.month.toString().padLeft(2, '0')}/${date.year} ${date.hour.toString().padLeft(2, '0')}:${date.minute.toString().padLeft(2, '0')}';
    } catch (e) {
      return dateString;
    }
  }

  String _formatStatus(String status) {
    switch (status) {
      case 'pending':
        return 'Pendente';
      case 'confirmed':
        return 'Confirmada';
      case 'cancelled':
        return 'Cancelada';
      case 'completed':
        return 'Concluída';
      default:
        return status;
    }
  }

  Future<pw.Document> _generatePdf(BuildContext context) async {
    final doc = pw.Document();
    
    // Obter dados do paciente antes da construção do PDF para evitar acessos assíncronos dentro do build
    final user = Provider.of<AuthProvider>(context, listen: false).user;

    doc.addPage(
      pw.Page(
        pageFormat: PdfPageFormat.a4,
        build: (pw.Context pdfContext) {
          return pw.Column(
            crossAxisAlignment: pw.CrossAxisAlignment.start,
            children: [
              pw.Header(
                level: 0,
                child: pw.Row(
                  mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                  children: [
                    pw.Text(
                      'MEDICON',
                      style: pw.TextStyle(
                        fontSize: 24,
                        fontWeight: pw.FontWeight.bold,
                        color: PdfColors.blue900,
                      ),
                    ),
                    pw.Text(
                      'Comprovativo de Marcação',
                      style: pw.TextStyle(
                        fontSize: 16,
                        color: PdfColors.grey700,
                      ),
                    ),
                  ],
                ),
              ),
              pw.SizedBox(height: 30),
              pw.Text(
                'Detalhes do Paciente',
                style: pw.TextStyle(
                  fontSize: 18,
                  fontWeight: pw.FontWeight.bold,
                ),
              ),
              pw.Divider(),
              pw.Text('Nome: ${user?.name ?? 'Paciente'}'),
              pw.Text('Email: ${user?.email ?? ''}'),
              pw.SizedBox(height: 30),
              pw.Text(
                'Detalhes do Agendamento',
                style: pw.TextStyle(
                  fontSize: 18,
                  fontWeight: pw.FontWeight.bold,
                ),
              ),
              pw.Divider(),
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Text('Hospital/Clínica:'),
                  pw.Text(
                    appointment.hospital?.name ?? 'N/A',
                    style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                  ),
                ],
              ),
              pw.SizedBox(height: 10),
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Text('Especialidade:'),
                  pw.Text(
                    appointment.specialty?.name ?? 'Geral',
                    style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                  ),
                ],
              ),
              pw.SizedBox(height: 10),
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Text('Data e Hora:'),
                  pw.Text(
                    _formatDate(appointment.appointmentDate),
                    style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                  ),
                ],
              ),
              pw.SizedBox(height: 10),
              pw.Row(
                mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                children: [
                  pw.Text('Estado:'),
                  pw.Text(
                    _formatStatus(appointment.status),
                    style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                  ),
                ],
              ),
              if (appointment.notes != null &&
                  appointment.notes!.isNotEmpty) ...[
                pw.SizedBox(height: 20),
                pw.Text('Notas Adicionais:'),
                pw.Container(
                  padding: const pw.EdgeInsets.all(10),
                  margin: const pw.EdgeInsets.only(top: 5),
                  color: PdfColors.grey100,
                  child: pw.Text(appointment.notes!),
                ),
              ],
              pw.Spacer(),
              pw.Divider(),
              pw.Center(
                child: pw.Text(
                  'Documento gerado através da App MEDICON',
                  style: const pw.TextStyle(
                    fontSize: 10,
                    color: PdfColors.grey,
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );

    return doc;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Pré-visualização do Comprovativo'),
        backgroundColor: Colors.blue[900],
        foregroundColor: Colors.white,
      ),
      body: PdfPreview(
        build: (format) async {
          final doc = await _generatePdf(context);
          return doc.save();
        },
        pdfFileName: 'Comprovativo_Marcacao_${appointment.id}.pdf',
        canChangeOrientation: false,
        canChangePageFormat: false,
        canDebug: false,
        previewPageMargin: const EdgeInsets.all(10),
      ),
    );
  }
}
