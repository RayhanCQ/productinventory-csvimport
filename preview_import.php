<?php
session_start();

require_once 'config/database.php';
require_once 'includes/product_validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Akses tidak valid.';
    header('Location: index.php');
    exit;
}

if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Gagal mengunggah file CSV.';
    header('Location: index.php');
    exit;
}

$file = $_FILES['csv_file'];

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($extension !== 'csv') {
    $_SESSION['error'] = 'File harus berformat CSV.';
    header('Location: index.php');
    exit;
}

$uploadDir = __DIR__ . '/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$tempFile = $uploadDir . uniqid('csv_', true) . '.csv';

if (!move_uploaded_file($file['tmp_name'], $tempFile)) {
    $_SESSION['error'] = 'File gagal disimpan.';
    header('Location: index.php');
    exit;
}

$handle = fopen($tempFile, 'r');

if (!$handle) {
    $_SESSION['error'] = 'File CSV tidak dapat dibaca.';
    header('Location: index.php');
    exit;
}

$header = fgetcsv($handle);

if (is_array($header)) {
    $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0] ?? '');
}

$expected = [
    'product_name',
    'category',
    'price',
    'stock',
    'supplier'
];

if ($header !== $expected) {
    fclose($handle);
    unlink($tempFile);

    $_SESSION['error'] =
        'Header CSV tidak sesuai. Gunakan: product_name,category,price,stock,supplier';

    header('Location: index.php');
    exit;
}

$products = [];
$error = '';
$rowNumber = 1;

while (($row = fgetcsv($handle)) !== false) {
    $rowNumber++;

    if ($row === [null]) {
        continue;
    }

    if (count($row) !== 5) {
        $error = "Baris $rowNumber CSV harus memiliki 5 kolom.";
        break;
    }

    $product = [
        'product_name' => trim($row[0]),
        'category' => trim($row[1]),
        'price' => trim($row[2]),
        'stock' => trim($row[3]),
        'supplier' => trim($row[4])
    ];

    if (!isValidProductData($product)) {
        $error = "Data pada baris $rowNumber CSV tidak valid.";
        break;
    }

    $products[] = $product;
}

fclose($handle);
unlink($tempFile);

if ($error !== '') {
    $_SESSION['error'] = $error;
    header('Location: index.php');
    exit;
}

if (count($products) === 0) {
    $_SESSION['error'] = 'Tidak ada data yang dapat diimport.';
    header('Location: index.php');
    exit;
}

$_SESSION['csv_products'] = $products;

require_once 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-11">

        <h1 class="fw-bold mb-3">Preview CSV</h1>
        <p class="text-muted">
            Periksa data sebelum diimport ke database.
        </p>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($products as $index => $product): ?>

                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($product['product_name']); ?></td>
                                <td><?= htmlspecialchars($product['category']); ?></td>
                                <td><?= htmlspecialchars($product['price']); ?></td>
                                <td><?= htmlspecialchars($product['stock']); ?></td>
                                <td><?= htmlspecialchars($product['supplier']); ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

                <div class="mt-4 d-flex gap-2">

                    <form action="import_process.php" method="POST">
                        <button class="btn btn-success" type="submit">
                            Import ke Database
                        </button>
                    </form>

                    <a href="index.php" class="btn btn-secondary">
                        Batal
                    </a>

                </div>

            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
