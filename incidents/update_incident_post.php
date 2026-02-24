<?php
require_once(__DIR__ . '/../db/database.php');

$incident_id = filter_input(INPUT_POST, 'incident_id', FILTER_VALIDATE_INT);
$description = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');
$close = filter_input(INPUT_POST, 'close', FILTER_VALIDATE_INT);

if (!$incident_id || $description === '') {
    header('Location: update_incident.php?error=' . urlencode('Please select an incident and enter a description.'));
    exit;
}

if ($close) {
    $q = 'UPDATE incidents SET description = :d, dateClosed = IF(dateClosed IS NULL, NOW(), dateClosed) WHERE incidentID = :iid';
} else {
    $q = 'UPDATE incidents SET description = :d, dateClosed = NULL WHERE incidentID = :iid';
}

$s = $db->prepare($q);
$s->bindValue(':d', $description);
$s->bindValue(':iid', $incident_id, PDO::PARAM_INT);
$s->execute();
$s->closeCursor();

header('Location: update_incident.php?incident_id=' . urlencode($incident_id) . '&success=' . urlencode('Incident updated.'));
exit;
