<?php
require_once('../db/database.php');
require_once('../views/header.php');

$last_name = trim(filter_input(INPUT_GET, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$customers = [];

if ($last_name !== '') {
    $query = 'SELECT customerID, firstName, lastName, city, state, countryCode, email
              FROM customers
              WHERE lastName = :lastName
              ORDER BY firstName';
    $statement = $db->prepare($query);
    $statement->bindValue(':lastName', $last_name);
    $statement->execute();
    $customers = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
}
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Search Customers</h1>
    <p class="text-muted mb-0">Enter a last name to find matching customers.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Back to Home</a>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form class="row g-3" method="get" action="">
      <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($last_name) ?>" placeholder="e.g., Morgan" required>
      </div>
      <div class="col-md-6 d-flex align-items-end gap-2">
        <button class="btn btn-primary" type="submit">Search</button>
        <a class="btn btn-light" href="index.php">Clear</a>
      </div>
    </form>
  </div>
</div>

<?php if ($last_name !== ''): ?>
  <div class="card shadow-sm">
    <div class="card-header bg-white">
      <strong>Results</strong>
      <span class="text-muted">(<?= count($customers) ?> found)</span>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Email</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php if (count($customers) === 0): ?>
          <tr>
            <td colspan="4" class="text-muted">No customers found with last name "<?= htmlspecialchars($last_name) ?>".</td>
          </tr>
        <?php else: ?>
          <?php foreach ($customers as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['firstName'] . ' ' . $c['lastName']) ?></td>
              <td><?= htmlspecialchars(trim(($c['city'] ?? '') . ', ' . ($c['state'] ?? '') . ' ' . ($c['countryCode'] ?? ''))) ?></td>
              <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="view_update.php?customer_id=<?= urlencode($c['customerID']) ?>">Select</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require_once('../views/footer.php'); ?>
