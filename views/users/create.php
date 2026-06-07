<?php
$pageTitle = "Create User";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';
?>

<div class="content-wrapper">

    <section class="content p-3">

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

            <div class="form-group">
                <label>Name</label>
                <input name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input name="phone" class="form-control">
            </div>

            <div class="form-group">
                <label>Role</label>

                <select name="role" class="form-control">

                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                    <option value="admin">Admin</option>

                </select>

            </div>

            <button class="btn btn-success">

                Save

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>