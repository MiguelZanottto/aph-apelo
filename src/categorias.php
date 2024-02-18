<?php

use config\Config;
use services\CategoriasService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';
require_once __DIR__ . '/models/Categoria.php';
$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('No tienes permisos para acceder a las categorias');
            window.location.href = 'index.php';
          </script>";
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="/images/favicon.webp" rel="icon" type="image/png">
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>

    <?php
    echo "</br>";
    echo "<h1>Categorias</h1>";
    $config = Config::getInstance();
    ?>
    <br>

    <div class="container">
        <div class="row">
            <?php
            $categoriasService = new CategoriasService($config->db);
            $categorias = $categoriasService->findAll();
            ?>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria->id); ?></td>
                        <td><?php echo htmlspecialchars($categoria->nombre); ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Acciones">
                                <a class="btn btn-secondary" href="update_categoria.php?id=<?php echo $categoria->id; ?>">Editar</a>
                                <a class="btn btn-danger" href="delete_categoria.php?id=<?php echo $categoria->id; ?>"
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoria?');">
                                    Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-3">
            <a class="btn btn-success btn-lg" href="create_categoria.php">Crear Nueva Categoria</a>
        </div>

        <p class="mt-4 text-center" style="font-size: smaller;">
            <?php
            if ($session->isLoggedIn()) {
                echo "<span>Nº de visitas: {$session->getVisitCount()}</span>";
                echo "<span>, desde el último login en: {$session->getLastLoginDate()}</span>";
            }
            ?>
        </p>
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