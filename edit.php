<?php

session_start();

require_once 'config/database.php';
require_once 'includes/product_validation.php';

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
        SELECT *
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

} catch (PDOException $e) {
    $_SESSION['error'] = 'Data produk gagal dimuat.';

    header('Location: products.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

    } elseif (!isValidProductPrice($price)) {

        $_SESSION['error'] = 'Harga produk tidak valid.';

    } elseif (
        filter_var($stock, FILTER_VALIDATE_INT) === false ||
        $stock < 0
    ) {

        $_SESSION['error'] = 'Stok produk tidak valid.';

    } else {

        try {
            $query = "
                UPDATE products
                SET
                    product_name = :product_name,
                    category = :category,
                    price = :price,
                    stock = :stock,
                    supplier = :supplier
                WHERE id = :id
            ";

            $statement = $pdo->prepare($query);

            $statement->execute([
                ':product_name' => $product_name,
                ':category' => $category,
                ':price' => $price,
                ':stock' => $stock,
                ':supplier' => $supplier,
                ':id' => $id
            ]);

            $_SESSION['success'] = 'Produk berhasil diperbarui.';

            header('Location: products.php');
            exit;

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Produk gagal diperbarui.';
        }
    }

    $product['product_name'] = $product_name;
    $product['category'] = $category;
    $product['price'] = $price;
    $product['stock'] = $stock;
    $product['supplier'] = $supplier;
}

require_once 'includes/header.php';

?>

<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="mb-4">

            <h1 class="fw-bold mb-1">
                Edit Produk
            </h1>

            <p class="text-muted mb-0">
                Perbarui data produk.
            </p>

        </div>


        <?php if (isset($_SESSION['error'])): ?>

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <?= htmlspecialchars($_SESSION['error']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>


        <div class="card shadow-sm">

            <div class="card-body p-4">

                <form
                    action="edit.php?id=<?= $product['id']; ?>"
                    method="POST"
                >

                    <div class="mb-3">

                        <label
                            for="product_name"
                            class="form-label"
                        >
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="product_name"
                            name="product_name"
                            value="<?= htmlspecialchars(
                                $product['product_name']
                            ); ?>"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="category"
                            class="form-label"
                        >
                            Kategori
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="category"
                            name="category"
                            value="<?= htmlspecialchars(
                                $product['category']
                            ); ?>"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="price"
                            class="form-label"
                        >
                            Harga
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="price"
                            name="price"
                            min="0"
                            step="0.01"
                            value="<?= htmlspecialchars(
                                $product['price']
                            ); ?>"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label
                            for="stock"
                            class="form-label"
                        >
                            Stok
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="stock"
                            name="stock"
                            min="0"
                            value="<?= htmlspecialchars(
                                $product['stock']
                            ); ?>"
                            required
                        >

                    </div>


                    <div class="mb-4">

                        <label
                            for="supplier"
                            class="form-label"
                        >
                            Supplier
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="supplier"
                            name="supplier"
                            value="<?= htmlspecialchars(
                                $product['supplier']
                            ); ?>"
                            required
                        >

                    </div>


                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Update
                        </button>


                        <a
                            href="products.php"
                            class="btn btn-secondary"
                        >
                            Batal
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

require_once 'includes/footer.php';

?>
