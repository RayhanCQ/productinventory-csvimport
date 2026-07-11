<?php

session_start();

require_once 'config/database.php';

$id = $_GET['id'] ?? '';

if (
    filter_var($id, FILTER_VALIDATE_INT) === false ||
    $id <= 0
) {
    $_SESSION['error'] = 'ID produk tidak valid.';

    header('Location: products.php');
    exit;
}

try {
    $query = "
        SELECT id
        FROM products
        WHERE id = :id
    ";

    $statement = $pdo->prepare($query);

    $statement->execute([
        ':id' => $id
    ]);

    $product = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $_SESSION['error'] = 'Produk tidak ditemukan.';

        header('Location: products.php');
        exit;
    }

    $query = "
        DELETE FROM products
        WHERE id = :id
    ";

    $statement = $pdo->prepare($query);

    $statement->execute([
        ':id' => $id
    ]);

    $_SESSION['success'] = 'Produk berhasil dihapus.';

} catch (PDOException $e) {
    $_SESSION['error'] = 'Produk gagal dihapus.';
}

header('Location: products.php');
exit;