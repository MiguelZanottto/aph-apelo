<?php

use config\Config;
use models\Categoria;
use services\CategoriasService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('No tienes permisos para crear una categoria');
            window.location.href = 'categorias.php';
          </script>";
    exit;
}

$config = Config::getInstance();
$categoriasService = new CategoriasService($config->db);
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nombre = trim($nombre, "");

    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    }

    if (count($errores) === 0) {

        $categoria = new Categoria();
        $categoria->nombre = $nombre;

        try {
            if($categoriasService->save($categoria)){
                echo "<script type='text/javascript'>
                alert('Categoria creada correctamente');
                window.location.href = 'categorias.php';
                </script>";
            } else {
                echo "<script type='text/javascript'>
                alert('Ya existe una categoria con el mismo nombre');
                window.location.href = 'categorias.php';
                </script>";
            }
        } catch (Exception $e) {
            $error = 'Error en el sistema. Por favor intente más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="/images/favicon.webp" rel="icon" type="image/png">
    <style>
        body {
            background-image: url('https://ae01.alicdn.com/kf/Sbb30b217c4274bd8b45097cd1e719ec8b.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            margin-top: 50px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            color: #343a40;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>
    <h1>Crear Categoria</h1>

    <form action="create_categoria.php" method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input class="form-control" id="nombre" name="nombre" type="text" title="El nombre no puede estar vacio." pattern="^(?!\s*$).+"  required>
            <?php if (isset($errores['nombre'])): ?>
                <small class="text-danger"><?php echo $errores['nombre']; ?></small>
            <?php endif; ?>
        </div>
        <div class="text-center">
            <button class="btn btn-primary" type="submit">Crear</button>
            <a class="btn btn-secondary mx-2" href="categorias.php">Volver</a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>