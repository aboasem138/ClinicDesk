<?php
// index.php

// 1. بدء الجلسة الأمنية
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. تضمين ملفات الإعدادات والملفات الأساسية
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/CSRF.php';

// 3. قراءة المسار المطلوب (الافتراضي هو لوحة التحكم dashboard)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// 4. فحص بسيط للمسارات (سيتم توسيعه وربطه بالـ Controllers لاحقاً)
// 4. نظام التوجيه المركزي (Routing)
switch ($page) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        $controller = new AuthController();

        if ($action === 'login') {
            $controller->loginView();
        } elseif ($action === 'handle_login') {
            $controller->handleLogin();
        } elseif ($action === 'logout') {
            $controller->logout();
        } else {
            header("Location: " . BASE_URL . "?page=auth&action=login");
        }
        break;

    case 'dashboard':
        // حماية الصفحة
        Auth::requireLogin();

        // تجميع عناصر اللوحة البرمجية بالترتيب
        require_once __DIR__ . '/views/partials/header.php';
        require_once __DIR__ . '/views/partials/navbar.php';
        require_once __DIR__ . '/views/partials/sidebar.php';

        // هنا يبدأ محتوى الصفحة الداخلي الديناميكي
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-dark">لوحة التحكم الرئيسية</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <?php require_once __DIR__ . '/views/partials/alerts.php'; ?>

            <div class="card mt-3">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">مرحباً بك مجدداً</h3>
                </div>
                <div class="card-body">
                    <h5>أهلاً بك يا دكتور/أستاذ: <strong><?php echo htmlspecialchars(Auth::user('name')); ?></strong>
                    </h5>
                    <p>صلاحياتك الحالية في النظام هي: <span
                            class="badge badge-info"><?php echo htmlspecialchars(Auth::user('role')); ?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
        // تجميع نهاية اللوحة والفوتر
        require_once __DIR__ . '/views/partials/footer.php';
        break;
    case 'appointments':
        require_once __DIR__ . '/controllers/AppointmentController.php';
        $controller = new AppointmentController();
        
        if ($action === 'index') {
            $controller->index();
        } elseif ($action === 'book') {
            $controller->createView();
        } elseif ($action === 'store') {
            $controller->store();
        } else {
            header("Location: " . BASE_URL . "?page=appointments");
        }
        break;
    case 'doctors':
        require_once __DIR__ . '/controllers/DoctorController.php';
        $controller = new DoctorController();
        
        if ($action === 'index') {
            $controller->index();
        } elseif ($action === 'store') {
            $controller->store();
        } elseif ($action === 'toggle') {
            $controller->toggle();
        } else {
            header("Location: " . BASE_URL . "?page=doctors");
        }
        break;
    case 'doctor_appts':
        require_once __DIR__ . '/controllers/DoctorDashboardController.php';
        $controller = new DoctorDashboardController();
        
        if ($action === 'index') {
            $controller->index();
        } elseif ($action === 'change_status') {
            $controller->changeStatus();
        } elseif ($action === 'add_prescription') {
            $controller->addPrescription();
        } else {
            header("Location: " . BASE_URL . "?page=doctor_appts");
        }
        break;
    case 'reports':
        require_once __DIR__ . '/controllers/ReportController.php';
        $controller = new ReportController();
        
        if ($action === 'index') {
            $controller->index();
        } elseif ($action === 'export') {
            $controller->exportCSV();
        } else {
            header("Location: " . BASE_URL . "?page=reports");
        }
        break;
    case 'specializations':
        require_once __DIR__ . '/controllers/SpecializationController.php';
        $controller = new SpecializationController();

        if ($action === 'index') {
            $controller->index();
        } elseif ($action === 'store') {
            $controller->store();
        } elseif ($action === 'delete') {
            $controller->destroy();
        } else {
            header("Location: " . BASE_URL . "?page=specializations");
        }
        break;
    default:
        http_response_code(404);
        echo "<h1>404 - الصفحة غير موجودة</h1>";
        break;
}