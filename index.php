<?php
require_once __DIR__.'/views/header.php';
$admin_on=!empty($_SESSION['admin_id']);
$tech_on=!empty($_SESSION['tech_id']);
$cust_on=!empty($_SESSION['customer_id']);
?>
<div class="text-center mb-4"><h1 class="h2 mb-1">SportsPro Technical Support</h1><div class="text-muted">Sports management software for the sports enthusiast</div></div>
<div class="card shadow-sm border-0"><div class="card-body">
<h2 class="h5 mb-3">Main Menu</h2>
<div class="list-group">
<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= htmlspecialchars($base_url) ?>/admin/login.php"><span>Administrators</span><span class="text-muted small"><?= $admin_on?'Logged in':'' ?></span></a>
<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= htmlspecialchars($base_url) ?>/technicians/login.php"><span>Technicians</span><span class="text-muted small"><?= $tech_on?'Logged in':'' ?></span></a>
<a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= htmlspecialchars($base_url) ?>/registrations/login.php"><span>Customers</span><span class="text-muted small"><?= $cust_on?'Logged in':'' ?></span></a>
</div>
</div></div>

<?php if($admin_on): ?>
<div class="card shadow-sm border-0 mt-4"><div class="card-body">
<div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 mb-0">Admin Tools</h2><a class="btn btn-sm btn-outline-secondary" href="<?= htmlspecialchars($base_url) ?>/admin/logout.php">Logout</a></div>
<div class="list-group">
<a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/views/admin/manage_products.php">Manage Products</a>
<a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/views/admin/manage_technicians.php">Manage Technicians</a>
<a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/views/admin/manage_customers.php">Manage Customers</a>
<a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/incidents/get_customer.php">Create Incident</a>
<a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/incidents/assign_incident.php">Assign Incident</a>
<a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/incidents/display_incidents.php">Display Incidents</a>
</div>
</div></div>
<?php endif; ?>

<?php if($tech_on): ?>
<div class="card shadow-sm border-0 mt-4"><div class="card-body">
<div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 mb-0">Technician Tools</h2><a class="btn btn-sm btn-outline-secondary" href="<?= htmlspecialchars($base_url) ?>/technicians/logout.php">Logout</a></div>
<div class="list-group"><a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/incidents/update_incident.php">Update Incident</a></div>
</div></div>
<?php endif; ?>

<?php if($cust_on): ?>
<div class="card shadow-sm border-0 mt-4"><div class="card-body">
<div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h5 mb-0">Customer Tools</h2><a class="btn btn-sm btn-outline-secondary" href="<?= htmlspecialchars($base_url) ?>/registrations/logout.php">Logout</a></div>
<div class="list-group"><a class="list-group-item list-group-item-action" href="<?= htmlspecialchars($base_url) ?>/registrations/register_product.php">Register Product</a></div>
</div></div>
<?php endif; ?>

<?php require_once __DIR__.'/views/footer.php'; ?>
