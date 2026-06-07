<?php

$pageTitle = "Doctors";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <a href="index.php?page=doctors&action=create" class="btn btn-primary mb-3">

            Add Doctor

        </a>

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>Photo</th>

                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Fee</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($doctors as $doctor): ?>

                <tr>
                    <td>

                        <?php if(!empty($doctor['photo'])): ?>

                        <img src="public/uploads/doctor_photos/<?= $doctor['photo'] ?>" width="60">

                        <?php endif; ?>

                    </td>


                    <td><?= htmlspecialchars($doctor['name']) ?></td>

                    <td>
                        <?= $doctor['specialization_name'] ?>
                    </td>

                    <td>
                        <?= $doctor['consultation_fee'] ?>
                    </td>

                    <td>

                        <a class="btn btn-warning btn-sm"
                            href="index.php?page=doctors&action=edit&id=<?= $doctor['id'] ?>">

                            Edit

                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>