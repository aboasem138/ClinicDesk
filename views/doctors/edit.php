<?php

$pageTitle = "Edit Doctor";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';

$selectedDays = explode(
    ',',
    $doctor['available_days']
);

?>

<div class="content-wrapper">

    <section class="content p-3">

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

            <div class="form-group">

                <label>Specialization</label>

                <select name="specialization_id" class="form-control">

                    <?php foreach($specializations as $spec): ?>

                    <option value="<?= $spec['id'] ?>" <?= $spec['id'] == $doctor['specialization_id']
? 'selected'
: '' ?>>

                        <?= $spec['name'] ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Bio</label>

                <textarea name="bio" class="form-control"><?= $doctor['bio'] ?></textarea>

            </div>

            <div class="form-group">

                <label>Consultation Fee</label>

                <input type="number" step="0.01" name="consultation_fee" class="form-control"
                    value="<?= $doctor['consultation_fee'] ?>">

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

                    <input type="checkbox" name="available_days[]" value="<?= $day ?>" <?= in_array(
$day,
$selectedDays
)
? 'checked'
: '' ?>>

                    <?= $day ?>

                </label>

                <br>

                <?php endforeach; ?>

            </div>

            <button class="btn btn-primary">

                Update

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>