<?php

$pageTitle = "My Prescriptions";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Date</th>
                    <th>Diagnosis</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($prescriptions as $p): ?>

                <tr>

                    <td><?= $p['id'] ?></td>

                    <td><?= $p['appt_date'] ?></td>

                    <td><?= $p['diagnosis'] ?></td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>