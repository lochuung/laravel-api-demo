---
applyTo: "**/Product*.php"
description: "Instruction for generating Product CRUD APIs following UserController architecture"
---

Use the same structure and layers as the UserController when creating ProductController and related files.

- Controller: place in `App\Http\Controllers\Api\V1\ProductController`
    - Use `BaseController` as parent
    - Inject `ProductService` via constructor
    - Return responses using `FormatsApiResponse` trait
    - Use `ProductResource` and `ProductCollection` for output formatting
    - Use `ProductRequest` for validation on store/update
    - Handle `index`, `show`, `store`, `update`, and `destroy` actions

- Request: place `ProductRequest.php` and `ProductIndexRequest.php` in `App\Http\Requests\Products\`
    - Extend `BaseApiRequest`
    - Use rules and authorize like `UserRequest`

- Resource: place in `App\Http\Resources\Products\`
    - Create `ProductResource` for single object and `ProductCollection` for paginated response

- Service: implement `ProductService` in `App\Services\ProductService.php`
    - Use constructor injection with `ProductRepositoryInterface`
    - Follow same method signatures as `UserService`

- Repository: implement interface `ProductRepositoryInterface` and concrete `ProductRepository`
    - Place in `App\Repositories\` and `App\Repositories\Contracts\`

- Model: use `App\Models\Product`

- Naming Convention:
    - Use camelCase for variables and snake_case for database fields
    - Prefix API request classes with `Product`
    - Suffix DTOs with `Resource`, e.g., `ProductResource`, `ProductCollection`

- Follow PSR-4 namespaces and PSR-12 formatting
- Do not include route definitions in controller – routes are defined separately in `routes/api.php`
