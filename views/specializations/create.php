```php
<?php

$pageTitle = "Create Specialization";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <form method="POST">

            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

            <div class="form-group">

                <label>Name</label>

                <input type="text" name="name" class="form-control" required>

            </div>

            <button type="submit" class="btn btn-success">

                Save

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>