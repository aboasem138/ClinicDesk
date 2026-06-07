<?php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="text-dark">التقارير العامة للنظام</h1>
            <a href="<?php echo BASE_URL; ?>?page=reports&action=export" class="btn btn-success">
                <i class="fas fa-file-excel"></i> تصدير التقرير بصيغة CSV (Excel)
            </a>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header bg-navy">
                    <h3 class="card-title text-white">سجل كل المواعيد بالمنظومة (مقسم لصفحات)</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-bordered text-center m-0">
                        <thead>
                            <tr>
                                <th># الموعد</th>
                                <th>المريض</th>
                                <th>الطبيب المعالج</th>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="6">لا توجد مواعيد مسجلة في المنظومة حتى الآن.</td>
                            </tr>
                            <?php else: foreach ($appointments as $appt): ?>
                            <tr>
                                <td>#<?php echo $appt['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($appt['patient_name']); ?></strong></td>
                                <td>د. <?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['appt_date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appt_time'])); ?></td>
                                <td>
                                    <?php 
                                        if ($appt['status'] === 'pending') echo '<span class="badge badge-warning">انتظار</span>';
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
                <div class="card-footer clearfix">
                    <?php echo $paginationLinks; ?>
                </div>
            </div>

        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>