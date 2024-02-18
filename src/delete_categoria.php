<?php

use config\Config;
use models\Categoria;
use services\CategoriasService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriasService.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/services/SessionService.php';

$session = SessionService::getInstance();
if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('No tienes permisos para eliminar una categoria');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$categoria = null;

if ($id === false) {
    header('Location: index.php');
    exit;
} else {
    $config = Config::getInstance();
    $categoriasService = new CategoriasService($config->db);
    $categoria = $categoriasService->deleteById($id);
    if ($categoria) {
        echo "<script type='text/javascript'>
                alert('Categoria eliminada correctamente');
                window.location.href = 'categorias.php';
                </script>";
    } else{
        echo "<script type='text/javascript'>
                alert('No se puede eliminar la categoria porque tiene funkos asociados');
                window.location.href = 'categorias.php';
                </script>";
    }
}