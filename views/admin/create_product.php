<?php
declare(strict_types=1);
require_once(__DIR__ . "/../../lib/auth.php");
require_admin();

require __DIR__ . '/../../db/database.php';

// pass the products code, name, version and release date
$productCode = trim($_POST ['productCode'] ?? ''); 
$name = trim($_POST ['name'] ?? ''); 
$version = trim($_POST ['version'] ?? ''); 
$releaseDate = trim($_POST ['releaseDate'] ?? ''); 

if($productCode === '' || $name === '' || $version === '' || $releaseDate === ""){
    header("Location: add_product.php");
    exit;
}

// Optional: avoid duplicates
$check = $db->prepare("SELECT productCode FROM products WHERE productCode = :code");
$check->execute(['code' => $productCode]);
if ($check->fetch()) {
    // simple redirect back to the form with an error message (could be improved with a session flash message)
    header("Location: add_product.php");
    exit;
}

$stmt = $db->prepare("
    INSERT INTO products (productCode, name, version, releaseDate)
    VALUES (:code, :name, :version, :date)
");

// execute the statement 
$stmt->execute([
    'code'    => $productCode,
    'name'    => $name,
    'version' => $version,
    'date'    => $releaseDate
]);

header("Location: manage_products.php");
exit;
?>
