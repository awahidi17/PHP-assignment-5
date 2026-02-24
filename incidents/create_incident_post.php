<?php
// Handles Create Incident form submission

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

$customer_id = filter_input(INPUT_POST, 'customer_id', FILTER_VALIDATE_INT);
$product_code = trim(filter_input(INPUT_POST, 'product_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

if (!$customer_id || $product_code === '' || $title === '' || $description === '') {
    header('Location: get_customer.php');
    exit;
}

// Insert incident
$q = 'INSERT INTO incidents (customerID, productCode, techID, dateOpened, title, description)
      VALUES (:cid, :pcode, NULL, NOW(), :title, :descr)';
$s = $db->prepare($q);
$s->bindValue(':cid', $customer_id, PDO::PARAM_INT);
$s->bindValue(':pcode', $product_code);
$s->bindValue(':title', $title);
$s->bindValue(':descr', $description);
$s->execute();
$incident_id = (int)$db->lastInsertId();
$s->closeCursor();
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Incident Created</h1>
    <p class="text-muted mb-0">Your incident has been created successfully.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
</div>

<div class="card shadow-sm" style="max-width: 720px;">
  <div class="card-body">
    <p class="mb-2"><strong>Incident ID:</strong> <?= $incident_id ?></p>
    <p class="mb-2"><strong>Product:</strong> <?= htmlspecialchars($product_code) ?></p>
    <p class="mb-2"><strong>Title:</strong> <?= htmlspecialchars($title) ?></p>
    <p class="mb-0"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($description)) ?></p>

    <div class="mt-3 d-flex gap-2">
      <a class="btn btn-primary" href="<?= $base_url ?>/incidents/get_customer.php">Create Another Incident</a>
      <a class="btn btn-outline-secondary" href="<?= $base_url ?>/incidents/display_incidents.php">Display Incidents</a>
    </div>
  </div>
</div>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
