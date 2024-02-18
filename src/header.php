<?php

use services\SessionService;

require_once __DIR__ . '/services/SessionService.php';
$session = SessionService::getInstance();
$username = $session->isLoggedIn() ? $session->getUserName() : 'Invitado';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crear Funko</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="/images/favicon.png" rel="icon" type="image/png">
    <style>
        body {
            padding-top: 56px;
        }

        .navbar {
            background-color: #6f42c1;
        }

        .navbar-brand img {
            margin-right: 10px;
        }

        .navbar-toggler {
            border: none;
            outline: none;
            padding: 10px;
            background-color: #ffffff;
        }

        .navbar-toggler-icon {
            background-color: #6f42c1;
        }

        .navbar-nav a {
            color: #ffffff;
            font-weight: bold;
            transition: color 0.3s ease;
            text-transform: uppercase;
        }

        .navbar-nav a:hover {
            color: #f8f9fa;
        }

        .navbar-text {
            color: #ffffff;
        }

        .navbar-brand {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="index.php">
            <img alt="Logo" class="d-inline-block align-text-top" height="30" src="images/logo_funko.png" width="150">
            Funkos Zanotto's
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Tienda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categorias.php">Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create.php">Agregar Funko</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $session->isLoggedIn() ? 'logout.php' : 'login.php'; ?>">
                        <?php echo $session->isLoggedIn() ? 'Logout' : 'Login'; ?>
                    </a>
                </li>
            </ul>
            <span class="navbar-text">
                 Usuario: <?php echo htmlspecialchars($username); ?>
            </span>
        </div>
    </nav>
</header>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>