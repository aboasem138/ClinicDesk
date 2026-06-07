<?php
// views/partials/sidebar.php
$current_role = Auth::user('role');
$user_name = Auth::user('name');
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-light"><b>Clinic</b>Desk</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info text-white">
                <h5>مرحباً، <?php echo htmlspecialchars($user_name); ?></h5>
                <span class="badge badge-success"><?php echo ucfirst($current_role); ?></span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>لوحة التحكم الرئيسية</p>
                    </a>
                </li>

                <?php if ($current_role === 'admin'): ?>
                <li class="nav-header">إدارة النظام</li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=doctors" class="nav-link">
                        <i class="nav-icon fas fa-user-md"></i>
                        <p>إدارة الأطباء</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=specializations" class="nav-link">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>التخصصات الطبية</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=reports" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>التقارير العامة</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($current_role === 'doctor'): ?>
                <li class="nav-header">عيادتي</li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=doctor_appts" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>مواعيدي الطبية</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($current_role === 'patient'): ?>
                <li class="nav-header">خدماتي</li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=appointments&action=book" class="nav-link">
                        <i class="nav-icon fas fa-calendar-plus"></i>
                        <p>حجز موعد جديد</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo BASE_URL; ?>?page=appointments" class="nav-link">
                        <i class="nav-icon fas fa-history"></i>
                        <p>تاريخي ومواعيدي</p>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</aside>