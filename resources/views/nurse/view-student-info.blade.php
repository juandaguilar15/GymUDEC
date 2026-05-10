@extends('layouts.nurse')

@section('title', 'Información del Estudiante - GymUdec')

@section('page-title', '👁️ Información del Estudiante')

@section('page-subtitle', 'Revisa el historial y datos físicos del estudiante con una vista ordenada y clara.')

@section('content')
    <div class="page-card">
        <h2>Información del Estudiante</h2>
        <p>Consulta los datos de contacto, condición actual y notas médicas del estudiante.</p>

        <div style="background: #f9f9f9; padding: 20px; border-left: 4px solid #F8B803; border-radius: 5px; margin-bottom: 25px;">
            <h3 style="color: #1B5E46; font-size: 12px; text-transform: uppercase; margin-bottom: 8px; font-weight: 600;">Información del Estudiante</h3>
            <p><strong>Correo:</strong> {{ $user->email }}</p>
            <p><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <h2>📊 Datos Físicos</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Edad</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">{{ number_format($physicalInfo->age, 0) }} años</div>
            </div>
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Género</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">{{ ucfirst($physicalInfo->gender) }}</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Fecha de Nacimiento</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">{{ $physicalInfo->date_of_birth->format('d/m/Y') }}</div>
            </div>
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Permisos</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">
                    @if($physicalInfo->permisos === 'libre')
                        ✅ Libre
                    @else
                        ⚠️ Limitado
                    @endif
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Altura</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">{{ number_format($physicalInfo->height, 2) }} m</div>
            </div>
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">Peso</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">{{ number_format($physicalInfo->weight, 1) }} kg</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 15px;">
            <div style="padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px;">IMC (Índice de Masa Corporal)</div>
                <div style="font-size: 16px; color: #1B5E46; font-weight: 600;">
                    @php
                        $imc = $physicalInfo->weight / ($physicalInfo->height ** 2);
                        $imcFormatted = number_format($imc, 1);
                        $imcCategory = '';
                        if ($imc < 18.5) $imcCategory = 'Bajo peso';
                        elseif ($imc < 25) $imcCategory = 'Normal';
                        elseif ($imc < 30) $imcCategory = 'Sobrepeso';
                        else $imcCategory = 'Obesidad';
                    @endphp
                    {{ $imcFormatted }} - {{ $imcCategory }}
                </div>
            </div>
        </div>

        @if ($physicalInfo->condition)
            <div style="margin-bottom: 15px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">⚕️ Condición Médica / Limitaciones</div>
                <div style="font-size: 14px; color: #333; line-height: 1.5; white-space: pre-wrap;">{{ $physicalInfo->condition }}</div>
            </div>
        @else
            <div style="margin-bottom: 15px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">⚕️ Condición Médica / Limitaciones</div>
                <div style="font-size: 14px; color: #999; font-style: italic;">No reporta condiciones médicas o limitaciones físicas.</div>
            </div>
        @endif

        @if ($physicalInfo->recommendation)
            <div style="margin-bottom: 15px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">💡 Recomendaciones Médicas</div>
                <div style="font-size: 14px; color: #333; line-height: 1.5; white-space: pre-wrap;">{{ $physicalInfo->recommendation }}</div>
            </div>
        @else
            <div style="margin-bottom: 15px; padding: 15px; background: #f0f0f0; border-radius: 5px;">
                <div style="font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">💡 Recomendaciones Médicas</div>
                <div style="font-size: 14px; color: #999; font-style: italic;">No hay recomendaciones médicas específicas.</div>
            </div>
        @endif

        <div style="font-size: 12px; color: #999; margin-top: 10px;">
            <strong>Última actualización:</strong> {{ $physicalInfo->updated_at->format('d/m/Y H:i') }}
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <a href="{{ route('nurse.physical-form', ['email' => $user->email]) }}" class="btn btn-primary" style="flex: 1;">✏️ Editar</a>
            <form action="{{ route('nurse.delete-info', ['email' => $user->email]) }}" method="POST" style="flex: 1; margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar la información física de este estudiante?')" style="width: 100%;">🗑️ Eliminar</button>
            </form>
            <a href="{{ route('nurse.list-students') }}" class="btn btn-secondary" style="flex: 1;">← Volver</a>
        </div>
    </div>
@endsection
