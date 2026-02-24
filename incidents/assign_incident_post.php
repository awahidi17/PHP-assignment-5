<?php
require_once(__DIR__ . '/../db/database.php');

$incident_id = filter_input(INPUT_POST, 'incident_id', FILTER_VALIDATE_INT);
$tech_id = filter_input(INPUT_POST, 'tech_id', FILTER_VALIDATE_INT);

if (!$incident_id || !$tech_id) {
    header('Location: assign_incident.php?error=' . urlencode('Please select a technician.'));
    exit;
}

$q = 'UPDATE incidents SET techID = :tid WHERE incidentID = :iid';
$s = $db->prepare($q);
$s->bindValue(':tid', $tech_id, PDO::PARAM_INT);
$s->bindValue(':iid', $incident_id, PDO::PARAM_INT);
$s->execute();
$s->closeCursor();

header('Location: assign_incident.php');
exit;
