<?php
$user = Auth::currentUser();
?>

<h1>Doctor Dashboard</h1>

<p>
    Welcome
    <?= $user['name']; ?>
</p>