<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## API Documentation

### Product API Endpoints

The Product API provides full CRUD operations for managing products in the system.

#### Authentication
All endpoints require authentication using Bearer token (Laravel Passport).

#### Endpoints

**GET** `/api/v1/products`
- List all products with filtering and pagination
- Available filters:
  - `search`: Search by name, code, or description
  - `category_id`: Filter by category ID
  - `is_active`: Filter by active status (true/false)
  - `is_featured`: Filter by featured status (true/false)
  - `min_price`, `max_price`: Filter by price range
  - `sort_by`: Sort by field (id, name, code, price, stock, created_at, updated_at)
  - `sort_order`: Sort direction (asc, desc)
  - `page`: Page number for pagination
  - `per_page`: Items per page (max 100)

**POST** `/api/v1/products` *(Admin/Moderator only)*
- Create a new product
- Required fields: `name`, `code`, `price`, `stock`, `category_id`
- Optional fields: `description`, `cost`, `sku`, `barcode`, `expiry_date`, `image`, `is_active`, `is_featured`

**GET** `/api/v1/products/{id}`
- Show a specific product by ID

**GET** `/api/v1/products/{id}/category`
- Show a product with category details

**PUT/PATCH** `/api/v1/products/{id}` *(Admin/Moderator only)*
- Update an existing product
- Same fields as POST endpoint

**DELETE** `/api/v1/products/{id}` *(Admin/Moderator only)*
- Delete a product

#### Response Format
All API responses follow a consistent format:
```json
{
  "success": true,
  "data": { ... },
  "message": "Success message"
}
```

#### Example Usage
```bash
# Get all products
GET /api/v1/products?search=laptop&category_id=1&sort_by=price&sort_order=asc

# Create a product
POST /api/v1/products
{
  "name": "MacBook Pro",
  "code": "MBP001",
  "description": "Apple MacBook Pro 13-inch",
  "price": 1299.99,
  "stock": 10,
  "category_id": 1,
  "is_active": true,
  "is_featured": true
}
```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
