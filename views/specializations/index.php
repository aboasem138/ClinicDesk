```php
<?php

$pageTitle = "Specializations";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <a href="index.php?page=specializations&action=create" class="btn btn-primary mb-3">

            Add Specialization

        </a>

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($specializations as $spec): ?>

                <tr>

                    <td><?= $spec['id'] ?></td>

                    <td><?= htmlspecialchars($spec['name']) ?></td>

                    <td>

                        <a class="btn btn-danger btn-sm"
                            href="index.php?page=specializations&action=delete&id=<?= $spec['id'] ?>">

                            Delete

                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>