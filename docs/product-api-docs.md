# Product API Documentation

## Overview

This document provides comprehensive documentation for the Product API endpoints in the Laravel API Demo project. The API follows RESTful principles and uses Laravel Passport for authentication.

## Base URL
```
https://your-domain.com/api/v1
```

## Authentication

All endpoints require authentication using Bearer tokens (Laravel Passport).

**Header:**
```
Authorization: Bearer {access_token}
```

## Response Format

All API responses follow a consistent JSON structure:

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data here
    }
}
```

For paginated responses:
```json
{
    "data": [...],
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": "..."
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 10,
        "to": 10,
        "total": 50
    }
}
```

## Error Responses

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

---

## Product Endpoints

### 1. Get All Products

**Endpoint:** `GET /products`

**Description:** Retrieve a paginated list of products with optional filtering and sorting.

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `search` | string | No | Search in product name, code, or description (max: 255 chars) |
| `category_id` | integer | No | Filter by category ID |
| `is_active` | boolean | No | Filter by active status |
| `code_prefix` | string | No | Filter by code prefix (max: 10 chars) |
| `min_price` | numeric | No | Minimum price filter (≥ 0) |
| `max_price` | numeric | No | Maximum price filter (≥ min_price) |
| `stock_threshold` | integer | No | Filter products with stock below threshold |
| `expiring_soon_days` | integer | No | Filter products expiring within X days (≥ 1) |
| `is_expired` | boolean | No | Filter expired products |
| `sort_by` | string | No | Sort field: `id`, `name`, `code`, `price`, `stock`, `created_at`, `updated_at` |
| `sort_order` | string | No | Sort direction: `asc`, `desc` |
| `page` | integer | No | Page number (≥ 1) |
| `per_page` | integer | No | Items per page (1-100, default: 10) |

**Example Request:**
```http
GET /api/v1/products?search=laptop&category_id=1&is_active=true&sort_by=name&sort_order=asc&page=1&per_page=15
```

**Example Response:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Gaming Laptop",
            "base_sku": "PRD001-LAPTOP",
            "base_unit": "pieces",
            "description": "High-performance gaming laptop",
            "price": "1299.99",
            "cost": "999.99",
            "stock": 25,
            "min_stock": 5,
            "expiry_date": null,
            "image": "https://example.com/images/laptop.jpg",
            "is_active": true,
            "category": {
                "id": 1,
                "name": "Electronics"
            },
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-20T14:45:00Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/v1/products?page=1",
        "last": "http://localhost/api/v1/products?page=3",
        "prev": null,
        "next": "http://localhost/api/v1/products?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 3,
        "per_page": 15,
        "to": 15,
        "total": 42
    }
}
```

### 2. Get Product Filter Options

**Endpoint:** `GET /products/filter-options`

**Description:** Retrieve available filter options for products (categories, etc.).

**Example Response:**
```json
{
    "success": true,
    "message": "Filter options retrieved successfully",
    "data": {
        "categories": [
            {
                "id": 1,
                "name": "Electronics"
            },
            {
                "id": 2,
                "name": "Clothing"
            }
        ],
        "price_range": {
            "min": 0,
            "max": 2999.99
        },
        "stock_levels": {
            "low_stock_threshold": 10,
            "out_of_stock_count": 5
        }
    }
}
```

### 3. Get Single Product

**Endpoint:** `GET /products/{id}`

**Description:** Retrieve a specific product by ID with detailed information including units.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Product ID |

**Example Response:**
```json
{
    "success": true,
    "message": "Product retrieved successfully",
    "data": {
        "id": 1,
        "name": "Gaming Laptop",
        "base_sku": "PRD001-LAPTOP",
        "base_unit": "pieces",
        "base_unit_id": 1,
        "description": "High-performance gaming laptop",
        "price": "1299.99",
        "cost": "999.99",
        "stock": 25,
        "min_stock": 5,
        "expiry_date": null,
        "image": "https://example.com/images/laptop.jpg",
        "is_active": true,
        "category": {
            "id": 1,
            "name": "Electronics"
        },
        "units": [
            {
                "id": 1,
                "unit_name": "pieces",
                "sku": "PRD001-LAPTOP-PC",
                "conversion_rate": "1.0000",
                "selling_price": "1299.99",
                "is_base_unit": true
            }
        ],
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-20T14:45:00Z"
    }
}
```

