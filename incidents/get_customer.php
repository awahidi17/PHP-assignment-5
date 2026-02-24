<?php
require_once(__DIR__ . "/../lib/auth.php");
require_admin();
//Get Customer (by email) before creating an incident

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($email === '') {
        $error = 'Please enter an email address.';
    } else {
        $query = 'SELECT customerID, firstName, lastName FROM customers WHERE email = :email';
        $stmt = $db->prepare($query);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (!$customer) {
            $error = 'No customer found for that email address.';
        } else {
            header('Location: create_incident.php?customer_id=' . urlencode($customer['customerID']));
            exit;
        }
    }
}
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Get Customer</h1>
    <p class="text-muted mb-0">You must enter the customer&apos;s email address to select the customer.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
</div>

<?php if ($error): ?>
  <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow-sm" style="max-width: 640px;">
  <div class="card-body">
    <form method="post" action="">
      <label class="form-label">Email</label>
      <div class="d-flex gap-2">
        <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        <button class="btn btn-primary" type="submit">Get Customer</button>
      </div>
    </form>
  </div>
</div>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
