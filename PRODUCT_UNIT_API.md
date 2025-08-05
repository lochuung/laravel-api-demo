# ProductUnit API Documentation

## Overview
The ProductUnit API allows you to manage product units for products in the system. Each product can have multiple units (e.g., piece, pack, box) with different conversion rates and prices.

## Routes

### 1. Get Product Units
**GET** `/api/v1/products/{product}/units`

Returns all units for a specific product.

**Example Response:**
```json
{
  "success": true,
  "message": "success",
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "unit_name": "piece",
      "sku": "PRD001-PIECE",
      "barcode": "123456789012",
      "conversion_rate": 1.0000,
      "selling_price": 10.00,
      "is_base_unit": true,
      "created_at": "2024-01-01 12:00:00",
      "updated_at": "2024-01-01 12:00:00"
    }
  ]
}
```

### 2. Create Product Unit
**POST** `/api/v1/products/{product}/units`

Creates a new unit for a specific product.

**Request Body:**
```json
{
  "unit_name": "box",
  "sku": "PRD001-BOX", // optional
  "conversion_rate": 12.0000,
  "selling_price": 115.00,
  "is_base_unit": false
}
```

**Example Response:**
```json
{
  "success": true,
  "message": "success",
  "data": {
    "id": 2,
    "product_id": 1,
    "unit_name": "box",
    "sku": "PRD001-BOX",
    "barcode": "123456789013",
    "conversion_rate": 12.0000,
    "selling_price": 115.00,
    "is_base_unit": false,
    "created_at": "2024-01-01 12:05:00",
    "updated_at": "2024-01-01 12:05:00"
  }
}
```

### 3. Show Product Unit
**GET** `/api/v1/products/{product}/units/{unit}`

Returns a specific unit that belongs to the product.

### 4. Update Product Unit
**PUT** `/api/v1/products/units/{unit}`

Updates a specific product unit.

**Request Body:**
```json
{
  "unit_name": "carton",
  "conversion_rate": 24.0000,
  "selling_price": 220.00,
  "is_base_unit": false
}
```

### 5. Delete Product Unit
**DELETE** `/api/v1/products/units/{unit}`

Deletes a specific product unit. Note: Cannot delete if it's the product's base unit.

## Validation Rules

### Create/Update Unit
- `unit_name`: required, string, max 255 characters
- `sku`: optional, string, max 255 characters, must be unique
- `conversion_rate`: required, numeric, minimum 0.0001
- `selling_price`: required, numeric, minimum 0
- `is_base_unit`: required, boolean

## Business Logic

1. **Automatic SKU Generation**: If no SKU is provided, it will be auto-generated based on product ID and unit name.

2. **Automatic Barcode Generation**: Barcodes are automatically generated based on the SKU using Code128 format.

3. **Base Unit Management**: 
   - Only one unit per product can be the base unit
   - When setting a unit as base unit, other units are automatically unset
   - The product's `base_unit_id` is automatically updated when a unit is set as base

4. **Deletion Protection**: Cannot delete a unit that is set as the product's base unit.

5. **Ownership Validation**: Units can only be accessed through their parent product to ensure data integrity.

## Error Responses

```json
{
  "success": false,
  "message": "The ProductUnit was not found.",
  "error_code": "404"
}
```

```json
{
  "success": false,
  "message": "Cannot delete the base unit. Please change the base unit before deleting.",
  "error_code": "400"
}
```

```json
{
  "success": false,
  "message": "The specified unit does not belong to the product.",
  "error_code": "403"
}
```
