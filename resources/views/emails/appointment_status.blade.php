<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #1e3a8a; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background-color: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; border-radius: 0 0 8px 8px; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 15px; font-weight: bold; }
        .status-confirmed { background-color: #dbeafe; color: #1e40af; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>MEDICON</h2>
        </div>
        <div class="content">
            <p>Olá <strong>{{ $appointment->user->name }}</strong>,</p>
            
            <p>O estado da sua marcação no hospital <strong>{{ $appointment->hospital->name }}</strong> foi atualizado.</p>
            
            <p>
                <strong>Data da Consulta:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') }}<br>
                <strong>Especialidade:</strong> {{ $appointment->specialty ? $appointment->specialty->name : 'Geral' }}<br>
                <strong>Novo Estado:</strong> 
                @if($appointment->status === 'confirmed')
                    <span class="status status-confirmed">Confirmada</span>
                @elseif($appointment->status === 'cancelled')
                    <span class="status status-cancelled">Cancelada</span>
                @elseif($appointment->status === 'completed')
                    <span class="status status-completed">Concluída</span>
                @else
                    {{ ucfirst($appointment->status) }}
                @endif
            </p>

            @if($appointment->status === 'confirmed')
                <p>A sua consulta está confirmada. Por favor, compareça no hospital 15 minutos antes da hora marcada.</p>
            @elseif($appointment->status === 'cancelled')
                <p>Infelizmente, a sua consulta teve de ser cancelada. Por favor, aceda à plataforma para reagendar.</p>
            @endif

            <p>Obrigado por utilizar a plataforma MEDICON.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MEDICON. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
