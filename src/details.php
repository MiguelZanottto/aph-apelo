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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if ($id === false) {
    header('Location: index.php');
    exit;
} else {
    $config = Config::getInstance();
    $funkosService = new FunkosService($config->db);
    $funko = $funkosService->findById($id);
    if ($funko === null) {
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Funko</title>
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

        .row {
            align-items: center;
        }

        dl.row {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        dt.col-sm-3 {
            font-weight: bold;
            color: #343a40;
        }

        dd.col-sm-9 {
            color: #343a40;
        }

        img.img-fluid {
            max-width: 100%;
            height: auto;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .img-container {
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 16px 30px;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <?php require_once 'header.php'; ?>

    <h1 class="mt-3 mb-3">Detalles del Funko</h1>
    <div class="row">
        <div class="col-md-6">
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($funko->id); ?></dd>
                <dt class="col-sm-3">Nombre:</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($funko->nombre); ?></dd>
                <dt class="col-sm-3">Precio:</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($funko->precio); ?></dd>
                <dt class="col-sm-3">Stock:</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($funko->stock); ?></dd>
                <dt class="col-sm-3">Categoría:</dt>
                <dd class="col-sm-9"><?php echo htmlspecialchars($funko->categoriaNombre); ?></dd>
            </dl>
        </div>
        <div class="col-md-6 text-center">
            <div class="img-container">
                <img class="img-fluid"
                     src="<?php echo htmlspecialchars($funko->imagen); ?>" onerror="this.onerror=null; this.src='../images/image_not_found.jpg'">
            </div>
        </div>
    </div>
    <a class="btn btn-primary" href="index.php">Volver</a>
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