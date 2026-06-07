<?php

session_start();

require_once __DIR__ . '/config/config.php';

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'users':

    require_once __DIR__ . '/controllers/UserController.php';

    $controller = new UserController();

    $action = $_GET['action'] ?? 'index';

    $controller->$action();

    break;

    case 'login':

        require_once __DIR__ . '/controllers/AuthController.php';

        $controller = new AuthController();

        $controller->login();

        break;
    case 'doctors':

    require_once
    __DIR__ . '/controllers/DoctorController.php';

    $controller =
        new DoctorController();

    $action =
        $_GET['action'] ?? 'index';

    $controller->$action();

    break;
    case 'appointments':

    require_once
    __DIR__ .
    '/controllers/AppointmentController.php';

    $controller =
        new AppointmentController();

    $action =
        $_GET['action'] ?? 'index';

    $controller->$action();

    break;
    case 'prescriptions':

    require_once
    __DIR__ .
    '/controllers/PrescriptionController.php';

    $controller =
        new PrescriptionController();

    $action =
        $_GET['action'] ?? 'index';

    $controller->$action();

    break;
    case 'reports':

    require_once
    __DIR__ .
    '/controllers/ReportController.php';

    $controller =
        new ReportController();

    $controller->index();

    break;
    case 'specializations':

    require_once
    __DIR__ .
    '/controllers/SpecializationController.php';

    $controller =
        new SpecializationController();

    $action =
        $_GET['action'] ?? 'index';

    $controller->$action();

    break;
    case 'logout':

        require_once __DIR__ . '/controllers/AuthController.php';

        $controller = new AuthController();

        $controller->logout();

        break;

    case 'dashboard':

        require_once __DIR__ . '/controllers/DashboardController.php';

        $controller = new DashboardController();

        $controller->index();

        break;
    case 'error':

    $code =
        $_GET['code'] ?? 404;

    if ($code == 403) {

        require_once
            __DIR__ .
            '/views/errors/403.php';

    } else {

        require_once
            __DIR__ .
            '/views/errors/404.php';
    }

    break;

    default:

        require_once __DIR__ . '/views/errors/404.php';

        break;
}