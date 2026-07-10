<?php

require_once 'config/database.php';
require_once 'includes/header.php';

?>

<div class="row justify-content-center">

    <div class="col-lg-10">

        <div class="mb-5">
            <h1 class="fw-bold">
                Product Inventory
            </h1>

            <p class="text-muted">
                Kelola data produk secara manual atau import data melalui file CSV.
            </p>
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


        <div class="row g-4">

            <!-- Tambah Produk Manual -->
            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-body p-4">

                        <h4 class="card-title fw-bold mb-4">
                            Tambah Produk
                        </h4>

                        <form
                            action="save.php"
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
                                    required
                                >
                            </div>


                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Simpan Produk
                            </button>

                        </form>

                    </div>

                </div>

            </div>


            <!-- Import CSV -->
            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-body p-4">

                        <h4 class="card-title fw-bold mb-4">
                            Import Produk CSV
                        </h4>

                        <p class="text-muted">
                            Pilih file CSV untuk menambahkan beberapa produk sekaligus.
                            Data akan ditampilkan terlebih dahulu sebelum diimport ke database.
                        </p>


                        <form
                            action="preview_import.php"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            <div class="mb-4">

                                <label
                                    for="csv_file"
                                    class="form-label"
                                >
                                    File CSV
                                </label>

                                <input
                                    type="file"
                                    class="form-control"
                                    id="csv_file"
                                    name="csv_file"
                                    accept=".csv"
                                    required
                                >

                            </div>


                            <button
                                type="submit"
                                class="btn btn-success w-100"
                            >
                                Preview CSV
                            </button>

                        </form>


                        <hr class="my-4">


                        <div>

                            <h6 class="fw-bold">
                                Format CSV
                            </h6>

                            <p class="text-muted mb-2">
                                File CSV harus memiliki header berikut:
                            </p>

                            <div class="bg-light border rounded p-3">

                                <code>
                                    product_name,category,price,stock,supplier
                                </code>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="text-center mt-5">

            <a
                href="products.php"
                class="btn btn-outline-dark"
            >
                Lihat Semua Produk
            </a>

        </div>

    </div>

</div>

<?php

require_once 'includes/footer.php';

?>