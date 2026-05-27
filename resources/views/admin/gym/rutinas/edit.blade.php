@extends('layouts.admin')

@section('title', 'Editar Asignación - GymUdec')
@section('page-title', '✏️ Editar Asignación')
@section('page-subtitle', 'Actualiza la asignación de rutina al estudiante.')
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
    .selected-info {
        border-radius: 1.5rem;
        border: 1px solid #d1fae5;
        background: #ecfdf5;
        padding: 1rem;
        color: #334155;
        margin-bottom: 1rem;
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
        <form action="{{ route('rutinas.update', $rutinaAdmin->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" id="routine_id" name="routine_id" value="{{ old('routine_id', $rutinaAdmin->routine_id) }}" />
            <input type="hidden" id="student_email" name="student_email" value="{{ old('student_email', $rutinaAdmin->student_email) }}" />

            <div>
                <h2 class="text-lg font-semibold text-emerald-950 mb-3">Rutina seleccionada</h2>
                <div class="selected-info" id="selectedRoutineInfo">
                    <strong>Rutina seleccionada:</strong> {{ $rutinaAdmin->routine_name }}
                </div>
                <p class="info-text">Filtra por nombre y selecciona otra rutina si deseas cambiarla.</p>

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
                <h2 class="text-lg font-semibold text-emerald-950 mb-3">Estudiante seleccionado</h2>
                <div class="selected-info" id="selectedStudentInfo">
                    <strong>Estudiante seleccionado:</strong> {{ $rutinaAdmin->student_name }} — {{ $rutinaAdmin->student_email }}
                </div>
                <p class="info-text">Filtra por nombre o correo para seleccionar otro estudiante.</p>

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
                                <td><button type="button" class="select-btn" onclick="selectStudent('{{ addslashes($student->email) }}', '{{ addslashes($student->name) }}', this)">Seleccionar</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <a href="{{ route('rutinas.index') }}" class="btn-tertiary">Cancelar</a>
                <button type="submit" class="btn-primary">Actualizar Asignación</button>
                <form action="{{ route('rutinas.destroy', $rutinaAdmin->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">Eliminar Asignación</button>
                </form>
            </div>
        </form>
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