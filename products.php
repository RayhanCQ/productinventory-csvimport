<?php

require_once 'config/database.php';
require_once 'includes/header.php';

$search = trim($_GET['search'] ?? '');

try {
    if ($search !== '') {
        $query = "
            SELECT *
            FROM products
            WHERE product_name LIKE :search
            OR category LIKE :search
            ORDER BY id DESC
        ";

        $statement = $pdo->prepare($query);

        $statement->execute([
            ':search' => '%' . $search . '%'
        ]);
    } else {
        $query = "
            SELECT *
            FROM products
            ORDER BY id DESC
        ";

        $statement = $pdo->query($query);
    }

    $products = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $products = [];

    $_SESSION['error'] = 'Data produk gagal dimuat.';
}

?>

<div class="row justify-content-center">

    <div class="col-lg-12">

        <div
            class="d-flex justify-content-between align-items-center mb-4"
        >

            <div>
                <h1 class="fw-bold mb-1">
                    Daftar Produk
                </h1>

                <p class="text-muted mb-0">
                    Kelola seluruh data produk.
                </p>
            </div>


            <a
                href="index.php"
                class="btn btn-primary"
            >
                Tambah Produk
            </a>

        </div>


        <?php if (isset($_SESSION['success'])): ?>

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <?= htmlspecialchars($_SESSION['success']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>

            </div>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>


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
                    action="products.php"
                    method="GET"
                    class="mb-4"
                >

                    <div class="row g-2">

                        <div class="col-md-10">

                            <input
                                type="text"
                                class="form-control"
                                name="search"
                                placeholder="Cari nama produk atau kategori..."
                                value="<?= htmlspecialchars($search); ?>"
                            >

                        </div>


                        <div class="col-md-2">

                            <button
                                type="submit"
                                class="btn btn-dark w-100"
                            >
                                Cari
                            </button>

                        </div>

                    </div>

                </form>


                <?php if ($search !== ''): ?>

                    <div class="mb-3">

                        <span class="text-muted">
                            Hasil pencarian untuk:
                        </span>

                        <strong>
                            "<?= htmlspecialchars($search); ?>"
                        </strong>

                        <a
                            href="products.php"
                            class="ms-2"
                        >
                            Reset
                        </a>

                    </div>

                <?php endif; ?>


                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle"
                    >

                        <thead class="table-dark">

                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Supplier</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>


                        <tbody>

                            <?php if (count($products) > 0): ?>

                                <?php foreach ($products as $product): ?>

                                    <tr>

                                        <td>
                                            <?= htmlspecialchars($product['id']); ?>
                                        </td>


                                        <td>
                                            <?= htmlspecialchars($product['product_name']); ?>
                                        </td>


                                        <td>
                                            <?= htmlspecialchars($product['category']); ?>
                                        </td>


                                        <td>
                                            Rp <?= number_format(
                                                $product['price'],
                                                0,
                                                ',',
                                                '.'
                                            ); ?>
                                        </td>


                                        <td>
                                            <?= htmlspecialchars($product['stock']); ?>
                                        </td>


                                        <td>
                                            <?= htmlspecialchars($product['supplier']); ?>
                                        </td>


                                        <td>

                                            <div class="d-flex gap-2">

                                                <a
                                                    href="edit.php?id=<?= $product['id']; ?>"
                                                    class="btn btn-warning btn-sm"
                                                >
                                                    Edit
                                                </a>


                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?= $product['id']; ?>"
                                                >
                                                    Delete
                                                </button>

                                            </div>

                                        </td>

                                    </tr>


                                    <div
                                        class="modal fade"
                                        id="deleteModal<?= $product['id']; ?>"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Hapus Produk
                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"
                                                    ></button>

                                                </div>


                                                <div class="modal-body">

                                                    Apakah Anda yakin ingin menghapus produk

                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $product['product_name']
                                                        ); ?>
                                                    </strong>

                                                    ?

                                                </div>


                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        Tidak
                                                    </button>


                                                    <a
                                                        href="delete.php?id=<?= $product['id']; ?>"
                                                        class="btn btn-danger"
                                                    >
                                                        Ya, Hapus
                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted py-4"
                                    >

                                        <?php if ($search !== ''): ?>

                                            Produk tidak ditemukan.

                                        <?php else: ?>

                                            Belum ada data produk.

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

require_once 'includes/footer.php';

?>