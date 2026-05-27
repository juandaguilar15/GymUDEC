@extends('layouts.admin')

@section('title', 'Asignar Rutina - GymUdec')
@section('page-title', '📋 Asignar Rutina a Estudiante')
@section('page-subtitle', 'Selecciona la rutina y el estudiante antes de guardar la asignación.')
@section('page-actions')
    <a href="{{ route('rutinas.index') }}" class="btn-tertiary">← Volver al listado</a>
@endsection

@push('head')
<style>
    .select-table {
        width: 100%;
        border-collapse: collapse;
    }
    .select-table th,
    .select-table td {
        padding: 0.85rem 1rem;
        border: 1px solid #e5e7eb;
        text-align: left;
    }
    .select-table th {
        background: #ecfdf5;
        color: #065f46;
        font-weight: 700;
    }
    .select-table tbody tr:hover {
        background: #f8fafc;
    }
    .selected-row {
        background: #d1fae5 !important;
    }
    .select-btn {
        border-radius: 1rem;
        background: #047857;
        color: #ffffff;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .select-btn:hover {
        background: #065f46;
    }
    .view-btn {
        border-radius: 1rem;
        background: #2d6a8f;
        color: #ffffff;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
        margin-left: 0.5rem;
    }
    .view-btn:hover {
        background: #1e4d6b;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .selected-info {
        border-radius: 1.5rem;
        border: 1px solid #d1fae5;
        background: #ecfdf5;
        padding: 1rem;
        color: #334155;
        margin-bottom: 1rem;
    }
    .physical-info-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .physical-info-modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #ecfdf5;
    }
    .modal-header h3 {
        margin: 0;
        color: #065f46;
    }
    .modal-close {
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s ease;
    }
    .modal-close:hover {
        background: #b91c1c;
    }
    .info-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .info-item {
        padding: 1rem;
        border-radius: 1rem;
        background: #ecfdf5;
        border: 1px solid #d1fae5;
    }
    .info-label {
        color: #065f46;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    .info-value {
        color: #334155;
        font-size: 1.125rem;
        font-weight: 500;
    }
    .no-data {
        padding: 1.5rem;
        text-align: center;
        color: #999;
        background: #f3f4f6;
        border-radius: 1rem;
    }
</style>
@endpush

