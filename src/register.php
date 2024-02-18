<?php


use config\Config;
use models\User;
use services\SessionService;
use services\UsersService;


require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/UsersService.php';
require_once __DIR__ . '/config/Config.php';

$session = SessionService::getInstance();
$config = Config::getInstance();

$errores = [];
$usersService = new UsersService($config->db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellidos = filter_input(INPUT_POST, 'apellidos',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $nombre = trim($nombre, " ");
    $apellidos = trim($apellidos, " ");
    $email = trim($email, " ");
    $username = trim($username, " ");
    $password = trim($password, " ");

    if(empty($nombre)){
        $errores['nombre'] = "El nombre no puede estar vacio";
    }
    if(empty($apellidos)){
        $errores['nombre'] = "El apellido no puede estar vacio";
    }
    if(empty($email)){
        $errores['email'] = "El email no puede estar vacio";
    }
    if(empty($username)){
        $errores['username'] = "El username no puede estar vacio";
    }
    if(empty($password)){
        $errores['password'] = "La contraseña no puede estar vacia";
    }

    if (count($errores) === 0){
        $user = new User();
        $user->nombre = $nombre;
        $user->apellidos = $apellidos;
        $user->email = $email;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->roles = ['USER'];

        if($usersService->saveUser($user)){
            echo "<script type='text/javascript'>
                alert('Usuario registrado correctamente');
                window.location.href = 'login.php';
                </script>";
        } else {
            echo "<script type='text/javascript'>
                alert('Error. Ya existe un usuario registrado con el mismo username o email');
                window.location.href = 'register.php';
                </script>";
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
                <h1 class="text-center">Registro</h1>
            </div>
            <div class="card-body">
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label text-purple">Nombre:</label>
                        <input class="form-control" id="nombre" name="nombre" required title="El nombre no puede estar vacio." pattern="^(?!\s*$).+"  type="text">
                        <?php if (isset($errores['nombre'])): ?>
                            <small class="text-danger"><?php echo $errores['nombre']; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="apellidos" class="form-label text-purple">Apellidos:</label>
                        <input class="form-control" id="apellidos" name="apellidos"  title="El apellido no puede estar vacio." pattern="^(?!\s*$).+" required type="text">
                        <?php if (isset($errores['apellidos'])): ?>
                            <small class="text-danger"><?php echo $errores['apellidos']; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-purple">Email:</label>
                        <input class="form-control" id="email" name="email" required title="El email no puede estar vacio." pattern="^(?!\s*$).+"  type="email">
                        <?php if (isset($errores['email'])): ?>
                            <small class="text-danger"><?php echo $errores['email']; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label text-purple">Username:</label>
                        <input class="form-control" id="username" name="username" required  title="El username no puede estar vacio." pattern="^(?!\s*$).+"  type="text">
                        <?php if (isset($errores['username'])): ?>
                            <small class="text-danger"><?php echo $errores['username']; ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-purple">Password:</label>
                        <input class="form-control" id="password" name="password" required type="password" title="La contraseña no puede estar vacia." pattern="^(?!\s*$).+" >
                        <?php if (isset($errores['password'])): ?>
                            <small class="text-danger"><?php echo $errores['password']; ?></small>
                        <?php endif; ?>
                    </div>
                    <p class="text-center">¿Ya tienes cuenta? <a href="login.php">Iniciar sesion</a></p>
                    <button class="btn btn-secondary btn-block" type="submit">Registrarme</button>
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