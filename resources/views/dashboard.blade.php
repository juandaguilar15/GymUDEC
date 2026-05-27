@php(
    $layout = auth()->user()->role === 'administrador'
        ? 'layouts.admin'
        : (auth()->user()->role === 'enfermero' ? 'layouts.nurse' : 'layouts.student')
)
@extends($layout)

@section('title', 'Dashboard - GymUdec')
@section('page-title', 'Panel de Control')
@section('page-subtitle')
    @if(auth()->user()->role === 'administrador')
        Unifica tu experiencia en el mismo diseño administrativo y mantén las acciones claras desde el primer ingreso.
    @elseif(auth()->user()->role === 'enfermero')
        Gestiona la enfermería con acciones directas para estudiantes, fichas médicas y datos relevantes.
    @else
        Revisa tu rutina, avisos y ficha física con un diseño limpio y accesible.
    @endif
@endsection

@section('content')
    <div class="grid gap-6 xl:grid-cols-[2fr_1fr] mb-8">
        <section class="admin-card">
            <p class="admin-section-label">Bienvenida</p>
            <h2 class="text-3xl font-bold text-emerald-950 mb-4">¡Hola, {{ auth()->user()->name }}!</h2>
            <p class="text-slate-600 mb-6">
                @if(auth()->user()->role === 'administrador')
                    Este es tu panel administrativo. Desde aquí puedes gestionar el sistema con claridad. Haz primero lo más importante y luego revisa los detalles.
                @elseif(auth()->user()->role === 'enfermero')
                    Este es tu panel de enfermería con una vista ordenada. Empieza por buscar estudiantes o revisar indicadores importantes.
                @else
                    Este es tu panel de estudiante con el estilo administrativo. Revisa tu rutina, consulta avisos y mantén tu ficha física al día.
                @endif
            </p>

            @if(auth()->user()->role === 'estudiante' )
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <a href="{{ route('student.routines.index') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">🏋️</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Mis Rutinas</h3>
                            <p class="dashboard-action-card-text">Ve tus rutinas activas y asignadas, sin saturar la pantalla con opciones extra.</p>
                        </div>
                    </a>
                    <a href="{{ route('student.notices.index') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">📝</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Avisos</h3>
                            <p class="dashboard-action-card-text">Revisa los mensajes importantes y regresa al dashboard cuando lo necesites.</p>
                        </div>
                    </a>
                    @if(!empty($canCreate) && $canCreate)
                        <a href="{{ route('student.routines.create') }}" class="action-card group">
                            <div class="dashboard-action-card-icon">✍️</div>
                            <div>
                                <h3 class="dashboard-action-card-title">Crear Rutina</h3>
                                <p class="dashboard-action-card-text">Abre la vista para crear tu rutina con la misma experiencia administrativa.</p>
                            </div>
                        </a>
                    @endif
                </div>
            @endif

            @if(auth()->user()->role === 'enfermero')
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <a href="{{ route('nurse.search-student') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">🔎</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Buscar estudiante</h3>
                            <p class="dashboard-action-card-text">Encuentra rápidamente la ficha del estudiante por nombre o correo.</p>
                        </div>
                    </a>
                    <a href="{{ route('nurse.list-students') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">👥</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Lista de estudiantes</h3>
                            <p class="dashboard-action-card-text">Revisa todos los registros de estudiantes y administra sus fichas físicas.</p>
                        </div>
                    </a>
                    <a href="{{ route('analytics.index') }}" class="action-card group">
                        <div class="dashboard-action-card-icon">📊</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Indicadores</h3>
                            <p class="dashboard-action-card-text">Consulta métricas clave que apoyan el seguimiento enfermero.</p>
                        </div>
                    </a>
                </div>
            @endif

            @if(auth()->user()->role === 'administrador')
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="admin-menu-card">
                        <div class="dashboard-action-card-icon">👥</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Usuarios</h3>
                            <p class="dashboard-action-card-text">Gestiona el listado de usuarios desde el módulo correspondiente.</p>
                        </div>
                    </div>
                    <div class="admin-menu-card">
                        <div class="dashboard-action-card-icon">📋</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Rutinas</h3>
                            <p class="dashboard-action-card-text">Accede al área de rutinas sin botones dedicados en este panel.</p>
                        </div>
                    </div>
                    <div class="admin-menu-card">
                        <div class="dashboard-action-card-icon">📢</div>
                        <div>
                            <h3 class="dashboard-action-card-title">Avisos</h3>
                            <p class="dashboard-action-card-text">Observa el estado de los avisos y ve al módulo si deseas editarlos.</p>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <aside class="admin-card">
            <p class="admin-section-label">Tu perfil</p>
            <h3 class="text-xl font-semibold text-emerald-950 mb-3">Resumen rápido</h3>
            <p class="text-slate-600 mb-6">Accede a tu información personal y a las acciones más relevantes desde un solo panel.</p>

            <div class="grid gap-4 mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Nombre</p>
                    <p class="text-base font-medium text-emerald-950">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Correo</p>
                    <p class="text-base font-medium text-emerald-950">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Rol</p>
                    <p class="text-base font-medium text-emerald-950">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold">Miembro desde</p>
                    <p class="text-base font-medium text-emerald-950">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="grid gap-3">
                @if(auth()->user()->role === 'estudiante')
                    <p class="text-sm text-slate-600">Accede a tu ficha física desde el módulo de estudiante.</p>
                @elseif(auth()->user()->role === 'enfermero')
                    <p class="text-sm text-slate-600">Utiliza las opciones de enfermería para buscar y gestionar estudiantes.</p>
                @elseif(auth()->user()->role === 'administrador')
                    <p class="text-sm text-slate-600">Este panel muestra funciones de administración sin botones de acceso directo.</p>
                @endif
            </div>
        </aside>
    </div>

    @if(auth()->user()->role === 'estudiante')
        <div class="admin-card mb-8">
            <h2 class="text-xl font-semibold text-emerald-950 mb-4">Indicadores rápidos</h2>
            <div class="admin-stat-grid">
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Rutinas actuales</p>
                    <div class="dashboard-stat-value">{{ $routineCount ?? $user->routines()->count() }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Avisos activos</p>
                    <div class="dashboard-stat-value">{{ $notices->count() }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Ficha física</p>
                    <div class="dashboard-stat-value">{{ optional($user->physicalInfo)->exists ? 'Registrada' : 'Pendiente' }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Permiso</p>
                    <div class="dashboard-stat-value">{{ ucfirst($permisos ?? 'desconocido') }}</div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->role === 'administrador')
        <div class="admin-card mb-8">
            <h2 class="text-xl font-semibold text-emerald-950 mb-4">Indicadores clave</h2>
            <div class="admin-stat-grid">
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Usuarios totales</p>
                    <div class="dashboard-stat-value">{{ $stats['totalUsers'] }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Estudiantes</p>
                    <div class="dashboard-stat-value">{{ $stats['totalStudents'] }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Máquinas</p>
                    <div class="dashboard-stat-value">{{ $stats['totalMachines'] }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Ejercicios</p>
                    <div class="dashboard-stat-value">{{ $stats['totalExercises'] }}</div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->role === 'enfermero')
        <div class="admin-card mb-8">
            <h2 class="text-xl font-semibold text-emerald-950 mb-4">Indicadores enfermería</h2>
            <div class="admin-stat-grid">
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Estudiantes</p>
                    <div class="dashboard-stat-value">{{ $studentCount ?? $stats['totalStudents'] ?? 'N/A' }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Fichas físicas</p>
                    <div class="dashboard-stat-value">{{ $stats['totalPhysicalInfos'] ?? 'N/A' }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Avisos activos</p>
                    <div class="dashboard-stat-value">{{ $notices->count() }}</div>
                </div>
                <div class="dashboard-stat-card">
                    <p class="dashboard-stat-label">Fichas actualizadas hoy</p>
                    <div class="dashboard-stat-value">{{ $stats['updatedToday'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    @endif
@endsection
