<?php

$pageTitle = "Book Appointment";

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

                <label>Doctor</label>

                <select name="doctor_id" class="form-control">

                    <?php foreach($doctors as $doctor): ?>

                    <option value="<?= $doctor['id'] ?>">

                        <?= $doctor['name'] ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Date</label>

                <input type="date" name="appt_date" class="form-control" required>

            </div>

            <div class="form-group">

                <label>Time</label>

                <input type="time" name="appt_time" class="form-control" required>

            </div>

            <div class="form-group">

                <label>Reason</label>

                <textarea name="reason" class="form-control"></textarea>

            </div>

            <button class="btn btn-primary">

                Book

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>