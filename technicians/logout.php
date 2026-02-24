<?php
session_start();
unset($_SESSION['tech_id'], $_SESSION['tech_name'], $_SESSION['tech_email']);
header('Location: ../index.php');
exit;
