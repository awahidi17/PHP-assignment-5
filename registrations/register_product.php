<?php
require_once(__DIR__ . "/../lib/auth.php");
require_customer();
//Register Product

session_start();

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

$customer_id = $_SESSION['customer_id'] ?? null;
if (!$customer_id) {
    header('Location: login.php');
    exit;
}

// Fetch customer
$qC = 'SELECT customerID, firstName, lastName FROM customers WHERE customerID = :cid';
$sC = $db->prepare($qC);
$sC->bindValue(':cid', $customer_id, PDO::PARAM_INT);
$sC->execute();
$customer = $sC->fetch(PDO::FETCH_ASSOC);
$sC->closeCursor();

if (!$customer) {
    unset($_SESSION['customer_id']);
    header('Location: login.php');
    exit;
}

// Products NOT already registered by this customer
$qP = 'SELECT p.productCode, CONCAT(p.name, " ", IFNULL(p.version, "")) AS productLabel
       FROM products p
       WHERE p.productCode NOT IN (
            SELECT r.productCode FROM registrations r WHERE r.customerID = :cid
       )
       ORDER BY p.name';
$sP = $db->prepare($qP);
$sP->bindValue(':cid', $customer_id, PDO::PARAM_INT);
$sP->execute();
$products = $sP->fetchAll(PDO::FETCH_ASSOC);
$sP->closeCursor();

$success = trim(filter_input(INPUT_GET, 'success', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$error = trim(filter_input(INPUT_GET, 'error', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Register Product</h1>
    <p class="text-muted mb-0">SportsPro Technical Support</p>
  </div>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
    <a class="btn btn-light" href="logout.php">Logout</a>
  </div>
</div>

<?php if ($success): ?>
  <div class="alert alert-success" role="alert"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (count($products) === 0): ?>
  <div class="alert alert-info" role="alert">All products are already registered for this customer.</div>
<?php else: ?>
  <div class="card shadow-sm" style="max-width: 720px;">
    <div class="card-body">
      <form method="post" action="register_product_post.php">
        <div class="mb-3">
          <label class="form-label">Customer</label>
          <div class="form-control-plaintext fw-semibold">
            <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']) ?>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Product</label>
          <select class="form-select" name="product_code" required>
            <option value="" disabled selected>Select a product</option>
            <?php foreach ($products as $p): ?>
              <option value="<?= htmlspecialchars($p['productCode']) ?>"><?= htmlspecialchars(trim($p['productLabel'])) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <button class="btn btn-primary" type="submit">Register Product</button>
      </form>
    </div>
  </div>
<?php endif; ?>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
