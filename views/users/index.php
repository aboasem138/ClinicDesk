<?php
$pageTitle = "Users";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';
require '../views/partials/alerts.php';
?>

<div class="content-wrapper">

    <section class="content p-3">

        <a href="index.php?page=users&action=create" class="btn btn-primary mb-3">

            Add User

        </a>

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach($users as $user): ?>

                <tr>

                    <td><?= $user['id'] ?></td>

                    <td><?= $user['name'] ?></td>

                    <td><?= $user['email'] ?></td>

                    <td><?= $user['role'] ?></td>

                    <td>
                        <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                    </td>

                    <td>

                        <a class="btn btn-warning btn-sm" href="index.php?page=users&action=edit&id=<?= $user['id'] ?>">
                            Edit
                        </a>

                        <a class="btn btn-secondary btn-sm"
                            href="index.php?page=users&action=toggle&id=<?= $user['id'] ?>">
                            Toggle
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>