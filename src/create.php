<?php

use config\Config;
use models\Funko;
use services\CategoriasService;
use services\FunkosService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/models/Funko.php';
require_once __DIR__ . '/services/CategoriasService.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('No tienes permisos para crear un funko');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$config = Config::getInstance();
$categoriasService = new CategoriasService($config->db);
$funkosService = new FunkosService($config->db);

$categorias = $categoriasService->findAll();

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $categoria = $categoriasService->findByName($categoria);

    $nombre = trim($nombre, "");

    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    }

    if (!isset($precio) || $precio === '') {
        $errores['precio'] = 'El precio es obligatorio.';
    } elseif ($precio < 0) {
        $errores['precio'] = 'El precio no puede ser negativo.';
    }

    if (!isset($stock) || $stock === '') {
        $errores['stock'] = 'El stock es obligatorio.';
    } elseif ($stock < 0) {
        $errores['stock'] = 'El stock no puede ser negativo.';
    }

    if (empty($categoria)) {
        $errores['categoria'] = 'La categoría es obligatoria.';
    }

    if (count($errores) === 0) {

        $funko = new Funko();
        $funko->nombre = $nombre;
        $funko->precio = $precio;
        $funko->stock = $stock;
        $funko->categoriaId = $categoria->id;

        try {
            $funkosService->save($funko);
            echo "<script type='text/javascript'>
                alert('Funko creado correctamente');
                window.location.href = 'index.php';
                </script>";
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
    <title>Crear Funko</title>
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
    <h1>Crear Funko</h1>

    <form action="create.php" method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input class="form-control" id="nombre" name="nombre" type="text" title="El nombre no puede estar vacio." pattern="^(?!\s*$).+"  required>
            <?php if (isset($errores['nombre'])): ?>
                <small class="text-danger"><?php echo $errores['nombre']; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input class="form-control" id="precio" min="0.0" name="precio" step="0.01" type="number" required
                   value="0">
            <?php if (isset($errores['precio'])): ?>
                <small class="text-danger"><?php echo $errores['precio']; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input class="form-control" id="imagen" name="imagen" readonly type="text">
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input class="form-control" id="stock" min="0" name="stock" type="number" required value="0">
            <?php if (isset($errores['stock'])): ?>
                <small class="text-danger"><?php echo $errores['stock']; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select class="form-control" id="categoria" name="categoria" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat->nombre); ?>">
                        <?php echo htmlspecialchars($cat->nombre); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['categoria'])): ?>
                <small class="text-danger"><?php echo $errores['categoria']; ?></small>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <button class="btn btn-primary" type="submit">Crear</button>
            <a class="btn btn-secondary mx-2" href="index.php">Volver</a>
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