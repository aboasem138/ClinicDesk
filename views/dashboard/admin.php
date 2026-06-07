```php
<?php

$pageTitle = "Admin Dashboard";

$user = Auth::currentUser();

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <h2>

            Welcome
            <?= htmlspecialchars($user['name']) ?>

        </h2>

        <div class="row">

            <div class="col-md-3">

                <div class="card">

                    <div class="card-body">

                        <h3>
                            <?= $todayAppointments ?>
                        </h3>

                        <p>
                            Today's Appointments
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <h4>User Statistics</h4>

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Role</th>
                    <th>Total</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach($stats as $row): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($row['role']) ?>
                    </td>

                    <td>
                        <?= $row['total'] ?>
                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

        <form method="POST" action="index.php?page=logout">

            <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken(); ?>">

            <button class="btn btn-danger">

                Logout

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>