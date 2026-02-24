<?php
require_once(__DIR__ . "/../lib/auth.php");
require_admin();
// Project 6-6 style - Assign Incident to Technician

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

// Get open incidents that are not assigned
$qInc = 'SELECT i.incidentID, i.title, i.dateOpened,
                c.firstName, c.lastName,
                p.name AS productName, p.productCode
         FROM incidents i
         JOIN customers c ON c.customerID = i.customerID
         JOIN products p ON p.productCode = i.productCode
         WHERE i.techID IS NULL AND i.dateClosed IS NULL
         ORDER BY i.dateOpened DESC, i.incidentID DESC';
$incidents = $db->query($qInc)->fetchAll(PDO::FETCH_ASSOC);

$qTech = 'SELECT techID, firstName, lastName FROM technicians ORDER BY lastName, firstName';
$techs = $db->query($qTech)->fetchAll(PDO::FETCH_ASSOC);

$error = trim(filter_input(INPUT_GET, 'error', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Assign Incident</h1>
    <p class="text-muted mb-0">Assign an open incident to a technician.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
</div>

<?php if ($error): ?>
  <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (count($incidents) === 0): ?>
  <div class="alert alert-info" role="alert">There are no unassigned open incidents.</div>
<?php else: ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Incident</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Opened</th>
            <th style="width: 280px;">Technician</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($incidents as $i): ?>
            <tr>
              <td>
                <div class="fw-semibold">#<?= (int)$i['incidentID'] ?> - <?= htmlspecialchars($i['title']) ?></div>
              </td>
              <td><?= htmlspecialchars($i['firstName'] . ' ' . $i['lastName']) ?></td>
              <td><?= htmlspecialchars($i['productName'] . ' (' . $i['productCode'] . ')') ?></td>
              <td><?= htmlspecialchars($i['dateOpened']) ?></td>
              <td>
                <form class="d-flex gap-2" method="post" action="assign_incident_post.php">
                  <input type="hidden" name="incident_id" value="<?= (int)$i['incidentID'] ?>">
                  <select class="form-select form-select-sm" name="tech_id" required>
                    <option value="" disabled selected>Select tech</option>
                    <?php foreach ($techs as $t): ?>
                      <option value="<?= (int)$t['techID'] ?>"><?= htmlspecialchars($t['firstName'] . ' ' . $t['lastName']) ?></option>
                    <?php endforeach; ?>
                  </select>
              </td>
              <td class="text-end">
                  <button class="btn btn-sm btn-primary" type="submit">Assign</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
