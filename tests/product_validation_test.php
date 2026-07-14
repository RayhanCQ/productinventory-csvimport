<?php

require_once __DIR__ . '/../includes/product_validation.php';

$valid = [
    'product_name' => 'Keyboard', 'category' => 'Elektronik',
    'price' => '250000.50', 'stock' => '15', 'supplier' => 'Acme'
];

assert(isValidProductData($valid));
assert(!isValidProductData(array_merge($valid, ['price' => '-1'])));
assert(!isValidProductPrice('1.999'));
assert(!isValidProductData(array_merge($valid, ['stock' => '-1'])));
