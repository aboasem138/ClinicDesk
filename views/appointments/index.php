<?php

$pageTitle = "Appointments";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <?php if(Auth::role() === 'patient'): ?>

        <a href="index.php?page=appointments&action=create" class="btn btn-primary mb-3">

            Book Appointment

        </a>

        <?php endif; ?>

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>

                    <?php if(Auth::role() !== 'patient'): ?>

                    <th>Actions</th>

                    <?php endif; ?>

                </tr>

            </thead>

            <tbody>

                <?php foreach($appointments as $appt): ?>

                <tr>

                    <td><?= $appt['id'] ?></td>

                    <td><?= $appt['appt_date'] ?></td>

                    <td><?= $appt['appt_time'] ?></td>

                    <td><?= $appt['status'] ?></td>

                    <?php if(Auth::role() !== 'patient'): ?>

                    <td>

                        <a class="btn btn-info btn-sm"
                            href="index.php?page=appointments&action=status&id=<?= $appt['id'] ?>&status=confirmed">

                            Confirm

                        </a>

                        <a class="btn btn-success btn-sm"
                            href="index.php?page=appointments&action=status&id=<?= $appt['id'] ?>&status=completed">

                            Complete

                        </a>

                        <a class="btn btn-danger btn-sm"
                            href="index.php?page=appointments&action=status&id=<?= $appt['id'] ?>&status=cancelled">

                            Cancel

                        </a>

                    </td>

                    <?php endif; ?>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>