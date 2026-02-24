<?php
// Simple login hub (Admin / Technician / Customer)
$role = trim(filter_input(INPUT_GET,'role',FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
if($role==='admin'){ header('Location: ../admin/login.php'); exit; }
if($role==='technician'){ header('Location: ../technicians/login.php'); exit; }
if($role==='customer'){ header('Location: ../registrations/login.php'); exit; }
require_once __DIR__ . '/../views/header.php';
?>
<h1 class="h3 mb-3">Login</h1>
<p class="text-muted">Choose a user type to log in.</p>
<div class="list-group">
  <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="login.php?role=admin">Administrator<i class="bi bi-chevron-right"></i></a>
  <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="login.php?role=technician">Technician<i class="bi bi-chevron-right"></i></a>
  <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="login.php?role=customer">Customer<i class="bi bi-chevron-right"></i></a>
</div>
<?php require_once __DIR__ . '/../views/footer.php'; ?>
