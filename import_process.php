<?php

session_start();

require_once 'config/database.php';

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

    $success = 0;

    foreach ($products as $product) {

        if (
            trim($product['product_name']) === '' ||
            trim($product['category']) === '' ||
            trim($product['supplier']) === ''
        ) {
            continue;
        }

        if (!is_numeric($product['price'])) {
            continue;
        }

        if (
            filter_var($product['stock'], FILTER_VALIDATE_INT) === false
        ) {
            continue;
        }

        $statement->execute([
            ':product_name' => trim($product['product_name']),
            ':category'     => trim($product['category']),
            ':price'        => $product['price'],
            ':stock'        => $product['stock'],
            ':supplier'     => trim($product['supplier'])
        ]);

        $success++;
    }

    $pdo->commit();

    unset($_SESSION['csv_products']);

    $_SESSION['success'] = $success . ' produk berhasil diimport.';

} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['error'] = 'Import CSV gagal.';
}

header('Location: products.php');
exit;
