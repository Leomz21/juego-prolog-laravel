<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualizar Proyecto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1 class="mb-4">Actualizar Proyecto</h1>

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

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea 
                name="descripcion" 
                id="descripcion" 
                class="form-control" 
                rows="3"
            >{{ $proyecto->descripcion }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>

        <a href="{{ route('project.index') }}" class="btn btn-secondary">
            Volver
        </a>

    </form>

</div>

</body>
</html>