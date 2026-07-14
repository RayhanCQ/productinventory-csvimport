<?php

function isValidProductPrice($price): bool
{
    return is_string($price)
        && preg_match('/^\d+(?:\.\d{1,2})?$/', $price)
        && (float) $price <= 99999999.99;
}

function isValidProductData(array $product): bool
{
    $stock = $product['stock'] ?? null;

    return trim($product['product_name'] ?? '') !== ''
        && trim($product['category'] ?? '') !== ''
        && trim($product['supplier'] ?? '') !== ''
        && isValidProductPrice($product['price'] ?? null)
        && filter_var($stock, FILTER_VALIDATE_INT) !== false
        && $stock >= 0;
}