### 4. Create Product

**Endpoint:** `POST /products`

**Description:** Create a new product.

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `name` | string | Yes | Product name (max: 255 chars) |
| `description` | string | No | Product description (max: 1000 chars) |
| `price` | numeric | No | Product price (≥ 0) |
| `cost` | numeric | No | Product cost (≥ 0) |
| `stock` | integer | Yes | Stock quantity (≥ 0) |
| `min_stock` | integer | No | Minimum stock threshold (≥ 0) |
| `base_sku` | string | No | Base SKU (max: 255 chars, unique) |
| `base_unit` | string | Yes | Base unit name (max: 255 chars) |
| `category_id` | integer | Yes | Category ID (must exist) |
| `expiry_date` | date | No | Expiry date (format: YYYY-MM-DD, after today) |
| `image` | string | No | Image URL |
| `is_active` | boolean | No | Active status (default: true) |

**Example Request:**
```json
{
    "name": "Gaming Laptop",
    "description": "High-performance gaming laptop with RTX graphics",
    "price": 1299.99,
    "cost": 999.99,
    "stock": 25,
    "min_stock": 5,
    "base_unit": "pieces",
    "category_id": 1,
    "image": "https://example.com/images/laptop.jpg",
    "is_active": true
}
```

**Example Response:**
```json
{
    "success": true,
    "message": "Product created successfully",
    "data": {
        "id": 1,
        "name": "Gaming Laptop",
        "base_sku": "PRD001-PIECES",
        "base_unit": "pieces",
        "base_unit_id": 1,
        // ... other product fields
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

### 5. Update Product

**Endpoint:** `PUT /products/{id}`

**Description:** Update an existing product.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Product ID |

**Request Body:** Same as create product, but `base_unit` is not required for updates.

**Example Response:**
```json
{
    "success": true,
    "message": "Product updated successfully",
    "data": {
        "id": 1,
        "name": "Updated Gaming Laptop",
        // ... other updated fields
        "updated_at": "2024-01-20T14:45:00Z"
    }
}
```

### 6. Delete Product

**Endpoint:** `DELETE /products/{id}`

**Description:** Delete a product.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Product ID |

**Example Response:**
```json
{
    "success": true,
    "message": "Product deleted successfully",
    "data": null
}
```

---

## Product Units Endpoints

### 1. Get Product Units

**Endpoint:** `GET /products/{product}/units`

**Description:** Retrieve all units for a specific product.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `product` | integer | Yes | Product ID |

**Example Response:**
```json
{
    "data": [
        {
            "id": 1,
            "product_id": 1,
            "unit_name": "pieces",
            "sku": "PRD001-LAPTOP-PC",
            "conversion_rate": "1.0000",
            "selling_price": "1299.99",
            "is_base_unit": true,
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-15T10:30:00Z"
        },
        {
            "id": 2,
            "product_id": 1,
            "unit_name": "box",
            "sku": "PRD001-LAPTOP-BOX",
            "conversion_rate": "10.0000",
            "selling_price": "12999.90",
            "is_base_unit": false,
            "created_at": "2024-01-16T09:15:00Z",
            "updated_at": "2024-01-16T09:15:00Z"
        }
    ]
}
```

### 2. Create Product Unit

**Endpoint:** `POST /products/{product}/units`

**Description:** Create a new unit for a specific product.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `product` | integer | Yes | Product ID |

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `unit_name` | string | Yes | Unit name (max: 255 chars) |
| `sku` | string | No | Unit SKU (max: 255 chars, unique) |
| `conversion_rate` | numeric | Yes | Conversion rate to base unit (≥ 0.0001) |
| `selling_price` | numeric | Yes | Selling price for this unit (≥ 0) |
| `is_base_unit` | boolean | No | Whether this is the base unit |

**Example Request:**
```json
{
    "unit_name": "box",
    "sku": "PRD001-LAPTOP-BOX",
    "conversion_rate": 10,
    "selling_price": 12999.90,
    "is_base_unit": false
}
```

**Example Response:**
```json
{
    "success": true,
    "message": "Product unit created successfully",
    "data": {
        "id": 2,
        "product_id": 1,
        "unit_name": "box",
        "sku": "PRD001-LAPTOP-BOX",
        "conversion_rate": "10.0000",
        "selling_price": "12999.90",
        "is_base_unit": false,
        "created_at": "2024-01-16T09:15:00Z",
        "updated_at": "2024-01-16T09:15:00Z"
    }
}
```

### 3. Get Single Product Unit

**Endpoint:** `GET /products/{product}/units/{unit}`

**Description:** Retrieve a specific product unit.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `product` | integer | Yes | Product ID |
| `unit` | integer | Yes | Unit ID |

**Example Response:**
```json
{
    "success": true,
    "message": "Product unit retrieved successfully",
    "data": {
        "id": 2,
        "product_id": 1,
        "unit_name": "box",
        "sku": "PRD001-LAPTOP-BOX",
        "conversion_rate": "10.0000",
        "selling_price": "12999.90",
        "is_base_unit": false,
        "product": {
            "id": 1,
            "name": "Gaming Laptop"
        },
        "created_at": "2024-01-16T09:15:00Z",
        "updated_at": "2024-01-16T09:15:00Z"
    }
}
```

### 4. Update Product Unit

**Endpoint:** `PUT /products/units/{unit}`

**Description:** Update an existing product unit.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `unit` | integer | Yes | Unit ID |

**Request Body:** Same as create product unit.

**Example Response:**
```json
{
    "success": true,
    "message": "Product unit updated successfully",
    "data": {
        "id": 2,
        "product_id": 1,
        "unit_name": "updated_box",
        "sku": "PRD001-LAPTOP-UBOX",
        "conversion_rate": "12.0000",
        "selling_price": "15599.88",
        "is_base_unit": false,
        "updated_at": "2024-01-20T11:30:00Z"
    }
}
```

### 5. Delete Product Unit

**Endpoint:** `DELETE /products/units/{unit}`

**Description:** Delete a product unit.

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `unit` | integer | Yes | Unit ID |

**Example Response:**
```json
{
    "success": true,
    "message": "Product unit deleted successfully",
    "data": null
}
```

---

## Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request data |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation errors |
| 500 | Internal Server Error - Server error |

---

## Error Examples

### Validation Error (422)
```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."],
        "category_id": ["The selected category id is invalid."],
        "stock": ["The stock must be at least 0."]
    }
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "Product not found",
    "data": null
}
```

### Authorization Error (403)
```json
{
    "success": false,
    "message": "You are not authorized to perform this action",
    "data": null
}
```

---

## Notes

1. **Permissions**: All endpoints require appropriate user permissions based on Laravel policies.
2. **Base Units**: When creating a product, a base unit is automatically created.
3. **SKU Generation**: If no `base_sku` is provided, it's automatically generated using the format `PRD{number}-{normalized_unit_name}`.
4. **Stock Management**: Product stock is managed at the base unit level.
5. **Unit Conversion**: When setting a unit as the base unit, stock and conversion rates are automatically recalculated.
6. **Cascade Deletion**: Deleting a product will also delete all associated units.
7. **Base Unit Protection**: The base unit of a product cannot be deleted.

---

## JavaScript API Client

The project includes a JavaScript API client located at `/resources/js/api/products.api.js` with the following functions:

- `getProducts(params)` - Get paginated products with filters
- `getProductFilterOptions()` - Get filter options
- `getProduct(id)` - Get single product
- `createProduct(productData)` - Create new product
- `updateProduct(id, productData)` - Update product
- `deleteProduct(id)` - Delete product
- `getProductUnits(productId)` - Get product units
- `createProductUnit(productId, unitData)` - Create product unit
- `updateProductUnit(unitId, unitData)` - Update product unit
- `deleteProductUnit(unitId)` - Delete product unit

**Usage Example:**
```javascript
import { getProducts, createProduct } from '../api/products.api.js';

// Get products with filters
const response = await getProducts({
    search: 'laptop',
    category_id: 1,
    page: 1,
    per_page: 10
});

// Create a new product
const newProduct = await createProduct({
    name: 'New Product',
    base_unit: 'pieces',
    category_id: 1,
    stock: 100
});
```
