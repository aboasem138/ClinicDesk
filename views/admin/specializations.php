<?php
// تضمين أجزاء اللوحة البرمجية المشتركة
require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/navbar.php';
require_once __DIR__ . '/../partials/sidebar.php';
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="text-dark">إدارة التخصصات الطبية</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php require_once __DIR__ . '/../partials/alerts.php'; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-secondary">
                            <h3 class="card-title text-white">التخصصات المسجلة</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 10%">#</th>
                                        <th>اسم التخصص</th>
                                        <th style="width: 20%">العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($specializations)): ?>
                                    <tr>
                                        <td colspan="3">لا توجد تخصصات مضافة حالياً.</td>
                                    </tr>
                                    <?php else: $count = 1; foreach ($specializations as $spec): ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><?php echo htmlspecialchars($spec['name']); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?page=specializations&action=delete&id=<?php echo $spec['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا التخصص؟');">
                                                <i class="fas fa-trash"></i> حذف
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">إضافة تخصص جديد</h3>
                        </div>
                        <form action="<?php echo BASE_URL; ?>?page=specializations&action=store" method="post">
                            <?php CSRF::insertField(); ?>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">اسم التخصص</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="مثال: طب الأطفال" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">حفظ التخصص</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>