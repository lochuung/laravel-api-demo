<?php

return [
    'import' => [
        'success' => 'Inventory imported successfully.',
        'failed' => 'Failed to import inventory.',
        'invalid_quantity' => 'Import quantity must be greater than 0.',
        'invalid_price' => 'Import price must be greater than 0.',
    ],

    'export' => [
        'success' => 'Inventory exported successfully.',
        'failed' => 'Failed to export inventory.',
        'insufficient_stock' => 'Insufficient stock for export. Available: :available, requested: :requested',
        'invalid_quantity' => 'Export quantity must be greater than 0.',
        'invalid_unit' => 'Invalid unit specified for this product.',
    ],

    'adjust' => [
        'success' => 'Inventory adjusted successfully.',
        'failed' => 'Failed to adjust inventory.',
        'invalid_quantity' => 'New quantity cannot be negative.',
        'same_quantity' => 'New quantity is the same as current stock.',
    ],

    'history' => [
        'not_found' => 'No inventory history found for this product.',
        'fetched' => 'Inventory history retrieved successfully.',
    ],

    'summary' => [
        'not_found' => 'No inventory summary available for this product.',
        'fetched' => 'Inventory summary retrieved successfully.',
    ],

    'errors' => [
        'product_not_found' => 'Product not found.',
        'unit_not_found' => 'Product unit not found.',
        'unit_mismatch' => 'Unit does not belong to this product.',
        'transaction_failed' => 'Inventory transaction failed.',
        'calculation_error' => 'Error calculating inventory conversion.',
    ],
];
