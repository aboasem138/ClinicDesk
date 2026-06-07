<?php
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="text-dark">إدارة حسابات الأطباء</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php require_once __DIR__ . '/../partials/alerts.php'; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title text-white">قائمة الأطباء بالمنظومة</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>التخصص</th>
                                        <th>الهاتف</th>
                                        <th>سعر الكشف</th>
                                        <th>الحالة</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($doctors)): ?>
                                    <tr>
                                        <td colspan="6">لا يوجد أطباء مسجلين حالياً.</td>
                                    </tr>
                                    <?php else: foreach($doctors as $doc): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($doc['name']); ?></strong><br><small
                                                class="text-muted"><?php echo htmlspecialchars($doc['email']); ?></small>
                                        </td>
                                        <td><span
                                                class="badge badge-info"><?php echo htmlspecialchars($doc['specialization_name']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($doc['phone'] ?? '---'); ?></td>
                                        <td>$<?php echo number_format($doc['consultation_fee'], 2); ?></td>
                                        <td>
                                            <?php if($doc['is_active'] == 1): ?>
                                            <span class="badge badge-success">نشط</span>
                                            <?php else: ?>
                                            <span class="badge badge-danger">معطل</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?page=doctors&action=toggle&id=<?php echo $doc['id']; ?>"
                                                class="btn btn-sm <?php echo $doc['is_active'] == 1 ? 'btn-warning' : 'btn-success'; ?>">
                                                <i
                                                    class="fas <?php echo $doc['is_active'] == 1 ? 'fa-user-slash' : 'fa-user-check'; ?>"></i>
                                                <?php echo $doc['is_active'] == 1 ? 'تعطيل الحساب' : 'تفعيل الحساب'; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">إضافة حساب طبيب جديد</h3>
                        </div>
                        <form action="<?php echo BASE_URL; ?>?page=doctors&action=store" method="post">
                            <?php CSRF::insertField(); ?>
                            <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                                <div class="form-group">
                                    <label>الاسم الكامل <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>كلمة المرور المؤقتة <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>رقم الهاتف</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>التخصص الطبي <span class="text-danger">*</span></label>
                                    <select name="specialization_id" class="form-control" required>
                                        <option value="">-- اختر التخصص --</option>
                                        <?php foreach($specializations as $spec): ?>
                                        <option value="<?php echo $spec['id']; ?>">
                                            <?php echo htmlspecialchars($spec['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>رسوم الكشف ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="consultation_fee" class="form-control"
                                        value="0.00" required>
                                </div>
                                <div class="form-group">
                                    <label>نبذة عن الطبيب (Bio)</label>
                                    <textarea name="bio" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>أيام العمل بالأسبوع</label><br>
                                    <div class="row">
                                        <?php 
                                        $all_days = ['Sun'=>'الأحد', 'Mon'=>'الاثنين', 'Tue'=>'الثلاثاء', 'Wed'=>'الأربعاء', 'Thu'=>'الخميس', 'Fri'=>'الجمعة', 'Sat'=>'السبت'];
                                        foreach($all_days as $key => $value): ?>
                                        <div class="col-6">
                                            <input type="checkbox" name="available_days[]" value="<?php echo $key; ?>"
                                                checked> <?php echo $value; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">إنشاء حساب الحكيم</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>