<?php

$pageTitle = "Add Prescription";

require '../views/partials/header.php';
require '../views/partials/navbar.php';
require '../views/partials/sidebar.php';

?>

<div class="content-wrapper">

    <section class="content p-3">

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">

                <label>Diagnosis</label>

                <textarea name="diagnosis" class="form-control" required></textarea>

            </div>

            <div class="form-group">

                <label>Medications</label>

                <textarea name="medications" class="form-control" required></textarea>

            </div>

            <div class="form-group">

                <label>Notes</label>

                <textarea name="notes" class="form-control"></textarea>

            </div>
            <div class="form-group">

                <label>Prescription PDF</label>

                <input type="file" name="prescription_file" class="form-control">

            </div>

            <button class="btn btn-success">

                Save

            </button>

        </form>

    </section>

</div>

<?php require '../views/partials/footer.php'; ?>