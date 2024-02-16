<?php


use config\Config;
use services\SessionService;
use services\UsersService;


require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/config/Config.php';

$session = SessionService::getInstance();
$config = Config::getInstance();

$error = '';
$usersService = new UsersService($config->db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username || !$password) {
        $error = 'Usuario/a o contraseña inválidos.';
    } else {
        try {
            $user = $usersService->authenticate($username, $password);
            if ($user) {
                $isAdmin = in_array('ADMIN', $user->roles);
                $session->login($user->username, $isAdmin);
                header('Location: index.php');
                exit;
            } else {
                $error = 'Usuario/a o contraseña inválidos.';
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
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="/images/favicon.webp" rel="icon" type="image/png">
</head>
<body>
<div class="container" style="width: 50%; margin-left: auto; margin-right: auto;">
    <?
    require_once 'header.php';
    ?>
    <br>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px; border-radius: 10px;">
            <div class="card-header bg-secondary text-white" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h1 class="text-center">Login</h1>
            </div>
            <div class="card-body">
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label text-purple">Username:</label>
                        <input class="form-control" id="username" name="username" required type="text">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-purple">Password:</label>
                        <input class="form-control" id="password" name="password" required type="password">
                    </div>
                    <?php if ($error): ?>
                        <p class="text-danger"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <button class="btn btn-secondary btn-block" type="submit">¡Entra ya!</button>
                </form>
            </div>
        </div>
    </div>

<?php
require_once 'footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</body>
</html>