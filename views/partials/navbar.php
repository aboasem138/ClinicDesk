<?php
// views/partials/navbar.php
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo BASE_URL; ?>?page=dashboard" class="nav-link">الرئيسية</a>
        </li>
    </ul>

    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link text-danger" href="<?php echo BASE_URL; ?>?page=auth&action=logout" role="button">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </a>
        </li>
    </ul>
</nav>