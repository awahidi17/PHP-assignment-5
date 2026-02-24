<?php
declare(strict_types=1);
require_once(__DIR__ . "/../../lib/auth.php");
require_admin();
require __DIR__ . '/../../db/database.php';

// Get product code from form
$productCode = trim($_POST['productCode'] ?? '');
if($productCode===''){header("Location: manage_products.php");exit;}

// Check if product is used in registrations
$stmt=$db->prepare("SELECT COUNT(*) FROM registrations WHERE productCode=:code");
$stmt->execute(['code'=>$productCode]);
$regCount=(int)$stmt->fetchColumn();
$stmt->closeCursor();

// Check if product is used in incidents
$stmt=$db->prepare("SELECT COUNT(*) FROM incidents WHERE productCode=:code");
$stmt->execute(['code'=>$productCode]);
$incCount=(int)$stmt->fetchColumn();
$stmt->closeCursor();

// If used, do not delete (prevents 500 error)
if($regCount>0 || $incCount>0){
  $msg="Cannot delete: this product is used in the database.";
  header("Location: manage_products.php?error=".urlencode($msg));
  exit;
}

// Safe delete
try{
  $stmt=$db->prepare("DELETE FROM products WHERE productCode=:code");
  $stmt->execute(['code'=>$productCode]);
  $stmt->closeCursor();
  header("Location: manage_products.php?success=".urlencode("Product deleted."));
  exit;
}catch(PDOException $e){
  header("Location: manage_products.php?error=".urlencode("Delete failed."));
  exit;
}
?>