<?php

require __DIR__ . '/vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/app/includes/database.php';

use App\Controllers\AuthController;
use App\Controllers\EventController;

$action = $_GET['action'] ?? 'login';

$authController = new AuthController($conn);
$eventController = new EventController($conn);

switch ($action) {
    case 'login':
        $authController->login();
        break;

    case 'authenticate':
        $authController->authenticate();
        break;

    case 'register':
        $authController->register();
        break;

    case 'register_process':
        $authController->registerProcess();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        require __DIR__ . '/app/includes/auth_check.php';
        require __DIR__ . '/app/views/dashboard.php';
        break;

    case 'events':
        $eventController->index();
        break;

    case 'create':
        $eventController->create();
        break;

    case 'store':
        $eventController->store();
        break;

    case 'edit':
        $eventController->edit();
        break;

    case 'update':
        $eventController->update();
        break;

    case 'delete':
        $eventController->delete();
        break;

    case 'upload':
        $eventController->uploadForm();
        break;

    case 'uploadParticipants':
        $eventController->uploadParticipants();
        break;

    case 'participants':
        $eventController->participants();
        break;

    default:
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header('Location: index.php?action=dashboard');
        } else {
            header('Location: index.php?action=login');
        }
        exit;
}
