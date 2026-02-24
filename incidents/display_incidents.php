<?php
require_once(__DIR__ . "/../lib/auth.php");
require_admin();
// Display Incidents (open + closed)

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

$q = 'SELECT i.incidentID, i.title, i.dateOpened, i.dateClosed,
             c.firstName AS cFirst, c.lastName AS cLast,
             p.name AS productName, p.productCode,
             t.firstName AS tFirst, t.lastName AS tLast
      FROM incidents i
      JOIN customers c ON c.customerID = i.customerID
      JOIN products p ON p.productCode = i.productCode
      LEFT JOIN technicians t ON t.techID = i.techID
      ORDER BY i.dateOpened DESC, i.incidentID DESC';
$incidents = $db->query($q)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Display Incidents</h1>
    <p class="text-muted mb-0">All incidents (most recent first).</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
</div>

<?php if (count($incidents) === 0): ?>
  <div class="alert alert-info" role="alert">No incidents found.</div>
<?php else: ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Opened</th>
            <th>Closed</th>
            <th>Technician</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($incidents as $i): ?>
            <tr>
              <td>#<?= (int)$i['incidentID'] ?></td>
              <td><?= htmlspecialchars($i['title']) ?></td>
              <td><?= htmlspecialchars($i['cFirst'] . ' ' . $i['cLast']) ?></td>
              <td><?= htmlspecialchars($i['productName'] . ' (' . $i['productCode'] . ')') ?></td>
              <td><?= htmlspecialchars($i['dateOpened']) ?></td>
              <td><?= $i['dateClosed'] ? htmlspecialchars($i['dateClosed']) : '<span class="text-muted">Open</span>' ?></td>
              <td>
                <?php if ($i['tFirst']): ?>
                  <?= htmlspecialchars($i['tFirst'] . ' ' . $i['tLast']) ?>
                <?php else: ?>
                  <span class="text-muted">Unassigned</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
