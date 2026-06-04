<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Proyectos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        body {
            background: #eef2f7;
            font-family: Arial, Helvetica, sans-serif;
        }

        .main-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.10);
        }

        .page-title {
            font-weight: 700;
            color: #172033;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .page-subtitle {
            color: #64748b;
            margin-bottom: 25px;
        }

        .btn-new {
            background-color: #1e3a5f;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            color: #ffffff;
        }

        .btn-new:hover {
            background-color: #162c49;
            color: #ffffff;
        }

        .table {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #172033;
            color: #ffffff;
            padding: 14px;
            border-color: #334155;
        }

        .table tbody td,
        .table tbody th {
            padding: 14px;
            vertical-align: middle;
            border-color: #dbe3ed;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-edit {
            background-color: #0f766e;
            border: none;
            color: #ffffff;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .btn-edit:hover {
            background-color: #115e59;
            color: #ffffff;
        }

        .btn-delete {
            background-color: #9f1239;
            border: none;
            color: #ffffff;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .btn-delete:hover {
            background-color: #881337;
            color: #ffffff;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="main-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="page-title">Gestión de Proyectos</h1>
                    <p class="page-subtitle">Consulta la información principal de tus proyectos.</p>
                </div>

                <a href="{{ route('project.create') }}" class="btn btn-new" role="button">
                    Nuevo proyecto
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Fecha de creación</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($proyectos as $proyecto)
                        <tr>
                            <th scope="row">{{ $proyecto->id }}</th>
                            <td>{{ $proyecto->nombre }}</td>
                            <td>{{ $proyecto->descripcion }}</td>
                            <td>{{ $proyecto->created_at }}</td>
                            <td>
                                <a href="{{ route('project.edit', $proyecto->id) }}" class="btn btn-edit btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('project.destroy', $proyecto->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-delete btn-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>