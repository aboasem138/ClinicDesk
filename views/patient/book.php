<?php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="text-dark">طلب حجز موعد عيادة</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php require_once __DIR__ . '/../partials/alerts.php'; ?>

            <div class="card card-primary max-width-600 mx-auto">
                <div class="card-header">
                    <h3 class="card-title text-white">تعبئة بيانات الموعد</h3>
                </div>
                <form action="<?php echo BASE_URL; ?>?page=appointments&action=store" method="post">
                    <?php CSRF::insertField(); ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label>اختر الطبيب والتخصص <span class="text-danger">*</span></label>
                            <select name="doctor_id" class="form-control" required>
                                <option value="">-- اختر الطبيب المناسب --</option>
                                <?php foreach ($doctors as $doc): if($doc['is_active'] == 1): ?>
                                <option value="<?php echo $doc['id']; ?>">
                                    د. <?php echo htmlspecialchars($doc['name']); ?>
                                    ([<?php echo htmlspecialchars($doc['specialization_name']); ?>] - الكشفية:
                                    $<?php echo $doc['consultation_fee']; ?>)
                                </option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>التاريخ المفضل <span class="text-danger">*</span></label>
                                    <input type="date" name="appt_date" class="form-control"
                                        min="<?php echo date('Y-m-b'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>الوقت المفضل <span class="text-danger">*</span></label>
                                    <input type="time" name="appt_time" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>الأعراض أو سبب الحجز</label>
                            <textarea name="reason" class="form-control" rows="3"
                                placeholder="اكتب باختصار ما تشعر به..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>?page=appointments" class="btn btn-default">إلغاء</a>
                        <button type="submit" class="btn btn-success">تأكيد وإرسال الطلب</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>