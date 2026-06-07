<?php

$pageTitle = "Reports";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <h3>Appointments Report</h3>
        <a href="index.php?page=reports&export=csv" class="btn btn-success mb-3">

            Export CSV

        </a>

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Date</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($appointments as $a): ?>

                <tr>

                    <td><?= $a['id'] ?></td>

                    <td><?= $a['appt_date'] ?></td>

                    <td><?= $a['status'] ?></td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>