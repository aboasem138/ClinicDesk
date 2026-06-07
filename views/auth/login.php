<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo APP_NAME; ?> | تسجيل الدخول</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet"
        href="<?php echo BASE_URL; ?>public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet"
        href="<?php echo BASE_URL; ?>public/assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/assets/adminlte/dist/css/adminlte.min.css">

    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        text-align: right;
    }

    .input-group .input-group-append .input-group-text {
        border-top-left-radius: .25rem;
        border-bottom-left-radius: .25rem;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group .form-control {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: .25rem;
        border-bottom-right-radius: .25rem;
    }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Clinic</b>Desk</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">سجل دخولك للوصول إلى لوحة التحكم</p>

                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-ban"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); // حذف الرسالة بعد عرضها لكي لا تظهر مجدداً عند عمل ريفريش ?>
                </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>?page=auth&action=handle_login" method="post">

                    <?php CSRF::insertField(); ?>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label factory="" for="remember">
                                    تذكرني
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">دخول</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?php echo BASE_URL; ?>public/assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/assets/adminlte/dist/js/adminlte.min.js"></script>
</body>

</html>