<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};

require_once '../src/Database.php';
require_once '../src/Security.php';
require_once '../src/UserController.php';

use App\UserController;

// Exemple de connexion
$userController = new UserController();
$userController->login($_POST['email'], $_POST['password']);

// Exemple d'enregistrement
$userController->register($_POST['email'], $_POST['password']);
?>