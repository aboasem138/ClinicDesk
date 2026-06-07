<?php
// views/partials/alerts.php
if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="icon fas fa-check"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
    <?php unset($_SESSION['success']); ?>
</div>
<?php endif;

if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="icon fas fa-ban"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
    <?php unset($_SESSION['error']); ?>
</div>
<?php endif; ?>