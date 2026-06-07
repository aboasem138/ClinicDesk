<?php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="text-dark">مواعيدي الطبية</h1>
            <a href="<?php echo BASE_URL; ?>?page=appointments&action=book" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> حجز موعد جديد
            </a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php require_once __DIR__ . '/../partials/alerts.php'; ?>

            <div class="card">
                <div class="card-header bg-lightblue">
                    <h3 class="card-title text-white">سجل المواعيد الخاص بك</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-bordered text-center">
                        <thead>
                            <tr>
                                <th>الطبيب</th>
                                <th>التخصص</th>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>سبب الزيارة</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="6">لا يوجد لديك أي مواعيد مسجلة حالياً.</td>
                            </tr>
                            <?php else: foreach ($appointments as $appt): ?>
                            <tr>
                                <td><strong>د. <?php echo htmlspecialchars($appt['doctor_name']); ?></strong></td>
                                <td><span
                                        class="badge badge-info"><?php echo htmlspecialchars($appt['specialization_name']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($appt['appt_date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appt_time'])); ?></td>
                                <td><?php echo htmlspecialchars($appt['reason'] ?? '---'); ?></td>
                                <td>
                                    <?php 
                                        if ($appt['status'] === 'pending') echo '<span class="badge badge-warning">بانتظار التأكيد</span>';
                                        elseif ($appt['status'] === 'confirmed') echo '<span class="badge badge-primary">مؤكد</span>';
                                        elseif ($appt['status'] === 'completed') echo '<span class="badge badge-success">مكتمل</span>';
                                        elseif ($appt['status'] === 'cancelled') echo '<span class="badge badge-danger">ملغي</span>';
                                        ?>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>