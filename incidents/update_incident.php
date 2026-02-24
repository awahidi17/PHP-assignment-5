<?php
require_once(__DIR__ . "/../lib/auth.php");
require_tech();
// Update Incident (close an incident + update description)

require_once(__DIR__ . '/../db/database.php');
require_once(__DIR__ . '/../views/header.php');

$q = 'SELECT i.incidentID, i.title, i.dateOpened, i.dateClosed, i.description,
             c.firstName AS cFirst, c.lastName AS cLast,
             p.name AS productName, p.productCode,
             t.firstName AS tFirst, t.lastName AS tLast
      FROM incidents i
      JOIN customers c ON c.customerID = i.customerID
      JOIN products p ON p.productCode = i.productCode
      LEFT JOIN technicians t ON t.techID = i.techID
      ORDER BY (i.dateClosed IS NULL) DESC, i.dateOpened DESC, i.incidentID DESC';
$incidents = $db->query($q)->fetchAll(PDO::FETCH_ASSOC);

$incident_id = filter_input(INPUT_GET, 'incident_id', FILTER_VALIDATE_INT);
$selected = null;
if ($incident_id) {
    foreach ($incidents as $row) {
        if ((int)$row['incidentID'] === (int)$incident_id) {
            $selected = $row;
            break;
        }
    }
}

$success = trim(filter_input(INPUT_GET, 'success', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$error = trim(filter_input(INPUT_GET, 'error', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h3 mb-1">Update Incident</h1>
    <p class="text-muted mb-0">Select an incident, update details, and optionally close it.</p>
  </div>
  <a class="btn btn-outline-secondary" href="<?= $base_url ?>/index.php">Home</a>
</div>

<?php if ($success): ?>
  <div class="alert alert-success" role="alert"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header bg-white"><strong>Incidents</strong></div>
      <div class="list-group list-group-flush">
        <?php foreach ($incidents as $i): ?>
          <?php $isActive = $selected && (int)$selected['incidentID'] === (int)$i['incidentID']; ?>
          <a class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>"
             href="update_incident.php?incident_id=<?= (int)$i['incidentID'] ?>">
            <div class="d-flex justify-content-between">
              <div>
                <div class="fw-semibold">#<?= (int)$i['incidentID'] ?> <?= htmlspecialchars($i['title']) ?></div>
                <small class="<?= $isActive ? '' : 'text-muted' ?>">
                  <?= htmlspecialchars($i['cFirst'] . ' ' . $i['cLast']) ?> • <?= htmlspecialchars($i['productCode']) ?>
                </small>
              </div>
              <div>
                <?php if ($i['dateClosed']): ?>
                  <span class="badge text-bg-secondary">Closed</span>
                <?php else: ?>
                  <span class="badge text-bg-success">Open</span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="card shadow-sm">
      <div class="card-header bg-white"><strong>Details</strong></div>
      <div class="card-body">
        <?php if (!$selected): ?>
          <p class="text-muted mb-0">Select an incident from the list to update it.</p>
        <?php else: ?>
          <div class="mb-3">
            <div class="fw-semibold">Customer</div>
            <div><?= htmlspecialchars($selected['cFirst'] . ' ' . $selected['cLast']) ?></div>
          </div>
          <div class="mb-3">
            <div class="fw-semibold">Product</div>
            <div><?= htmlspecialchars($selected['productName'] . ' (' . $selected['productCode'] . ')') ?></div>
          </div>
          <div class="mb-3">
            <div class="fw-semibold">Technician</div>
            <div><?= $selected['tFirst'] ? htmlspecialchars($selected['tFirst'] . ' ' . $selected['tLast']) : '<span class="text-muted">Unassigned</span>' ?></div>
          </div>

          <form method="post" action="update_incident_post.php">
            <input type="hidden" name="incident_id" value="<?= (int)$selected['incidentID'] ?>">

            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" rows="5" required><?= htmlspecialchars($selected['description'] ?? '') ?></textarea>
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="close" id="closeIncident" value="1" <?= $selected['dateClosed'] ? 'checked' : '' ?>>
              <label class="form-check-label" for="closeIncident">
                Mark as closed
              </label>
            </div>

            <button class="btn btn-primary" type="submit">Save</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once(__DIR__ . '/../views/footer.php'); ?>
