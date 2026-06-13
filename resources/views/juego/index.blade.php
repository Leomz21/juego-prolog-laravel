@php
    $modoActual = $modo ?? request('modo', 'inicio');

    $modoItems = [
        ['modo' => 'inicio', 'titulo' => 'Inicio', 'subtitulo' => 'Menú principal', 'icono' => '🏰'],
        ['modo' => 'solitario', 'titulo' => 'Combate individual', 'subtitulo' => 'Un héroe contra un enemigo', 'icono' => '⚔️'],
        ['modo' => 'grupo', 'titulo' => 'Combate grupal', 'subtitulo' => 'Forma un equipo de batalla', 'icono' => '🛡️'],
        ['modo' => 'mision_individual', 'titulo' => 'Misión individual', 'subtitulo' => 'Acepta una aventura en solitario', 'icono' => '📜'],
        ['modo' => 'mision_grupal', 'titulo' => 'Misión grupal', 'subtitulo' => 'Supera una misión con aliados', 'icono' => '🧭'],
        ['modo' => 'estado', 'titulo' => 'Ver personaje', 'subtitulo' => 'Consulta la ficha del héroe', 'icono' => '✨'],
    ];

    $personajeMeta = [
        'Elara' => [
            'icono' => '🧝‍♀️',
            'descripcion' => 'Guerrera élfica del reino',
            'armas' => ['espada', 'daga'],
            'inventario' => ['⚔️ Espada', '🗡️ Daga', '🛡️ Escudo', '🧪 Poción'],
        ],
        'Kael' => [
            'icono' => '🧝‍♂️',
            'descripcion' => 'Arquero élfico del bosque',
            'armas' => ['arco', 'daga', 'hacha'],
            'inventario' => ['🏹 Arco', '🗡️ Daga', '🪓 Hacha', '🎯 Flechas'],
        ],
        'Rin' => [
            'icono' => '🧙‍♀️',
            'descripcion' => 'Maga arcana de la torre',
            'armas' => ['varita', 'espada'],
            'inventario' => ['🔮 Varita', '⚔️ Espada', '📘 Libro', '🧪 Poción', '🔱 Amuleto'],
        ],
        'Luna' => [
            'icono' => '🥷',
            'descripcion' => 'Ninja sigilosa de la luna',
            'armas' => ['daga', 'arco', 'lanza'],
            'inventario' => ['🗡️ Daga', '🏹 Arco', '🔱 Lanza', '🧪 Poción', '🔱 Amuleto'],
        ],
        'Darius' => [
            'icono' => '🧔‍♂️',
            'descripcion' => 'Guardián pesado de hierro',
            'armas' => ['hacha', 'lanza', 'espada'],
            'inventario' => ['🪓 Hacha', '🔱 Lanza', '⚔️ Espada', '🛡️ Escudo', '🧪 Poción'],
        ],
        'Milo' => [
            'icono' => '🧑‍🌾',
            'descripcion' => 'Aventurero novato del valle',
            'armas' => ['lanza', 'espada', 'arco'],
            'inventario' => ['🔱 Lanza', '⚔️ Espada', '🏹 Arco', '🧪 Poción', '🗺️ Mapa'],
        ],
    ];

    $armaMeta = [
        'espada' => ['icono' => '⚔️', 'descripcion' => 'Arma equilibrada'],
        'arco' => ['icono' => '🏹', 'descripcion' => 'Ataque a distancia'],
        'varita' => ['icono' => '🔮', 'descripcion' => 'Poder mágico'],
        'daga' => ['icono' => '🗡️', 'descripcion' => 'Rápida y ligera'],
        'hacha' => ['icono' => '🪓', 'descripcion' => 'Daño pesado'],
        'lanza' => ['icono' => '🔱', 'descripcion' => 'Gran alcance'],
    ];

    $enemigoMeta = [
        'bruja' => ['icono' => '🧹', 'descripcion' => 'Hechicera oscura'],
        'zombi' => ['icono' => '🧟', 'descripcion' => 'No-muerto resistente'],
        'vampiro' => ['icono' => '🧛', 'descripcion' => 'Criatura de la noche'],
        'demonio' => ['icono' => '👹', 'descripcion' => 'Amenaza infernal'],
        'dragon' => ['icono' => '🐉', 'descripcion' => 'Jefe legendario'],
    ];

    $misionMeta = [
        'm1' => ['icono' => '🌲', 'descripcion' => 'Explora un bosque maldito'],
        'm2' => ['icono' => '🐉', 'descripcion' => 'Adéntrate en la cueva del dragón'],
        'm3' => ['icono' => '🏛️', 'descripcion' => 'Descifra secretos antiguos'],
        'm4' => ['icono' => '🏚️', 'descripcion' => 'Investiga ruinas olvidadas'],
        'm5' => ['icono' => '🏰', 'descripcion' => 'Asalta una fortaleza helada'],
    ];

    $opcion = function ($key, $value) {
        if (is_array($value)) {
            $valor = $value['id'] ?? $value['valor'] ?? $value['nombre'] ?? (is_string($key) ? $key : '');
            $texto = $value['nombre'] ?? $value['label'] ?? $value['titulo'] ?? $valor;
        } else {
            $valor = is_string($key) ? $key : $value;
            $texto = $value;
        }

        return [(string) $valor, (string) $texto];
    };

    $grupoOld = array_map('strval', old('grupo', []));

    $resultadoPartes = [];
    if (!empty($resultado)) {
        $resultadoPartes = preg_split('/\.\s+/', trim($resultado), -1, PREG_SPLIT_NO_EMPTY);
    }

    $iconoResultado = function ($texto) {
        $t = mb_strtolower($texto);

        if (str_contains($t, 'vida inicial del enemigo') || str_contains($t, 'vida final del enemigo') || str_contains($t, 'enemigo era')) {
            return '🖤';
        }

        if (str_contains($t, 'vida inicial del jugador') || str_contains($t, 'vida final del jugador') || str_contains($t, 'vida inicial del grupo') || str_contains($t, 'vida final del grupo')) {
            return '❤️';
        }

        if (str_contains($t, 'danio') || str_contains($t, 'daño')) {
            return '💥';
        }

        if (str_contains($t, 'ataques')) {
            return '🎯';
        }

        if (str_contains($t, 'victoria')) {
            return '🏆';
        }

        if (str_contains($t, 'derrota') || str_contains($t, 'no puede') || str_contains($t, 'falta')) {
            return '⚠️';
        }

        if (str_contains($t, 'xp') || str_contains($t, 'nivel')) {
            return '✨';
        }

        return '📌';
    };
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crónicas de Aetheria</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="rpg-body">
    <main class="rpg-shell">
        <section class="rpg-hero">
            <div class="rpg-hero-content">
                <span class="rpg-badge">Reino de Aetheria</span>
                <h1>Crónicas de Aetheria</h1>
                <p>
                    Elige tu camino, reúne aliados, acepta misiones y enfréntate
                    a criaturas que amenazan el reino.
                </p>
            </div>

            <div class="rpg-hero-orb">
                <span>☽</span>
            </div>
        </section>

        <section class="rpg-layout">
            <aside class="rpg-sidebar">
                <h2>Modos de juego</h2>

                <nav class="rpg-mode-list">
                    @foreach ($modoItems as $item)
                        <a
                            href="{{ route('juego.index', ['modo' => $item['modo']]) }}"
                            class="rpg-mode-card {{ $modoActual === $item['modo'] ? 'is-active' : '' }}"
                        >
                            <span class="rpg-mode-icon">{{ $item['icono'] }}</span>
                            <span>
                                <strong>{{ $item['titulo'] }}</strong>
                                <small>{{ $item['subtitulo'] }}</small>
                            </span>
                        </a>
                    @endforeach
                </nav>

                <div class="rpg-system-card">
                    <h3>Estado del reino</h3>
                    <p>Las puertas de Aetheria están abiertas. Elige una aventura para comenzar.</p>
                    <div class="rpg-status-line">
                        <span></span>
                        <strong>Aventura disponible</strong>
                    </div>
                </div>
            </aside>

            <section class="rpg-stage">
                @if ($errors->any())
                    <div class="rpg-alert">
                        <strong>La acción no pudo ejecutarse:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($modoActual === 'inicio')
                    <div class="rpg-panel rpg-intro-panel">
                        <div>
                            <span class="rpg-panel-kicker">Menú principal</span>
                            <h2>Selecciona tu aventura</h2>
                            <p>
                                Explora el reino de Aetheria, prepara a tus héroes y elige entre
                                combates, misiones o consulta de personajes.
                            </p>
                        </div>

                        <div class="rpg-home-grid">
                            <a href="{{ route('juego.index', ['modo' => 'combate']) }}" class="rpg-feature-card">
                                <span>⚔️</span>
                                <h3>Entrar en combate</h3>
                                <p>Elige si quieres luchar en solitario o formar un equipo.</p>
                            </a>

                            <a href="{{ route('juego.index', ['modo' => 'misiones']) }}" class="rpg-feature-card">
                                <span>📜</span>
                                <h3>Aceptar misión</h3>
                                <p>Escoge entre aventuras individuales o expediciones grupales.</p>
                            </a>

                            <a href="{{ route('juego.index', ['modo' => 'estado']) }}" class="rpg-feature-card">
                                <span>✨</span>
                                <h3>Ver personaje</h3>
                                <p>Consulta la ficha de tus héroes antes de iniciar la aventura.</p>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($modoActual === 'combate')
                    <div class="rpg-panel rpg-intro-panel">
                        <div>
                            <span class="rpg-panel-kicker">Zona de combate</span>
                            <h2>Elige el tipo de batalla</h2>
                            <p>
                                Puedes enfrentarte a un enemigo con un solo héroe o formar un equipo
                                para una batalla grupal.
                            </p>
                        </div>

                        <div class="rpg-home-grid two-columns">
                            <a href="{{ route('juego.index', ['modo' => 'solitario']) }}" class="rpg-feature-card">
                                <span>⚔️</span>
                                <h3>Combate individual</h3>
                                <p>Un héroe, un arma, un enemigo y una estrategia de ataque.</p>
                            </a>

                            <a href="{{ route('juego.index', ['modo' => 'grupo']) }}" class="rpg-feature-card">
                                <span>🛡️</span>
                                <h3>Combate grupal</h3>
                                <p>Forma un equipo y enfrenta una amenaza en conjunto.</p>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($modoActual === 'misiones')
                    <div class="rpg-panel rpg-intro-panel">
                        <div>
                            <span class="rpg-panel-kicker">Tablero de misiones</span>
                            <h2>Elige el tipo de misión</h2>
                            <p>
                                Acepta una aventura en solitario o reúne aliados para superar una misión grupal.
                            </p>
                        </div>

                        <div class="rpg-home-grid two-columns">
                            <a href="{{ route('juego.index', ['modo' => 'mision_individual']) }}" class="rpg-feature-card">
                                <span>📜</span>
                                <h3>Misión individual</h3>
                                <p>Envía a un personaje a cumplir una aventura por su cuenta.</p>
                            </a>

                            <a href="{{ route('juego.index', ['modo' => 'mision_grupal']) }}" class="rpg-feature-card">
                                <span>🧭</span>
                                <h3>Misión grupal</h3>
                                <p>Forma un equipo y comprueba si pueden completar la misión.</p>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($modoActual === 'solitario')
                    <div class="rpg-panel">
                        <div class="rpg-panel-header">
                            <span class="rpg-panel-kicker">Arena de combate</span>
                            <h2>Combate individual</h2>
                            <p>Selecciona un héroe, un arma de su inventario, un enemigo y define tu estrategia.</p>
                        </div>

                        <form method="POST" action="{{ route('juego.jugar') }}" class="rpg-form" data-combat-form="individual">
                            @csrf
                            <input type="hidden" name="tipo" value="combate_individual">

                            <div class="rpg-form-section">
                                <h3>Elige tu héroe</h3>
                                <div class="rpg-choice-grid">
                                    @foreach ($personajes as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $personajeMeta[$valor] ?? ['icono' => '🧑', 'descripcion' => 'Aventurero', 'armas' => [], 'inventario' => []];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="personaje"
                                                id="solitario-personaje-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                data-inventory="{{ implode(',', $meta['armas']) }}"
                                                required
                                                @checked(old('personaje') === $valor)
                                            >
                                            <label class="rpg-choice-card" for="solitario-personaje-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                                <span class="rpg-inventory-title">Inventario:</span>
                                                <span class="rpg-inventory-list">
                                                    @foreach ($meta['inventario'] as $item)
                                                        <em>{{ $item }}</em>
                                                    @endforeach
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rpg-form-section">
                                <h3>Elige tu arma</h3>
                                <p class="rpg-muted">Al seleccionar un héroe se resaltarán las armas que puede usar.</p>

                                <div class="rpg-choice-grid">
                                    @foreach ($armas as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $armaMeta[$valor] ?? ['icono' => '🗡️', 'descripcion' => 'Arma disponible'];
                                        @endphp

                                        <div class="rpg-weapon-option" data-weapon-option="{{ $valor }}">
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="arma"
                                                id="solitario-arma-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                data-weapon-id="{{ $valor }}"
                                                required
                                                @checked(old('arma') === $valor)
                                            >
                                            <label class="rpg-choice-card" for="solitario-arma-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                                <span class="rpg-weapon-state">Disponible según inventario</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rpg-form-section">
                                <h3>Elige tu enemigo</h3>
                                <div class="rpg-choice-grid">
                                    @foreach ($enemigos as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $enemigoMeta[$valor] ?? ['icono' => '👹', 'descripcion' => 'Amenaza desconocida'];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="enemigo"
                                                id="solitario-enemigo-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                required
                                                @checked(old('enemigo') === $valor)
                                            >
                                            <label class="rpg-choice-card enemy" for="solitario-enemigo-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rpg-strategy-row">
                                <label>
                                    <span>Número de ataques</span>
                                    <input type="number" name="ataques" min="1" max="10" value="{{ old('ataques', 1) }}" required>
                                </label>

                                <button type="submit" class="rpg-main-button" data-loading-label="Preparando combate...">
                                    Iniciar combate
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($modoActual === 'grupo')
                    <div class="rpg-panel">
                        <div class="rpg-panel-header">
                            <span class="rpg-panel-kicker">Batalla cooperativa</span>
                            <h2>Combate grupal</h2>
                            <p>Forma un equipo, selecciona un enemigo y entra al campo de batalla.</p>
                        </div>

                        <form method="POST" action="{{ route('juego.jugar') }}" class="rpg-form rpg-team-form">
                            @csrf
                            <input type="hidden" name="tipo" value="combate_grupal">

                            <div class="rpg-form-section">
                                <h3>Forma tu equipo</h3>
                                <p class="rpg-muted">Selecciona uno o varios personajes.</p>

                                <div class="rpg-choice-grid">
                                    @foreach ($personajes as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $personajeMeta[$valor] ?? ['icono' => '🧑', 'descripcion' => 'Aventurero', 'inventario' => []];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="checkbox"
                                                name="grupo[]"
                                                id="grupo-personaje-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                @checked(in_array($valor, $grupoOld, true))
                                            >
                                            <label class="rpg-choice-card" for="grupo-personaje-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                                <span class="rpg-inventory-title">Inventario:</span>
                                                <span class="rpg-inventory-list">
                                                    @foreach ($meta['inventario'] as $item)
                                                        <em>{{ $item }}</em>
                                                    @endforeach
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="rpg-team-counter" data-team-counter>
                                    Equipo seleccionado: 0
                                </div>
                            </div>

                            <div class="rpg-form-section">
                                <h3>Elige tu enemigo</h3>
                                <div class="rpg-choice-grid">
                                    @foreach ($enemigos as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $enemigoMeta[$valor] ?? ['icono' => '👹', 'descripcion' => 'Amenaza desconocida'];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="enemigo"
                                                id="grupo-enemigo-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                required
                                                @checked(old('enemigo') === $valor)
                                            >
                                            <label class="rpg-choice-card enemy" for="grupo-enemigo-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rpg-strategy-row">
                                <label>
                                    <span>Ataques por jugador</span>
                                    <input type="number" name="ataques" min="1" max="10" value="{{ old('ataques', 1) }}" required>
                                </label>

                                <button type="submit" class="rpg-main-button" data-loading-label="Preparando batalla...">
                                    Iniciar batalla grupal
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                @if ($modoActual === 'mision_individual')
                    <div class="rpg-panel">
                        <div class="rpg-panel-header">
                            <span class="rpg-panel-kicker">Tablero de aventuras</span>
                            <h2>Misión individual</h2>
                            <p>Elige un personaje y una misión para iniciar una nueva aventura.</p>
                        </div>

                        <form method="POST" action="{{ route('juego.jugar') }}" class="rpg-form">
                            @csrf
                            <input type="hidden" name="tipo" value="mision_individual">

                            <div class="rpg-form-section">
                                <h3>Elige tu aventurero</h3>
                                <div class="rpg-choice-grid">
                                    @foreach ($personajes as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $personajeMeta[$valor] ?? ['icono' => '🧑', 'descripcion' => 'Aventurero', 'inventario' => []];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="personaje"
                                                id="mision-personaje-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                required
                                                @checked(old('personaje') === $valor)
                                            >
                                            <label class="rpg-choice-card" for="mision-personaje-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                                <span class="rpg-inventory-title">Inventario:</span>
                                                <span class="rpg-inventory-list">
                                                    @foreach ($meta['inventario'] as $item)
                                                        <em>{{ $item }}</em>
                                                    @endforeach
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rpg-form-section">
                                <h3>Elige una misión</h3>
                                <div class="rpg-mission-grid">
                                    @foreach ($misiones as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $misionMeta[$valor] ?? ['icono' => '📜', 'descripcion' => 'Aventura disponible'];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="mision"
                                                id="mision-individual-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                required
                                                @checked(old('mision') === $valor)
                                            >
                                            <label class="rpg-mission-card" for="mision-individual-{{ $loop->index }}">
                                                <span>{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="rpg-main-button" data-loading-label="Iniciando misión...">
                                Aceptar misión
                            </button>
                        </form>
                    </div>
                @endif

                @if ($modoActual === 'mision_grupal')
                    <div class="rpg-panel">
                        <div class="rpg-panel-header">
                            <span class="rpg-panel-kicker">Expedición</span>
                            <h2>Misión grupal</h2>
                            <p>Forma un equipo y selecciona una misión para comenzar la expedición.</p>
                        </div>

                        <form method="POST" action="{{ route('juego.jugar') }}" class="rpg-form rpg-team-form">
                            @csrf
                            <input type="hidden" name="tipo" value="mision_grupal">

                            <div class="rpg-form-section">
                                <h3>Forma tu equipo</h3>
                                <div class="rpg-choice-grid">
                                    @foreach ($personajes as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $personajeMeta[$valor] ?? ['icono' => '🧑', 'descripcion' => 'Aventurero', 'inventario' => []];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="checkbox"
                                                name="grupo[]"
                                                id="mision-grupo-personaje-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                @checked(in_array($valor, $grupoOld, true))
                                            >
                                            <label class="rpg-choice-card" for="mision-grupo-personaje-{{ $loop->index }}">
                                                <span class="rpg-choice-icon">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                                <span class="rpg-inventory-title">Inventario:</span>
                                                <span class="rpg-inventory-list">
                                                    @foreach ($meta['inventario'] as $item)
                                                        <em>{{ $item }}</em>
                                                    @endforeach
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="rpg-team-counter" data-team-counter>
                                    Equipo seleccionado: 0
                                </div>
                            </div>

                            <div class="rpg-form-section">
                                <h3>Elige una misión</h3>
                                <div class="rpg-mission-grid">
                                    @foreach ($misiones as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $misionMeta[$valor] ?? ['icono' => '📜', 'descripcion' => 'Aventura disponible'];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="mision"
                                                id="mision-grupal-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                required
                                                @checked(old('mision') === $valor)
                                            >
                                            <label class="rpg-mission-card" for="mision-grupal-{{ $loop->index }}">
                                                <span>{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="rpg-main-button" data-loading-label="Iniciando expedición...">
                                Iniciar expedición
                            </button>
                        </form>
                    </div>
                @endif

                @if ($modoActual === 'estado')
                    <div class="rpg-panel">
                        <div class="rpg-panel-header">
                            <span class="rpg-panel-kicker">Ficha de personaje</span>
                            <h2>Ver personaje</h2>
                            <p>Consulta la ficha del personaje antes de enviarlo a la aventura.</p>
                        </div>

                        <form method="POST" action="{{ route('juego.jugar') }}" class="rpg-form">
                            @csrf
                            <input type="hidden" name="tipo" value="perfil">

                            <div class="rpg-form-section">
                                <h3>Elige un personaje</h3>
                                <div class="rpg-choice-grid">
                                    @foreach ($personajes as $key => $value)
                                        @php
                                            [$valor, $texto] = $opcion($key, $value);
                                            $meta = $personajeMeta[$valor] ?? ['icono' => '🧑', 'descripcion' => 'Aventurero', 'inventario' => []];
                                        @endphp

                                        <div>
                                            <input
                                                class="rpg-choice-input"
                                                type="radio"
                                                name="personaje"
                                                id="estado-personaje-{{ $loop->index }}"
                                                value="{{ $valor }}"
                                                required
                                                @checked(old('personaje') === $valor)
                                            >
                                            <label class="rpg-character-card" for="estado-personaje-{{ $loop->index }}">
                                                <span class="rpg-character-avatar">{{ $meta['icono'] }}</span>
                                                <strong>{{ $texto }}</strong>
                                                <small>{{ $meta['descripcion'] }}</small>

                                                <div class="rpg-stat">
                                                    <span>Vida</span>
                                                    <div><i style="width: 82%"></i></div>
                                                </div>

                                                <div class="rpg-stat">
                                                    <span>Energía</span>
                                                    <div><i style="width: 68%"></i></div>
                                                </div>

                                                <span class="rpg-inventory-title">Inventario:</span>
                                                <span class="rpg-inventory-list">
                                                    @foreach ($meta['inventario'] as $item)
                                                        <em>{{ $item }}</em>
                                                    @endforeach
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="rpg-main-button" data-loading-label="Consultando ficha...">
                                Consultar ficha
                            </button>
                        </form>
                    </div>
                @endif

                @if (!empty($resultado))
                    <section class="rpg-result" id="resultado-aventura">
                        <div class="rpg-result-header">
                            <span>📖</span>
                            <div>
                                <h2>Bitácora de aventura</h2>
                                <p>Resultado de tu acción</p>
                            </div>
                        </div>

                        <div class="rpg-result-grid">
                            @foreach ($resultadoPartes as $parte)
                                @php
                                    $parte = trim($parte);
                                    $parte = str_ends_with($parte, '.') ? $parte : $parte . '.';
                                @endphp

                                <article class="rpg-result-card">
                                    <span>{{ $iconoResultado($parte) }}</span>
                                    <p>{{ $parte }}</p>
                                </article>
                            @endforeach
                        </div>

                        <details class="rpg-raw-result">
                            <summary>Ver narración completa</summary>
                            <pre>{{ $resultado }}</pre>
                        </details>
                    </section>
                @endif
            </section>
        </section>
    </main>
</body>
</html>