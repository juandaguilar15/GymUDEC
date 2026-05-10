<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Información Física - GymUdec</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            padding: 40px;
            max-width: 700px;
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
        .info-card p { color: #333; font-size: 15px; margin: 4px 0; }
        .info-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 15px; }
        .info-item { padding: 15px; background: #f0f0f0; border-radius: 5px; }
        .info-item-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px; }
        .info-item-value { font-size: 16px; color: #1B5E46; font-weight: 600; }
        .highlight-item { background: #eef7f4; border: 1px solid #1B5E46; }
        .imc-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 600;
        }
        .imc-normal { background: #d4edda; color: #155724; }
        .imc-warning { background: #fff3cd; color: #856404; }
        .imc-danger { background: #f8d7da; color: #721c24; }
        
        .text-section { margin-bottom: 15px; padding: 15px; background: #f0f0f0; border-radius: 5px; }
        .text-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 8px; }
        .text-value { font-size: 14px; color: #333; line-height: 1.5; white-space: pre-wrap; }
        
        .permiso-tag {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            background: #1B5E46;
            color: white;
            margin-top: 10px;
        }

        .button-group { display: flex; gap: 10px; margin-top: 30px; }
        .back-btn {
            flex: 1;
            padding: 12px;
            background: #F8B803;
            color: #1B5E46;
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
            transition: all 0.3s;
        }
        .back-btn:hover { background: #e8a803; transform: translateY(-2px); }
        .timestamp { font-size: 11px; color: #999; margin-top: 10px; text-align: right; }
        
        @media (max-width: 768px) { .info-row { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Mi Ficha Médica</h1>

        <div class="info-card">
            <h3>Datos de Usuario</h3>
            <p><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Correo:</strong> {{ auth()->user()->email }}</p>
            <div class="permiso-tag">Permiso de entrenamiento: {{ $physicalInfo->permisos }}</div>
        </div>

        <div class="info-row">
            <div class="info-item">
                <div class="info-item-label">Género</div>
                <div class="info-item-value">{{ ucfirst($physicalInfo->gender) }}</div>
            </div>
            <div class="info-item">
                <div class="info-item-label">Edad</div>
                <div class="info-item-value">{{ $physicalInfo->age }} años</div>
            </div>
            <div class="info-item">
                <div class="info-item-label">Nacimiento</div>
                <div class="info-item-value">{{ $physicalInfo->date_of_birth->format('d/m/Y') }}</div>
            </div>
        </div>

        <h2 style="color: #1B5E46; font-size: 16px; margin: 20px 0 10px; font-weight: 600; border-bottom: 2px solid #F8B803; display: inline-block;">Mediciones Corporales</h2>
        <div class="info-row">
            <div class="info-item highlight-item">
                <div class="info-item-label">Altura</div>
                <div class="info-item-value">{{ $physicalInfo->height }} m</div>
            </div>
            <div class="info-item highlight-item">
                <div class="info-item-label">Peso</div>
                <div class="info-item-value">{{ $physicalInfo->weight }} kg</div>
            </div>
            <div class="info-item highlight-item">
                <div class="info-item-label">Índice (IMC)</div>
                <div class="info-item-value">
                    @php
                        $imc = $physicalInfo->weight / ($physicalInfo->height ** 2);
                        $formattedImc = number_format($imc, 2);
                        
                        $class = 'imc-normal';
                        $status = 'Normal';
                        if($imc < 18.5) { $class = 'imc-warning'; $status = 'Bajo peso'; }
                        else if($imc >= 25 && $imc < 30) { $class = 'imc-warning'; $status = 'Sobrepeso'; }
                        else if($imc >= 30) { $class = 'imc-danger'; $status = 'Obesidad'; }
                    @endphp
                    {{ $formattedImc }}
                    <div class="imc-badge {{ $class }}">{{ $status }}</div>
                </div>
            </div>
        </div>

        <div class="text-section">
            <div class="text-label">⚕️ Condición Médica Registrada</div>
            <div class="text-value">{{ $physicalInfo->condition ?? 'Sin condiciones especiales reportadas.' }}</div>
        </div>

        <div class="text-section">
            <div class="text-label">💡 Recomendaciones del Enfermero</div>
            <div class="text-value">{{ $physicalInfo->recommendation ?? 'Sin recomendaciones registradas.' }}</div>
        </div>

        <div class="timestamp">
            Ficha médica oficial de GymUDEC<br>
            Generado el: {{ now()->format('d/m/Y H:i') }}<br>
            Última actualización de enfermería: {{ $physicalInfo->updated_at->format('d/m/Y H:i') }}
        </div>

        <div class="button-group">
            <a href="{{ route('dashboard') }}" class="back-btn">← Volver al Dashboard</a>
        </div>
    </div>
</body>
</html>