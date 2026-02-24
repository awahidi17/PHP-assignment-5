<?php
require_once(__DIR__ . "/../../lib/auth.php");
require_admin();
// Simple redirect so the Admin menu stays consistent with other admin pages.
header('Location: ../../customers/index.php');
exit;
