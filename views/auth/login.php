<?php
$pageTitle = "Login";
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title><?= $pageTitle ?></title>

    <link rel="stylesheet" href="public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="public/assets/adminlte/dist/css/adminlte.min.css">

</head>

<body class="hold-transition login-page">

    <div class="login-box">

        <div class="card card-outline card-primary">

            <div class="card-header text-center">

                <h3>ClinicDesk</h3>

            </div>

            <div class="card-body">

                <form method="POST" action="index.php?page=login">

                    <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

                    <div class="form-group">

                        <input type="email" name="email" class="form-control" placeholder="Email" required>

                    </div>

                    <div class="form-group">

                        <input type="password" name="password" class="form-control" placeholder="Password" required>

                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

    <script src="public/assets/adminlte/plugins/jquery/jquery.min.js"></script>

    <script src="public/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="public/assets/adminlte/dist/js/adminlte.min.js"></script>

</body>

</html>