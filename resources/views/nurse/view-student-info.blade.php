<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Estudiante - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1B5E46 0%, #2a7a5e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #1B5E46;
            font-size: 24px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-card {
            background: #f9f9f9;
            padding: 20px;
            border-left: 4px solid #F8B803;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .info-card h3 {
            color: #1B5E46;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .info-card p {
            color: #333;
            font-size: 14px;
            margin: 4px 0;
        }
        
        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-item {
            padding: 15px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        
        .info-item-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-item-value {
            font-size: 16px;
            color: #1B5E46;
            font-weight: 600;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .text-section {
            margin-bottom: 15px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        
        .text-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .text-value {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            white-space: pre-wrap;
        }
        
        .no-value {
            color: #999;
            font-style: italic;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .edit-btn {
            flex: 1;
            padding: 12px;
            background: #F8B803;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: background 0.3s;
        }
        
        .edit-btn:hover {
            background: #e6a700;
        }
        
        .back-btn {
            flex: 1;
            padding: 12px;
            background: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: background 0.3s;
        }
        
        .back-btn:hover {
            background: #d0d0d0;
        }
        
        .timestamp {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .info-row {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤 {{ $user->name }}</h1>
        
        <div class="info-card">
            <h3>Información del Estudiante</h3>
            <p><strong>Correo:</strong> {{ $user->email }}</p>
            <p><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <h2 style="color: #1B5E46; font-size: 18px; margin-top: 30px; margin-bottom: 15px;">📊 Datos Físicos</h2>
        
        <div class="info-row">
            <div class="info-item">
                <div class="info-item-label">Edad</div>
                <div class="info-item-value">{{ $physicalInfo->age }} años</div>
            </div>
            <div class="info-item">
                <div class="info-item-label">Género</div>
                <div class="info-item-value">{{ ucfirst($physicalInfo->gender) }}</div>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-item">
                <div class="info-item-label">Fecha de Nacimiento</div>
                <div class="info-item-value">{{ $physicalInfo->date_of_birth->format('d/m/Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-item-label">Edad Calculada</div>
                <div class="info-item-value">{{ $physicalInfo->date_of_birth->diffInYears(now()) }} años</div>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-item">
                <div class="info-item-label">Altura</div>
                <div class="info-item-value">{{ $physicalInfo->height }} m</div>
            </div>
            <div class="info-item">
                <div class="info-item-label">Peso</div>
                <div class="info-item-value">{{ $physicalInfo->weight }} kg</div>
            </div>
        </div>
        
        <div class="info-row full-width">
            <div class="info-item">
                <div class="info-item-label">IMC (Índice de Masa Corporal)</div>
                <div class="info-item-value">
                    @php
                        $imc = $physicalInfo->weight / ($physicalInfo->height ** 2);
                        $imcFormatted = number_format($imc, 2);
                    @endphp
                    {{ $imcFormatted }}
                </div>
            </div>
        </div>
        
        @if ($physicalInfo->condition)
            <div class="text-section">
                <div class="text-label">⚕️ Condición Médica</div>
                <div class="text-value">{{ $physicalInfo->condition }}</div>
            </div>
        @endif
        
        @if ($physicalInfo->recommendation)
            <div class="text-section">
                <div class="text-label">💡 Recomendaciones</div>
                <div class="text-value">{{ $physicalInfo->recommendation }}</div>
            </div>
        @endif
        
        <div class="timestamp">
            <strong>Última actualización:</strong> {{ $physicalInfo->updated_at->format('d/m/Y H:i') }}
        </div>
        
        <div class="button-group">
            <a href="{{ route('nurse.physical-form', ['email' => $user->email]) }}" class="edit-btn">✏️ Editar</a>
            <form action="{{ route('nurse.delete-info', ['email' => $user->email]) }}" method="POST" style="flex: 1; margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="edit-btn" style="background: #e74c3c; width: 100%; margin: 0;" onclick="return confirm('¿Está seguro de que desea eliminar la información física de este estudiante?')">
                    🗑️ Eliminar
                </button>
            </form>
            <a href="{{ route('nurse.list-students') }}" class="back-btn">← Volver</a>
        </div>
    </div>
</body>
</html>