@section('content')
    @if (session('error'))
        <div class="errors">
            <ul><li>{{ session('error') }}</li></ul>
        </div>
    @endif

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-card space-y-6">
        <form action="{{ route('rutinas.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" id="routine_id" name="routine_id" value="{{ old('routine_id') }}" />
            <input type="hidden" id="student_email" name="student_email" value="{{ old('student_email') }}" />

            <div>
                <h2 class="text-lg font-semibold text-emerald-950 mb-3">Seleccionar rutina</h2>
                <div class="selected-info" id="selectedRoutineInfo">
                    @if(old('routine_id'))
                        @php $selected = $routines->firstWhere('id', old('routine_id')); @endphp
                        @if($selected)
                            <strong>Rutina seleccionada:</strong> {{ $selected->name }} — {{ ucfirst($selected->objective) }} ({{ $selected->level }})
                        @else
                            <strong>Selecciona una rutina de la tabla.</strong>
                        @endif
                    @else
                        <strong>Selecciona una rutina de la tabla.</strong>
                    @endif
                </div>
                <p class="info-text">Filtra y selecciona una rutina creada por el administrador.</p>

                <input type="text" id="routineSearch" class="form-group" placeholder="Buscar rutina por nombre..." aria-label="Buscar rutina por nombre" />
                <table class="select-table" id="routineTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Objetivo</th>
                            <th>Nivel</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routines as $routine)
                            <tr data-name="{{ strtolower($routine->name) }}" data-id="{{ $routine->id }}">
                                <td>{{ $routine->name }}</td>
                                <td>{{ ucfirst($routine->objective) }}</td>
                                <td>{{ $routine->level }}</td>
                                <td><button type="button" class="select-btn" onclick="selectRoutine({{ $routine->id }}, '{{ addslashes($routine->name) }}', '{{ addslashes(ucfirst($routine->objective)) }}', '{{ $routine->level }}', this)">Seleccionar</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-emerald-950 mb-3">Seleccionar estudiante</h2>
                <div class="selected-info" id="selectedStudentInfo">
                    @if(old('student_email'))
                        @php $selectedStudent = $students->firstWhere('email', old('student_email')); @endphp
                        @if($selectedStudent)
                            <strong>Estudiante seleccionado:</strong> {{ $selectedStudent->name }} — {{ $selectedStudent->email }}
                        @else
                            <strong>Selecciona un estudiante de la tabla.</strong>
                        @endif
                    @else
                        <strong>Selecciona un estudiante de la tabla.</strong>
                    @endif
                </div>
                <p class="info-text">Filtra por nombre o correo para elegir al estudiante adecuado.</p>

                <input type="text" id="studentSearch" class="form-group" placeholder="Buscar estudiante por nombre o correo..." aria-label="Buscar estudiante" />
                <table class="select-table" id="studentTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr data-name="{{ strtolower($student->name) }}" data-email="{{ strtolower($student->email) }}">
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="select-btn" onclick="selectStudent('{{ addslashes($student->email) }}', '{{ addslashes($student->name) }}', this)">Seleccionar</button>
                                        <button type="button" class="view-btn" onclick="viewPhysicalInfo('{{ $student->email }}', '{{ addslashes($student->name) }}', event)">📋 Ver ficha</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">
                <a href="{{ route('rutinas.index') }}" class="btn-tertiary">Cancelar</a>
                <button type="submit" class="btn-primary">Asignar Rutina</button>
            </div>
        </form>
    </div>

    <!-- Modal para ver información física -->
    <div id="physicalInfoModal" class="physical-info-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📋 Información Física del Estudiante</h3>
                <button type="button" class="modal-close" onclick="closePhysicalInfoModal()">✕</button>
            </div>
            <div id="physicalInfoContent">
                <!-- El contenido se cargará aquí -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const routineSearch = document.getElementById('routineSearch');
    const routineTable = document.getElementById('routineTable');
    const selectedRoutineInfo = document.getElementById('selectedRoutineInfo');
    const routineInput = document.getElementById('routine_id');

    const studentSearch = document.getElementById('studentSearch');
    const studentTable = document.getElementById('studentTable');
    const selectedStudentInfo = document.getElementById('selectedStudentInfo');
    const studentInput = document.getElementById('student_email');

    routineSearch.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        Array.from(routineTable.querySelectorAll('tbody tr')).forEach(row => {
            const name = row.dataset.name;
            row.style.display = name.includes(filter) ? '' : 'none';
        });
    });

    studentSearch.addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        Array.from(studentTable.querySelectorAll('tbody tr')).forEach(row => {
            const name = row.dataset.name;
            const email = row.dataset.email;
            row.style.display = name.includes(filter) || email.includes(filter) ? '' : 'none';
        });
    });

    function selectRoutine(id, name, objective, level, button) {
        routineInput.value = id;
        selectedRoutineInfo.innerHTML = `<strong>Rutina seleccionada:</strong> ${name} — ${objective} (${level})`;
        routineTable.querySelectorAll('tbody tr').forEach(row => row.classList.remove('selected-row'));
        button.closest('tr').classList.add('selected-row');
    }

    function selectStudent(email, name, button) {
        studentInput.value = email;
        selectedStudentInfo.innerHTML = `<strong>Estudiante seleccionado:</strong> ${name} — ${email}`;
        studentTable.querySelectorAll('tbody tr').forEach(row => row.classList.remove('selected-row'));
        button.closest('tr').classList.add('selected-row');
    }

    function viewPhysicalInfo(email, name, event) {
        event.preventDefault();
        const modal = document.getElementById('physicalInfoModal');
        const content = document.getElementById('physicalInfoContent');
        
        content.innerHTML = '<p style="text-align: center; padding: 2rem;">Cargando información...</p>';
        modal.classList.add('active');
        
        // Realizar petición AJAX para obtener la información física
        fetch(`/api/student-physical-info/${encodeURIComponent(email)}`)
            .then(response => {
                if (!response.ok) throw new Error('No se encontró información física');
                return response.json();
            })
            .then(data => {
                renderPhysicalInfo(data, name);
            })
            .catch(error => {
                content.innerHTML = `
                    <div class="no-data">
                        <p>⚠️ No hay información física registrada para este estudiante.</p>
                        <p style="margin-top: 0.5rem; font-size: 0.875rem;">El enfermero debe registrar la información primero.</p>
                    </div>
                `;
            });
    }

    function renderPhysicalInfo(data, name) {
        const content = document.getElementById('physicalInfoContent');
        const imc = data.weight && data.height ? (data.weight / (data.height ** 2)).toFixed(2) : 'N/A';
        const imcCategory = getIMCCategory(imc);
        
        content.innerHTML = `
            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: #065f46; margin-bottom: 1rem; font-size: 1rem;">Datos personales</h4>
                <div class="info-group">
                    <div class="info-item">
                        <div class="info-label">Nombre</div>
                        <div class="info-value">${name}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Correo</div>
                        <div class="info-value" style="font-size: 0.95rem;">${data.email}</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: #065f46; margin-bottom: 1rem; font-size: 1rem;">Medidas físicas</h4>
                <div class="info-group">
                    <div class="info-item">
                        <div class="info-label">Edad</div>
                        <div class="info-value">${data.age || 'N/A'} años</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Peso</div>
                        <div class="info-value">${data.weight || 'N/A'} kg</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Altura</div>
                        <div class="info-value">${data.height || 'N/A'} m</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Género</div>
                        <div class="info-value">${data.gender ? capitalizeFirst(data.gender) : 'N/A'}</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: #065f46; margin-bottom: 1rem; font-size: 1rem;">Indicadores de salud</h4>
                <div class="info-group">
                    <div class="info-item">
                        <div class="info-label">IMC</div>
                        <div class="info-value">${imc}</div>
                        <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem;">${imcCategory}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Condición</div>
                        <div class="info-value">${data.condition || 'No reportada'}</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="color: #065f46; margin-bottom: 1rem; font-size: 1rem;">Observaciones</h4>
                <div class="info-item">
                    <div class="info-label">Recomendación</div>
                    <div class="info-value" style="font-size: 1rem;">${data.recommendation || 'Sin recomendaciones'}</div>
                </div>
            </div>

            <div style="text-align: center; padding-top: 1rem; border-top: 2px solid #ecfdf5; color: #6b7280; font-size: 0.875rem;">
                Última actualización: ${new Date(data.updated_at).toLocaleDateString('es-CO')}
            </div>
        `;
    }

    function getIMCCategory(imc) {
        const value = parseFloat(imc);
        if (isNaN(value)) return '—';
        if (value < 18.5) return '(Bajo peso)';
        if (value < 25) return '(Normal)';
        if (value < 30) return '(Sobrepeso)';
        return '(Obesidad)';
    }

    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function closePhysicalInfoModal() {
        document.getElementById('physicalInfoModal').classList.remove('active');
    }

    // Cerrar modal al hacer click fuera de él
    document.getElementById('physicalInfoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (routineInput.value) {
            const currentRoutineRow = routineTable.querySelector(`tbody tr[data-id='${routineInput.value}']`);
            if (currentRoutineRow) {
                currentRoutineRow.classList.add('selected-row');
            }
        }
        if (studentInput.value) {
            const currentStudentRow = studentTable.querySelector(`tbody tr[data-email='${studentInput.value.toLowerCase()}']`);
            if (currentStudentRow) {
                currentStudentRow.classList.add('selected-row');
            }
        }
    });
</script>
@endpush