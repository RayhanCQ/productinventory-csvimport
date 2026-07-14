<?php

session_start();

require_once 'config/database.php';
require_once 'includes/product_validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Akses tidak valid.';
    header('Location: index.php');
    exit;
}

if (
    !isset($_SESSION['csv_products']) ||
    !is_array($_SESSION['csv_products']) ||
    count($_SESSION['csv_products']) === 0
) {
    $_SESSION['error'] = 'Tidak ada data CSV untuk diimport.';
    header('Location: index.php');
    exit;
}

$products = $_SESSION['csv_products'];

try {

    $pdo->beginTransaction();

    $query = "
        INSERT INTO products
        (
            product_name,
            category,
            price,
            stock,
            supplier
        )
        VALUES
        (
            :product_name,
            :category,
            :price,
            :stock,
            :supplier
        )
    ";

    $statement = $pdo->prepare($query);

    foreach ($products as $product) {

        if (!isValidProductData($product)) {
            throw new RuntimeException('Data CSV tidak valid.');
        }

        $statement->execute([
            ':product_name' => trim($product['product_name']),
            ':category'     => trim($product['category']),
            ':price'        => $product['price'],
            ':stock'        => $product['stock'],
            ':supplier'     => trim($product['supplier'])
        ]);
    }

    $pdo->commit();

    unset($_SESSION['csv_products']);

    $_SESSION['success'] = count($products) . ' produk berhasil diimport.';

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    unset($_SESSION['csv_products']);
    $_SESSION['error'] = 'Import CSV gagal.';
}

header('Location: products.php');
exit;
