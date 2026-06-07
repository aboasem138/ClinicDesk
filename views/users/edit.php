<?php
$pageTitle = "Edit User";

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

                <input name="name" class="form-control" value="<?= $user['name'] ?>" required>

            </div>

            <div class="form-group">

                <label>Phone</label>

                <input name="phone" class="form-control" value="<?= $user['phone'] ?>">

            </div>

            <button class="btn btn-primary">

                Update

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>