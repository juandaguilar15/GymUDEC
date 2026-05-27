@extends('layouts.student')

@section('title', 'Ejecutar Rutina - GymUdec')
@section('page-title', 'Ejecutar Rutina')
@section('page-subtitle', $routine->name)
@section('page-actions')
    <a href="{{ route('student.routines.show', ['routine' => $routine->id]) }}" class="btn-tertiary">← Detalle</a>
    <a href="{{ route('student.routines.index') }}" class="btn-tertiary">Mis Rutinas</a>
@endsection

@push('head')
<style>
    .select-day { width: 100%; max-width: 320px; padding: 0.85rem 1rem; border: 1px solid #cfd8dc; border-radius: 10px; background: white; font-size: 0.95rem; }
    .step-card { border-radius: 16px; border: 1px solid #dfe7eb; background: #ffffff; padding: 1.75rem; margin-top: 1.5rem; }
    .step-title { margin: 0 0 0.75rem 0; color: #1B5E46; font-size: 1.25rem; }
    .step-detail { color: #374151; font-size: 1rem; line-height: 1.8; }
    .step-meta { margin-top: 1rem; color: #475569; font-size: 0.95rem; }
    .step-footer { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-top: 1.75rem; }
    .message { padding: 1rem 1.2rem; border-radius: 12px; background: #ecfdf5; color: #0f5132; margin-top: 1rem; }
</style>
@endpush

@section('content')
    <div class="admin-card">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-semibold text-emerald-950">Ejecutar rutina: {{ $routine->name }}</h2>
                <p class="text-slate-600 mt-1">Selecciona un día para iniciar el entrenamiento paso a paso.</p>
                <p class="text-slate-600">Origen: <strong>{{ $routine->creator_label }}</strong></p>
            </div>
            <div><span class="status-badge">{{ ucfirst($routine->status) }}</span></div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-emerald-950 mb-3">Elegir día</h3>
            <div class="panel">
                <div class="flex items-center gap-3">
                    <select id="day-select" class="select-day" onchange="onSelectedDayChange()">
                        <option value="">Selecciona un día</option>
                        @foreach($trainingDays as $trainingDay)
                            <option value="{{ $trainingDay->day_name }}" {{ optional($selectedDay)->day_name === $trainingDay->day_name ? 'selected' : '' }}>{{ ucfirst($trainingDay->day_name) }}</option>
                        @endforeach
                    </select>
                    <a id="start-button" href="{{ $selectedDay ? route('student.routines.execute', ['routine' => $routine->id, 'day' => $selectedDay->day_name]) : '#' }}" class="btn-primary">{{ $selectedDay ? 'Reiniciar día' : 'Iniciar día' }}</a>
                </div>
                <p class="text-sm text-slate-600 mt-3">Puedes avanzar ejercicio por ejercicio con los botones, verás ejercicio → descanso → siguiente ejercicio.</p>
            </div>
        </div>

        <div id="execution-section" style="display: {{ $selectedDay ? 'block' : 'none' }};">
            <h3 class="text-lg font-semibold text-emerald-950 mb-3">Día seleccionado: {{ optional($selectedDay)->day_name ? ucfirst($selectedDay->day_name) : '—' }}</h3>
            <div class="step-card">
                <h4 class="step-title" id="step-title"></h4>
                <div class="step-detail" id="step-detail"></div>
                <div class="step-meta" id="step-meta"></div>
                <div class="step-footer">
                    <span class="text-sm text-slate-500" id="step-progress"></span>
                    <button id="next-step-button" class="btn-primary">Continuar</button>
                </div>
                <div class="message" id="finish-message" style="display:none;">¡Rutina completada! Felicitaciones por terminar este día de entrenamiento.</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const trainingDays = @json($trainingDays->map(function ($day) {
        return ['day_name' => $day->day_name, 'day_order' => $day->day_order];
    }));
    const selectedDayName = @json(optional($selectedDay)->day_name);
    const selectedDayExercises = @json($selectedDayExercises);

    const daySelect = document.getElementById('day-select');
    const startButton = document.getElementById('start-button');
    const executionSection = document.getElementById('execution-section');
    const stepTitle = document.getElementById('step-title');
    const stepDetail = document.getElementById('step-detail');
    const stepMeta = document.getElementById('step-meta');
    const nextStepButton = document.getElementById('next-step-button');
    const stepProgress = document.getElementById('step-progress');
    const finishMessage = document.getElementById('finish-message');

    let steps = [];
    let currentStepIndex = 0;

    function buildSteps() {
        steps = [];
        selectedDayExercises.forEach(item => {
            if (item.format === 'duration') {
                steps.push({ type: 'exercise-duration', payload: item });
                steps.push({ type: 'rest', payload: item });
            } else {
                const sets = Number(item.sets) || 1;
                for (let set = 1; set <= sets; set++) {
                    steps.push({ type: 'exercise-set', payload: { ...item, set, totalSets: sets } });
                    steps.push({ type: 'rest', payload: item });
                }
            }
        });
    }

    function renderStep() {
        if (currentStepIndex >= steps.length) {
            stepTitle.textContent = '¡Día terminado!';
            stepDetail.textContent = 'Has completado todos los pasos de este día.';
            stepMeta.textContent = '';
            stepProgress.textContent = `${steps.length}/${steps.length}`;
            nextStepButton.style.display = 'none';
            finishMessage.style.display = 'block';
            return;
        }

        const step = steps[currentStepIndex];
        const payload = step.payload;
        let title = '';
        let detail = '';
        let meta = '';

        if (step.type === 'exercise-duration') {
            title = `Ejercicio: ${payload.exercise_name}`;
            detail = `Duración: ${payload.duration || 'N/A'} ${payload.duration_unit || ''}`;
            meta = `Formato: duración`;
        } else if (step.type === 'exercise-set') {
            title = `Ejercicio: ${payload.exercise_name}`;
            detail = `Serie ${payload.set} de ${payload.totalSets} · Reps: ${payload.reps || 'N/A'}`;
            meta = `Formato: series`;
        } else if (step.type === 'rest') {
            title = 'Descanso';
            detail = `Tiempo de descanso: ${payload.rests || 'N/A'} ${payload.rests_unit || ''}`;
            meta = 'Recupera fuerzas antes del siguiente paso.';
        }

        stepTitle.textContent = title;
        stepDetail.textContent = detail;
        stepMeta.textContent = meta;
        stepProgress.textContent = `${currentStepIndex + 1}/${steps.length}`;
        nextStepButton.textContent = currentStepIndex + 1 === steps.length ? 'Finalizar día' : 'Continuar';
        nextStepButton.style.display = 'inline-block';
        finishMessage.style.display = 'none';
    }

    function nextStep() {
        currentStepIndex += 1;
        renderStep();
    }

    function onSelectedDayChange() {
        const selectedValue = daySelect.value;
        const url = new URL(window.location.href);
        if (selectedValue) {
            url.searchParams.set('day', selectedValue);
        } else {
            url.searchParams.delete('day');
        }
        startButton.href = url.toString();
        startButton.textContent = selectedValue ? 'Iniciar día' : 'Seleccionar día';
    }

    nextStepButton.addEventListener('click', nextStep);

    if (selectedDayName) {
        buildSteps();
        renderStep();
    } else {
        executionSection.style.display = 'none';
    }
</script>
@endpush
