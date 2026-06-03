<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Proyectos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="mb-4">Registro de Proyectos</h1>

    <form action="{{ route('project.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input 
                type="text" 
                class="form-control" 
                id="nombre" 
                name="nombre" 
                placeholder="Ingrese el nombre del proyecto"
            >
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea 
                class="form-control" 
                id="descripcion" 
                name="descripcion" 
                rows="3"
                placeholder="Ingrese la descripción del proyecto"
            ></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>

        <a href="{{ route('project.index') }}" class="btn btn-secondary">
            Volver
        </a>

    </form>

</div>

</body>
</html>