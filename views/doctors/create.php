<?php

$pageTitle = "Create Doctor";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

            <div class="form-group">

                <label>Doctor User</label>

                <select name="user_id" class="form-control" required>

                    <?php foreach($users as $user): ?>

                    <option value="<?= $user['id'] ?>">

                        <?= $user['name'] ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Specialization</label>

                <select name="specialization_id" class="form-control" required>

                    <?php foreach($specializations as $spec): ?>

                    <option value="<?= $spec['id'] ?>">

                        <?= $spec['name'] ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Bio</label>

                <textarea name="bio" class="form-control"></textarea>

            </div>

            <div class="form-group">

                <label>Consultation Fee</label>

                <input type="number" step="0.01" name="consultation_fee" class="form-control" required>

            </div>

            <div class="form-group">

                <label>Available Days</label>

                <br>

                <?php
$days = [
'Sun',
'Mon',
'Tue',
'Wed',
'Thu',
'Fri',
'Sat'
];
?>

                <?php foreach($days as $day): ?>

                <label>

                    <input type="checkbox" name="available_days[]" value="<?= $day ?>">

                    <?= $day ?>

                </label>

                <br>

                <?php endforeach; ?>

            </div>
            <div class="form-group">

                <label>Doctor Photo</label>

                <input type="file" name="photo" class="form-control">

            </div>

            <button type="submit" class="btn btn-success">

                Save

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>