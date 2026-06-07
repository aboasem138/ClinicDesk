<?php
$user = Auth::currentUser();
?>

<h1>Patient Dashboard</h1>

<p>
    Welcome
    <?= $user['name']; ?>
</p>