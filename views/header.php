<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Base URL (project root) so Home/Back links never 404.
// Works from any folder: /admin, /technicians, /registrations, /incidents, etc.
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$base_url = rtrim(str_replace('\\', '/', dirname($script)), '/');

// If we're inside a module folder, go back to the project root.
$modules = ['views','admin','technicians','registrations','incidents','customers'];
while (true) {
    $last = strtolower(basename($base_url));
    if ($last === '' || $last === '.') break;
    if (!in_array($last, $modules, true)) break;
    $base_url = rtrim(dirname($base_url), '/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportsPro Technical Support</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .icon-bubble{width:44px;height:44px;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;font-size:1.15rem;}
  </style>
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $base_url ?>/index.php">
            SportsPro Technical Support
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>/index.php">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">

