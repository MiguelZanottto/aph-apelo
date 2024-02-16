<?php

use config\Config;
use services\FunkosService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/models/Funko.php';
$session = $sessionService = SessionService::getInstance();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tienda Funko</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="/images/favicon.webp" rel="icon" type="image/png">
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>

    <?php
    echo "</br>";
    echo "<h1>{$session->getWelcomeMessage()}</h1>";
    $config = Config::getInstance();
    ?>
    <br>
    <form action="index.php" class="mb-3" method="get">
        <div class="input-group">
            <input type="text" class="form-control" id="search" name="search" placeholder="Buscar por nombre" aria-label="Buscar" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <div class="container">
        <div class="row">
            <?php
            $searchTerm = $_GET['search'] ?? null;
            $funkosService = new services\FunkosService($config->db);
            $funkos = $funkosService->findAllWithCategoryName($searchTerm);
            ?>
            <?php foreach ($funkos as $funko): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($funko->imagen); ?>" class="card-img-top" alt="Imagen del funko" onerror="this.onerror=null; this.src='../images/image_not_found.jpg'" style="height: 270px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-center" style="font-size: 1.5rem; font-weight: bold;"><?php echo htmlspecialchars($funko->nombre); ?></h5>
                            <p class="card-text text-center" style="font-size: 1rem;">
                                <strong>ID:</strong> <?php echo htmlspecialchars($funko->id); ?><br>
                                <strong>Precio:</strong> <?php echo htmlspecialchars($funko->precio); ?><br>
                                <strong>Stock:</strong> <?php echo htmlspecialchars($funko->stock); ?>
                            </p>
                            <div class="text-center">
                                <div class="btn-group" role="group" aria-label="Acciones">
                                    <a class="btn btn-primary" href="details.php?id=<?php echo $funko->id; ?>">Detalles</a>
                                    <a class="btn btn-secondary" href="update.php?id=<?php echo $funko->id; ?>">Editar</a>
                                    <a class="btn btn-info" href="update-image.php?id=<?php echo $funko->id; ?>">Imagen</a>
                                    <a class="btn btn-danger" href="delete.php?id=<?php echo $funko->id; ?>"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este funko?');">
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
            <a class="btn btn-success btn-lg" href="create.php">Crear Nuevo Funko</a>
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