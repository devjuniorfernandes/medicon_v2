<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprovativo de Marcação</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #1e3a8a; margin: 0; }
        .header p { color: #666; margin: 0; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 16px; border-bottom: 1px solid #ccc; padding-bottom: 5px; color: #444; }
        .row { margin-bottom: 8px; }
        .label { font-weight: bold; width: 150px; display: inline-block; }
        .value { color: #000; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .status { padding: 3px 8px; border-radius: 4px; color: white; font-weight: bold; font-size: 12px; }
        .status-pending { background-color: #f59e0b; }
        .status-confirmed { background-color: #10b981; }
        .status-cancelled { background-color: #ef4444; }
        .status-completed { background-color: #6b7280; }
    </style>
</head>
<body>

    <div class="header">
        <h1>MEDICON</h1>
        <p>Comprovativo de Marcação</p>
    </div>

    <div class="section">
        <h2>Detalhes do Paciente</h2>
        <div class="row">
            <span class="label">Nome:</span>
            <span class="value">{{ $appointment->user->name }}</span>
        </div>
        <div class="row">
            <span class="label">Email:</span>
            <span class="value">{{ $appointment->user->email }}</span>
        </div>
    </div>

    <div class="section">
        <h2>Detalhes do Agendamento</h2>
        <div class="row">
            <span class="label">Hospital/Clínica:</span>
            <span class="value">{{ $appointment->hospital->name ?? 'N/A' }}</span>
        </div>
        <div class="row">
            <span class="label">Especialidade:</span>
            <span class="value">{{ $appointment->specialty->name ?? 'Geral' }}</span>
        </div>
        <div class="row">
            <span class="label">Data e Hora:</span>
            <span class="value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="row">
            <span class="label">Estado:</span>
            <span class="value">
                @if($appointment->status === 'pending') <span class="status status-pending">Pendente</span>
                @elseif($appointment->status === 'confirmed') <span class="status status-confirmed">Confirmada</span>
                @elseif($appointment->status === 'completed') <span class="status status-completed">Concluída</span>
                @elseif($appointment->status === 'cancelled') <span class="status status-cancelled">Cancelada</span>
                @endif
            </span>
        </div>
        @if($appointment->notes)
        <div class="row" style="margin-top: 15px;">
            <span class="label">Notas Adicionais:</span>
            <div style="background: #f9fafb; padding: 10px; margin-top: 5px; border-radius: 4px;">
                {{ $appointment->notes }}
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        Documento gerado automaticamente através da plataforma MEDICON a {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
