<?php
require_once(__DIR__ . "/../lib/auth.php");
require_admin();
// Project 6-5 (Assignment 4) - Create Incident

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

$customer_id = filter_input(INPUT_GET, 'customer_id', FILTER_VALIDATE_INT);

if (!$customer_id) {
    header('Location: get_customer.php');
    exit;
}

// Get customer
$q1 = 'SELECT customerID, firstName, lastName FROM customers WHERE customerID = :cid';
$s1 = $db->prepare($q1);
$s1->bindValue(':cid', $customer_id, PDO::PARAM_INT);
$s1->execute();
$customer = $s1->fetch(PDO::FETCH_ASSOC);
$s1->closeCursor();

if (!$customer) {
    header('Location: get_customer.php');
    exit;
}

// Products registered by this customer
$q2 = 'SELECT p.productCode, CONCAT(p.name, " ", IFNULL(p.version, "")) AS productLabel
       FROM registrations r
       JOIN products p ON p.productCode = r.productCode
       WHERE r.customerID = :cid
       ORDER BY p.name';
$s2 = $db->prepare($q2);
$s2->bindValue(':cid', $customer_id, PDO::PARAM_INT);
$s2->execute();
$products = $s2->fetchAll(PDO::FETCH_ASSOC);
$s2->closeCursor();

$title = '';
$description = '';
$product_code = '';
$error = '';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Create Incident</h1>
    <p class="text-muted mb-0">SportsPro Technical Support</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
</div>

<?php if (count($products) === 0): ?>
  <div class="alert alert-warning" role="alert">
    This customer has no registered products. Please register a product first.
    <div class="mt-2">
      <a class="btn btn-sm btn-primary" href="<?= $base_url ?>/registrations/login.php">Go to Register Product</a>
    </div>
  </div>
<?php else: ?>

  <?php if ($error): ?>
    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm" style="max-width: 720px;">
    <div class="card-body">
      <form method="post" action="create_incident_post.php">
        <input type="hidden" name="customer_id" value="<?= (int)$customer['customerID'] ?>">

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
              <option value="<?= htmlspecialchars($p['productCode']) ?>">
                <?= htmlspecialchars(trim($p['productLabel'])) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input class="form-control" type="text" name="title" maxlength="200" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="4" required></textarea>
        </div>

        <button class="btn btn-primary" type="submit">Create Incident</button>
      </form>
    </div>
  </div>

<?php endif; ?>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
