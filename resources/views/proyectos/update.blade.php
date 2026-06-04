<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualizar Proyecto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        body {
            background: #eef2f7;
            font-family: Arial, Helvetica, sans-serif;
        }

        .form-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 35px;
            max-width: 750px;
            margin: auto;
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

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        .form-control {
            border-radius: 10px;
            padding: 11px;
            border: 1px solid #cbd5e1;
        }

        .form-control:focus {
            border-color: #1e3a5f;
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.15);
        }

        .form-control[readonly] {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .btn-save {
            background-color: #1e3a5f;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            color: #ffffff;
        }

        .btn-save:hover {
            background-color: #162c49;
            color: #ffffff;
        }

        .btn-back {
            background-color: #64748b;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            color: #ffffff;
        }

        .btn-back:hover {
            background-color: #475569;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="form-card">
            <h1 class="page-title">Actualizar Proyecto</h1>
            <p class="page-subtitle">Modifica la información registrada del proyecto seleccionado.</p>

            <form action="{{ route('project.update', $proyecto->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id" class="form-label">Id</label>
                    <input
                        type="text"
                        name="id"
                        id="id"
                        class="form-control"
                        value="{{ $proyecto->id }}"
                        readonly
                    >
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        class="form-control"
                        value="{{ $proyecto->nombre }}"
                    >
                </div>

                <div class="mb-4">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea
                        name="descripcion"
                        id="descripcion"
                        class="form-control"
                        rows="4"
                    >{{ $proyecto->descripcion }}</textarea>
                </div>

                <button type="submit" class="btn btn-save">
                    Guardar cambios
                </button>

                <a href="{{ route('project.index') }}" class="btn btn-back" role="button">
                    Volver
                </a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>