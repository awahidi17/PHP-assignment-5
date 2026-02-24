<?php
require_once('../db/database.php');
require_once('../views/header.php');

$customer_id = filter_input(INPUT_GET, 'customer_id', FILTER_VALIDATE_INT);
if ($customer_id === null || $customer_id === false) {
    $error = 'Invalid customer.';
    require_once('../views/error.php');
    exit;
}

// Load customer
$statement = $db->prepare('SELECT * FROM customers WHERE customerID = :id');
$statement->execute([':id' => $customer_id]);
$customer = $statement->fetch(PDO::FETCH_ASSOC);
$statement->closeCursor();

if (!$customer) {
    $error = 'Customer not found.';
    require_once('../views/error.php');
    exit;
}

$countries = $db->query('SELECT countryCode, countryName FROM countries ORDER BY countryName')
               ->fetchAll(PDO::FETCH_ASSOC);

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $lastName = trim(filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $city = trim(filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $state = trim(filter_input(INPUT_POST, 'state', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $postalCode = trim(filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $countryCode = trim(filter_input(INPUT_POST, 'countryCode', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');

    // Validation: all text boxes required for Assignment 3
    if ($firstName === '' || $lastName === '' || $address === '' || $city === '' || $state === '' || $postalCode === '' || $countryCode === '' || $phone === '' || $email === '') {
        $errors[] = 'A required field was not entered.';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!$errors) {
        $update = 'UPDATE customers
                   SET firstName = :firstName,
                       lastName = :lastName,
                       address = :address,
                       city = :city,
                       state = :state,
                       postalCode = :postalCode,
                       countryCode = :countryCode,
                       phone = :phone,
                       email = :email
                   WHERE customerID = :id';

        $stmt = $db->prepare($update);
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':address' => $address,
            ':city' => $city,
            ':state' => $state,
            ':postalCode' => $postalCode,
            ':countryCode' => $countryCode,
            ':phone' => $phone,
            ':email' => $email,
            ':id' => $customer_id,
        ]);

        // Reload for display
        $statement = $db->prepare('SELECT * FROM customers WHERE customerID = :id');
        $statement->execute([':id' => $customer_id]);
        $customer = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        $success = 'Customer updated.';
    }
}
?>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h1 class="h4 mb-0">View / Update Customer</h1>
            <div class="text-muted small">Customer ID: <?= htmlspecialchars((string)$customer_id) ?></div>
          </div>
          <a class="btn btn-outline-secondary btn-sm" href="index.php">Back</a>
        </div>

        <?php if ($success): ?>
          <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
          <div class="alert alert-danger">
            <?php foreach ($errors as $msg): ?>
              <div><?= htmlspecialchars($msg) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" class="row g-3">
          <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input class="form-control" name="firstName" value="<?= htmlspecialchars($customer['firstName'] ?? '') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="lastName" value="<?= htmlspecialchars($customer['lastName'] ?? '') ?>">
          </div>
          <div class="col-12">
            <label class="form-label">Address</label>
            <input class="form-control" name="address" value="<?= htmlspecialchars($customer['address'] ?? '') ?>">
          </div>
          <div class="col-md-5">
            <label class="form-label">City</label>
            <input class="form-control" name="city" value="<?= htmlspecialchars($customer['city'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">State</label>
            <input class="form-control" name="state" value="<?= htmlspecialchars($customer['state'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Postal Code</label>
            <input class="form-control" name="postalCode" value="<?= htmlspecialchars($customer['postalCode'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Country</label>
            <select class="form-select" name="countryCode">
              <option value="">-- Select --</option>
              <?php foreach ($countries as $c): ?>
                <option value="<?= htmlspecialchars($c['countryCode']) ?>" <?= ($customer['countryCode'] === $c['countryCode']) ? 'selected' : '' ?> >
                  <?= htmlspecialchars($c['countryName']) ?> (<?= htmlspecialchars($c['countryCode']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <div class="form-text">US is the country code for the United States.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone</label>
            <input class="form-control" name="phone" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" value="<?= htmlspecialchars($customer['email'] ?? '') ?>">
          </div>

          <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Update Customer</button>
            <a class="btn btn-link" href="index.php">Search Customers</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once('../views/footer.php'); ?>
