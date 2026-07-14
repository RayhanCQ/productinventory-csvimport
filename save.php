<?php

session_start();

require_once 'config/database.php';
require_once 'includes/product_validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$product_name = trim($_POST['product_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = $_POST['price'] ?? '';
$stock = $_POST['stock'] ?? '';
$supplier = trim($_POST['supplier'] ?? '');

if (
    $product_name === '' ||
    $category === '' ||
    $price === '' ||
    $stock === '' ||
    $supplier === ''
) {
    $_SESSION['error'] = 'Semua field wajib diisi.';

    header('Location: index.php');
    exit;
}

if (!isValidProductPrice($price)) {
    $_SESSION['error'] = 'Harga produk tidak valid.';

    header('Location: index.php');
    exit;
}

if (
    filter_var($stock, FILTER_VALIDATE_INT) === false ||
    $stock < 0
) {
    $_SESSION['error'] = 'Stok produk tidak valid.';

    header('Location: index.php');
    exit;
}

try {
    $query = "
        INSERT INTO products (
            product_name,
            category,
            price,
            stock,
            supplier
        )
        VALUES (
            :product_name,
            :category,
            :price,
            :stock,
            :supplier
        )
    ";

    $statement = $pdo->prepare($query);

    $statement->execute([
        ':product_name' => $product_name,
        ':category' => $category,
        ':price' => $price,
        ':stock' => $stock,
        ':supplier' => $supplier
    ]);

    $_SESSION['success'] = 'Produk berhasil ditambahkan.';

} catch (PDOException $e) {
    $_SESSION['error'] = 'Produk gagal ditambahkan.';
}

header('Location: index.php');
exit;
