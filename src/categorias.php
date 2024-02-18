<?php

use config\Config;
use services\CategoriasService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';
require_once __DIR__ . '/models/Categoria.php';
$session = $sessionService = SessionService::getInstance();
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
            <?php foreach ($categorias as $categoria): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center" style="font-size: 1.5rem; font-weight: bold;"><?php echo htmlspecialchars($categoria->nombre); ?></h5>
                            <p class="card-text text-center" style="font-size: 1rem;">
                                <strong>ID:</strong> <?php echo htmlspecialchars($categoria->id); ?><br>
                            </p>
                            <div class="text-center">
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <a class="btn btn-secondary" href="update_categoria.php?id=<?php echo $categoria->id; ?>">Editar</a>
                                    <a class="btn btn-danger" href="delete_categoria.php?id=<?php echo $categoria->id; ?>"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoria?');">
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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