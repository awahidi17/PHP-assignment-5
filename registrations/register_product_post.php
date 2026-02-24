<?php
session_start();

require_once(__DIR__ . '/../db/database.php');

$customer_id = $_SESSION['customer_id'] ?? null;
$product_code = trim(filter_input(INPUT_POST, 'product_code', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '');

if (!$customer_id) {
    header('Location: login.php');
    exit;
}

if ($product_code === '') {
    header('Location: register_product.php?error=' . urlencode('Please select a product.'));
    exit;
}

try {
    $q = 'INSERT INTO registrations (customerID, productCode, registrationDate)
          VALUES (:cid, :pcode, NOW())';
    $s = $db->prepare($q);
    $s->bindValue(':cid', $customer_id, PDO::PARAM_INT);
    $s->bindValue(':pcode', $product_code);
    $s->execute();
    $s->closeCursor();

    header('Location: register_product.php?success=' . urlencode("Product ({$product_code}) was registered successfully."));
    exit;
} catch (PDOException $e) {
    // Likely duplicate registration
    header('Location: register_product.php?error=' . urlencode('That product is already registered for this customer.'));
    exit;
}
