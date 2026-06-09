<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simulador Juego</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, #bae6fd 0, transparent 35%),
                radial-gradient(circle at top right, #fbcfe8 0, transparent 35%),
                linear-gradient(135deg, #f8fafc, #eef2ff);
            color: #172033;
            font-family: Arial, sans-serif;
        }

        .contenedor {
            max-width: 1180px;
            margin: 35px auto;
        }

        .hero {
            background: linear-gradient(135deg, #2563eb, #7c3aed, #db2777);
            color: white;
            padding: 34px;
            border-radius: 28px;
            box-shadow: 0 20px 45px rgba(79, 70, 229, 0.28);
            margin-bottom: 28px;
        }

        .hero h1 {
            font-weight: 900;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .hero p {
            margin: 0;
            font-size: 1.08rem;
            opacity: 0.95;
        }

        .btn-volver {
            display: inline-block;
            margin-bottom: 18px;
            color: #2563eb;
            font-weight: 800;
            text-decoration: none;
        }

        .btn-volver:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .modo-card {
            background: white;
            border-radius: 24px;
            padding: 26px;
            height: 100%;
            border: 1px solid #e5e7eb;
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.10);
            transition: 0.2s ease;
            text-decoration: none;
            display: block;
            color: #172033;
        }

        .modo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 45px rgba(15, 23, 42, 0.16);
            color: #172033;
        }

        .modo-card h2 {
            font-weight: 900;
            font-size: 1.45rem;
            margin-bottom: 10px;
        }

        .modo-card p {
            color: #4b5563;
            margin-bottom: 0;
        }

        .azul h2 { color: #2563eb; }
        .morado h2 { color: #7c3aed; }
        .naranja h2 { color: #ea580c; }
        .verde h2 { color: #16a34a; }
        .rosado h2 { color: #db2777; }

        .panel-juego {
            background: white;
            border-radius: 26px;
            padding: 28px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .panel-juego h2 {
            font-weight: 900;
            margin-bottom: 8px;
        }

        .descripcion {
            color: #4b5563;
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 800;
            color: #111827;
        }

        .form-select,
        .form-control {
            border: 2px solid #d1d5db;
            border-radius: 14px;
            padding: 11px 13px;
            background: #f9fafb;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
        }

        .btn-jugar {
            border: none;
            border-radius: 14px;
            color: white;
            font-weight: 900;
            padding: 13px;
            width: 100%;
            margin-top: 10px;
            font-size: 1.02rem;
        }

        .btn-azul { background: linear-gradient(135deg, #2563eb, #06b6d4); }
        .btn-morado { background: linear-gradient(135deg, #7c3aed, #db2777); }
        .btn-naranja { background: linear-gradient(135deg, #ea580c, #f59e0b); }
        .btn-verde { background: linear-gradient(135deg, #16a34a, #22c55e); }
        .btn-rosado { background: linear-gradient(135deg, #db2777, #f97316); }

        .btn-jugar:hover {
            opacity: 0.92;
            color: white;
        }

        .resultado {
            background: #ffffff;
            border: 3px solid #22c55e;
            border-left: 12px solid #22c55e;
            border-radius: 22px;
            padding: 24px;
            margin-top: 26px;
            box-shadow: 0 18px 38px rgba(34, 197, 94, 0.18);
        }

        .resultado h3 {
            color: #15803d;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .resultado p {
            font-size: 1.05rem;
            line-height: 1.7;
            margin: 0;
        }

        .grupo-check {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .grupo-check label {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            padding: 11px 12px;
            cursor: pointer;
            font-weight: 700;
        }

        .grupo-check label:hover {
            border-color: #7c3aed;
            background: #f5f3ff;
        }

        .grupo-check input {
            margin-right: 8px;
        }

        .nota {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e3a8a;
            border-radius: 18px;
            padding: 16px;
            margin-top: 20px;
            font-weight: 600;
        }
    </style>
</head>

<body>
<div class="contenedor px-3">
    <div class="hero">
        <h1>Simulador Juego</h1>

        @if ($modo === 'inicio')
            <p>Escoge un modo de juego para comenzar la aventura.</p>
        @else
            <p>Configura tu partida y presiona Jugar para ver el resultado.</p>
        @endif
    </div>

    @if ($modo === 'inicio')
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <a class="modo-card azul" href="{{ route('juego.index', ['modo' => 'solitario']) }}">
                    <h2>Partida en solitario</h2>
                    <p>Elige un personaje, un arma, un enemigo y la cantidad de ataques.</p>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a class="modo-card morado" href="{{ route('juego.index', ['modo' => 'grupo']) }}">
                    <h2>Partida en grupo</h2>
                    <p>Forma un equipo de personajes y enfrenta a un enemigo en conjunto.</p>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a class="modo-card naranja" href="{{ route('juego.index', ['modo' => 'mision_individual']) }}">
                    <h2>Mision individual</h2>
                    <p>Selecciona un personaje y revisa si puede entrar a una mision.</p>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a class="modo-card rosado" href="{{ route('juego.index', ['modo' => 'mision_grupal']) }}">
                    <h2>Mision grupal</h2>
                    <p>Elige un grupo y comprueba si cumple los requisitos de la mision.</p>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a class="modo-card verde" href="{{ route('juego.index', ['modo' => 'estado']) }}">
                    <h2>Estado del jugador</h2>
                    <p>Consulta nivel, vida, clase, inventario y XP acumulada.</p>
                </a>
            </div>
        </div>

        <div class="nota">
            Cada modo usa reglas diferentes del juego: combate individual, combate grupal, misiones, inventario, vida y XP.
        </div>
    @else
        <a class="btn-volver" href="{{ route('juego.index') }}">← Volver a modos de juego</a>

        <div class="panel-juego">
            @if ($modo === 'solitario')
                <h2 style="color:#2563eb;">Partida en solitario</h2>
                <p class="descripcion">
                    Selecciona un personaje, el arma que usara, el enemigo y cuantas veces atacara.
                    El enemigo respondera con una cantidad aleatoria de ataques.
                </p>

                <form method="POST" action="{{ route('juego.jugar') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="combate_individual">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Personaje</label>
                            <select name="personaje" class="form-select" required>
                                @foreach ($personajes as $personaje)
                                    <option value="{{ $personaje }}">{{ $personaje }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Arma</label>
                            <select name="arma" class="form-select" required>
                                @foreach ($armas as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Enemigo</label>
                            <select name="enemigo" class="form-select" required>
                                @foreach ($enemigos as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ataques del jugador</label>
                            <input type="number" name="ataques" class="form-control" min="1" max="10" value="1" required>
                        </div>
                    </div>

                    <button class="btn btn-jugar btn-azul">Jugar</button>
                </form>
            @endif

            @if ($modo === 'grupo')
                <h2 style="color:#7c3aed;">Partida en grupo</h2>
                <p class="descripcion">
                    Forma un equipo. Cada personaje usara su mejor arma disponible contra el enemigo.
                </p>

                <form method="POST" action="{{ route('juego.jugar') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="combate_grupal">

                    <div class="mb-3">
                        <label class="form-label">Grupo</label>
                        <div class="grupo-check">
                            @foreach ($personajes as $personaje)
                                <label>
                                    <input type="checkbox" name="grupo[]" value="{{ $personaje }}">
                                    {{ $personaje }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Enemigo</label>
                            <select name="enemigo" class="form-select" required>
                                @foreach ($enemigos as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ataques por jugador</label>
                            <input type="number" name="ataques" class="form-control" min="1" max="10" value="1" required>
                        </div>
                    </div>

                    <button class="btn btn-jugar btn-morado">Jugar</button>
                </form>
            @endif

            @if ($modo === 'mision_individual')
                <h2 style="color:#ea580c;">Mision individual</h2>
                <p class="descripcion">
                    Elige un personaje y una mision. El juego revisara nivel, requisitos, inventario y XP.
                </p>

                <form method="POST" action="{{ route('juego.jugar') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="mision_individual">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Personaje</label>
                            <select name="personaje" class="form-select" required>
                                @foreach ($personajes as $personaje)
                                    <option value="{{ $personaje }}">{{ $personaje }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mision</label>
                            <select name="mision" class="form-select" required>
                                @foreach ($misiones as $id => $nombre)
                                    <option value="{{ $id }}">{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-jugar btn-naranja">Jugar</button>
                </form>
            @endif

            @if ($modo === 'mision_grupal')
                <h2 style="color:#db2777;">Mision grupal</h2>
                <p class="descripcion">
                    Selecciona un grupo y una mision. El juego revisara XP acumulada e inventario conjunto.
                </p>

                <form method="POST" action="{{ route('juego.jugar') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="mision_grupal">

                    <div class="mb-3">
                        <label class="form-label">Grupo</label>
                        <div class="grupo-check">
                            @foreach ($personajes as $personaje)
                                <label>
                                    <input type="checkbox" name="grupo[]" value="{{ $personaje }}">
                                    {{ $personaje }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mision</label>
                        <select name="mision" class="form-select" required>
                            @foreach ($misiones as $id => $nombre)
                                <option value="{{ $id }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-jugar btn-rosado">Jugar</button>
                </form>
            @endif

            @if ($modo === 'estado')
                <h2 style="color:#16a34a;">Estado del jugador</h2>
                <p class="descripcion">
                    Consulta la informacion actual del personaje: nivel, vida, clase, XP e inventario.
                </p>

                <form method="POST" action="{{ route('juego.jugar') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="perfil">

                    <div class="mb-3">
                        <label class="form-label">Personaje</label>
                        <select name="personaje" class="form-select" required>
                            @foreach ($personajes as $personaje)
                                <option value="{{ $personaje }}">{{ $personaje }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-jugar btn-verde">Jugar</button>
                </form>
            @endif
        </div>

        @if (!empty($resultado))
            <div class="resultado">
                <h3>Resultado</h3>
                <p>{{ $resultado }}</p>
            </div>
        @endif
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mt-4">
            <strong>Hay errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
</body>
</html>