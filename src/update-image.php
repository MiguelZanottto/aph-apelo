<?php


use config\Config;
use services\FunkosService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkosService.php';
require_once __DIR__ . '/models/Funko.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('No tienes permisos para modificar un funko');
            window.location.href = 'index.php';
          </script>";
    exit;
}

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
        echo "<script type='text/javascript'>
                alert('No existe el funko');
                window.location.href = 'index.php';
                </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Imagen Funko</title>
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

        dl {
            margin-bottom: 30px;
        }

        dt {
            font-weight: bold;
            color: #343a40;
        }

        dd {
            font-size: 24px; /* Tamaño de fuente más grande */
        }

        .img-container {
            text-align: center;
        }

        .img-container img {
            max-width: 300px;
            max-height: 300px;
            border-radius: 5px;
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
            padding: 20px 30px;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 20px 30px;
            font-size: 20px;
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
    <h1 class="mt-3 mb-3">Actualizar Imagen Funko</h1>

    <div class="row">
        <div class="col-md-6">
            <dl class="row">
                <dt class="col-sm-4">ID:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($funko->id); ?></dd>
                <dt class="col-sm-4">Nombre:</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($funko->nombre); ?></dd>
            </dl>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <div class="img-container"><img alt="Funko Image" class="img-fluid"
                                                src="<?php echo htmlspecialchars($funko->imagen); ?>"></div>
            </div>

            <form action="update_image_file.php" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <label for="imagen">Nueva Imagen:</label>
                    <input accept="image/*" class="form-control-file" id="imagen" name="imagen" required type="file">
                    <small class="text-danger"></small>
                    <input name="id" value="<?php echo $id; ?>" type="hidden">
                </div>
                <div class="text-left">
                    <button class="btn btn-primary" type="submit">Actualizar</button>
                    <a class="btn btn-secondary mx-2" href="index.php">Volver</a>
                </div>
            </form>
        </div>
    </div>

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
