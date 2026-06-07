<?php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="text-dark">جدول مواعيدي الطبية</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php require_once __DIR__ . '/../partials/alerts.php'; ?>

            <div class="card">
                <div class="card-header bg-dark">
                    <h3 class="card-title text-white">المواعيد المحجوزة من المرضى</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-bordered text-center">
                        <thead>
                            <tr>
                                <th>اسم المريض</th>
                                <th>الهاتف</th>
                                <th>التاريخ</th>
                                <th>الوقت</th>
                                <th>سبب الحجز</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="7">لا توجد مواعيد مضافة في جدولك حالياً.</td>
                            </tr>
                            <?php else: foreach ($appointments as $appt): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($appt['patient_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($appt['patient_phone'] ?? '---'); ?></td>
                                <td><?php echo htmlspecialchars($appt['appt_date']); ?></td>
                                <td><?php echo date('h:i A', strtotime($appt['appt_time'])); ?></td>
                                <td><?php echo htmlspecialchars($appt['reason'] ?? '---'); ?></td>
                                <td>
                                    <?php 
                                        if ($appt['status'] === 'pending') echo '<span class="badge badge-warning">انتظار</span>';
                                        elseif ($appt['status'] === 'confirmed') echo '<span class="badge badge-primary">مؤكد</span>';
                                        elseif ($appt['status'] === 'completed') echo '<span class="badge badge-success">مكتمل ✅</span>';
                                        elseif ($appt['status'] === 'cancelled') echo '<span class="badge badge-danger">ملغي</span>';
                                        ?>
                                </td>
                                <td>
                                    <?php if ($appt['status'] === 'pending'): ?>
                                    <a href="<?php echo BASE_URL; ?>?page=doctor_appts&action=change_status&status=confirmed&id=<?php echo $appt['id']; ?>"
                                        class="btn btn-sm btn-success"><i class="fas fa-check"></i> تأكيد</a>
                                    <a href="<?php echo BASE_URL; ?>?page=doctor_appts&action=change_status&status=cancelled&id=<?php echo $appt['id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('هل تريد إلغاء الموعد؟');"><i class="fas fa-times"></i>
                                        إلغاء</a>
                                    <?php elseif ($appt['status'] === 'confirmed'): ?>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#prescModal<?php echo $appt['id']; ?>">
                                        <i class="fas fa-file-medical"></i> إتمام وكتابة روشتة
                                    </button>
                                    <?php else: ?>
                                    <span class="text-muted">لا توجد عمليات</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <div class="modal fade" id="prescModal<?php echo $appt['id']; ?>"
                                calendar-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content text-right">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title text-white">إتمام الكشف الطبي وإصدار الروشتة</h5>
                                        </div>
                                        <form action="<?php echo BASE_URL; ?>?page=doctor_appts&action=add_prescription"
                                            method="post" enctype="multipart/form-data">
                                            <?php CSRF::insertField(); ?>
                                            <input type="hidden" name="appointment_id"
                                                value="<?php echo $appt['id']; ?>">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>التشخيص الطبي الحالي <span
                                                            class="text-danger">*</span></label>
                                                    <textarea name="diagnosis" class="form-control" rows="2" required
                                                        placeholder="اكتب التشخيص المرضي هنا..."></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>الأدوية والجرعات <span class="text-danger">*</span></label>
                                                    <textarea name="medications" class="form-control" rows="3" required
                                                        placeholder="مثال: Panadol 500mg (كل 8 ساعات)"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>ملاحظات إضافية للمريض</label>
                                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>إرفاق ملف الروشتة المطبوع (PDF فقط)</label>
                                                    <input type="file" name="pdf_file" class="form-control-file"
                                                        accept="application/pdf">
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-success">حفظ وإتمام
                                                    الزيارة</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>