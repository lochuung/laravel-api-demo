# Project Overview

This project is a Laravel 12 web application for managing products, orders, customers, inventory, and users. It provides
a RESTful API and a Blade-based admin dashboard. Frontend behavior is implemented using jQuery and Axios directly in the
`public/js` directory (without a bundler).

## Folder Structure

- `/app`: Contains Laravel logic including models, services, controllers, events, listeners, policies, repositories, and
  resources.
- `/routes`: Route definitions for API and web.
- `/resources/views`: Blade templates for admin UI.
- `/public/js`: JavaScript scripts organized by domain and view, imported directly in Blade via
  `<script type="module" src="...">`.
- `/database`: Migrations, seeders, and model factories.
- `/tests`: PHPUnit tests (unit + feature).
- `/config`: Configuration files for Laravel, Passport, and queue workers.

## Libraries and Frameworks

- Laravel 12 + PHP 8.2 (PSR-12 coding standard).
- Laravel Passport for authentication.
- Bootstrap 5 + Tailwind CSS for styling.
- jQuery and Axios for dynamic interaction.
- Laravel Telescope for debugging and profiling.
- Nyholm PSR-7 with Symfony PSR bridge for HTTP layer.

## Coding Standards

## Coding Standards

### Backend (Laravel)

- API logic must follow this structure:  
  **FormRequest → Controller → Service (via interface) → Repository (via interface) → Model.**

- Controller responsibilities:
    - Inject service interfaces in the constructor (e.g., `DashboardServiceInterface $dashboardService`).
    - Controllers should not contain business logic; all logic must be delegated to service layer.
    - Controller methods must return JSON using `apiSuccessSingleResponse()` or `apiSuccessResponse()` from the
      `FormatsApiResponse` trait (inherited via `BaseController`).
    - Authorization must use `Gate::allows()` or `Gate::denies()` explicitly inside the controller method.
    - Use `AuthorizationException` when authorization fails.
    - HTTP status code must be appropriate (`200` for success, `403` for unauthorized, etc.).

- Example controller pattern:
  ```php
  public function index(): JsonResponse
  {
      if (Gate::denies('is-admin')) {
          throw new AuthorizationException(__('exception.unauthorized'));
      }

      $data = $this->dashboardService->getDashboardData();

      return $this->apiSuccessSingleResponse($data);
  }```

* Use FormRequest classes for all validation (e.g., `LoginRequest`, `RegisterRequest`).

* All responses must follow the structure:

  ```json
  {
    "success": true,
    "message": "localized_message",
    "data": {"...":  "..."}
  }
  ```

* All exceptions in APIs must use `apiErrorResponse()` method which logs context in non-production environments.

* Passwords must always be hashed using `Hash::make()` before storage.

* Services must implement dedicated `ServiceInterface` contracts.

* Repositories must implement corresponding `RepositoryInterface`.

* API response serialization must be handled via `JsonResource` classes like `UserResource`, `AuthResource`.

### JavaScript (public/js)

* Use `type="module"` in Blade templates to enable `import` syntax directly in browser.

* JS structure:

    * `/public/js/api/`: Axios modules (`users.api.js`, `upload.api.js`, etc.)
    * `/public/js/utils/`: Shared helper functions (`formatCurrency`, `debounce`, etc.)
    * `/public/js/views/`: Page-specific scripts (`users/edit.js`, `dashboard.js`)

* Use `window.api` (custom Axios instance) for all API calls.

* Standard form handling flow:

    1. Show loader (`showLoadingState()`),
    2. Fetch or submit data,
    3. Display result (`showSuccessMessage()` or `showErrorMessage()`),
    4. Hide loader (`hideLoadingState()`).

* Use helper functions to improve user experience:

    * `getIdFromUrl()` to extract user/order ID.
    * `debounce()` to optimize input handling.
    * `withButtonControl()` to disable buttons during async calls.
    * `updateUserCount()` to reflect UI state.

* All AJAX errors should be handled with `showError()` or `showErrorMessage()` and show toast notifications.

## JavaScript Guidelines (`public/js`)

- Use `type="module"` to allow `import` statements between JS files in `public/js`.
- Organize JS under the following structure:
    - `public/js/api/`: Axios-based API functions (e.g., `users.api.js`, `upload.api.js`).
    - `public/js/utils/`: Shared logic and helper functions (e.g., `formatCurrency`, `getIdFromUrl`, `debounce`).
    - `public/js/views/`: Scripts tied to specific pages or Blade views (e.g., `edit.js`, `create.js`).
- Example module usage:
  ```js
  import {getUser, updateUser, deleteUser} from '../../api/users.api.js';
  import {uploadImage} from '../../api/upload.api.js';
