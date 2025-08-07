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

## 📦 Libraries and Frameworks

Your project uses a modern full-stack development setup combining Laravel 12, Vite, and Tailwind CSS.

### 🔧 Backend (Laravel / PHP)

| Library / Framework    | Purpose                                             |
| ---------------------- | --------------------------------------------------- |
| **Laravel 12**         | Primary backend framework, follows PSR-12 standard  |
| **PHP 8.2**            | Language runtime, supports modern syntax & features |
| **Laravel Passport**   | OAuth2-based authentication and token management    |
| **Nyholm PSR-7**       | Lightweight PSR-7 HTTP message implementation       |
| **Symfony PSR Bridge** | Bridges Laravel with PSR-7 (used with Nyholm)       |
| **Laravel Telescope**  | Debugging and profiling for Laravel requests        |
| **Predis**             | Redis client for Laravel cache/queue/pub-sub        |
| **Laravel Pint**       | Code style formatting (PSR-12 compliant)            |
| **Laravel IDE Helper** | Improves IDE autocomplete and code navigation       |
| **Laravel Pail**       | Local log viewer in terminal with real-time updates |

---

### 🎨 Frontend (Vite + Tailwind + JS)

| Library / Tool              | Purpose                                              |
| --------------------------- | ---------------------------------------------------- |
| **Vite 6**                  | Modern frontend build tool with hot reload & ESM     |
| **Tailwind CSS 4**          | Utility-first CSS framework for rapid UI development |
| **@tailwindcss/forms**      | Tailwind plugin for better form UI styling           |
| **@tailwindcss/typography** | Tailwind plugin for prose styling                    |
| **@tailwindcss/postcss**    | Tailwind plugin for PostCSS compatibility            |
| **Axios**                   | HTTP client for making API requests                  |
| **Lodash**                  | Utility library (e.g., debounce, throttle)           |
| **Notyf**                   | Elegant toast notifications for user feedback        |
| **Toastr**                  | Classic Bootstrap-style notification library         |
| **date-fns / dayjs**        | Lightweight date utilities (formatting, parsing)     |
| **Concurrently** (dev)      | Runs multiple terminal commands in parallel          |
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

# ✅ JavaScript Code Standards (Laravel + Vite + TailwindCSS)

## ⚙️ Setup & Conventions

* Use **[Vite](https://vitejs.dev/)** as the build tool instead of Laravel Mix.
* Use **TailwindCSS** for styling — do not use Bootstrap or CSS frameworks with class conflicts.
* Use **ES Modules** (`import/export`) for all JavaScript.
* Avoid jQuery. Use **Vanilla JS** or **Alpine.js** for DOM manipulation.

---

## 📁 Project Structure (`resources/js/`)

```
resources/js/
├── api/                # Axios-based API modules (e.g., users.api.js)
├── utils/              # Utility functions (e.g., debounce.js, toast.js)
├── pages/              # Page-specific scripts (e.g., login.js, dashboard.js)
├── components/         # UI logic components (e.g., modal.js)
└── app.js              # Main entry point, imported via @vite
```

---

## 🔗 Import Scripts in Blade

Instead of this:

```blade
@push('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush
```

Use this with Vite:

```blade
@vite('resources/js/pages/login.js')
```

Also include the global app assets in your layout:

```blade
@vite(['resources/js/app.js', 'resources/css/app.css'])
```

---

## 📄 Example Blade View (`login.blade.php`)

```blade
@extends('layouts.auth')
@section('title', 'Login')

@section('card-content')
    <form id="login-form">
        <input type="email" id="email" required />
        <input type="password" id="password" required />
        <button type="submit" id="submit">Sign In</button>
    </form>
@endsection

@vite('resources/js/pages/login.js')
```

---

## 📦 API Usage via `api.js`

Your centralized Axios instance (`resources/js/api/api.js`):

```js
import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

export default api;
```

Example API module:

```js
// resources/js/api/auth.api.js
import api from './api.js';

export const login = (payload) => api.post('/login', payload);
```

---

## 🧠 Example Page Script (`resources/js/pages/login.js`)

```js
import {withButtonControl} from '../utils/withButtonControl.js';
import {showSuccessMessage, showErrorMessage} from '../utils/toast.js';
import {login} from '../api/auth.api.js';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const submitBtn = document.getElementById('submit');

    const handleLogin = withButtonControl(async () => {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        try {
            await login({email, password});
            showSuccessMessage('Login successful');
            window.location.href = '/dashboard';
        } catch (error) {
            showErrorMessage(error.response?.data?.message || 'Login failed');
        }
    }, submitBtn);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        handleLogin();
    });
});
```

---

## 🧩 Useful Utility Functions (`resources/js/utils/common.js`)

The `common.js` file contains shared utility functions and helpers used across the entire frontend. These include toast
notifications, button wrappers, date formatters, etc.

| Function / Export                 | Description                                                                |
|-----------------------------------|----------------------------------------------------------------------------|
| `withButtonControl(fn, selector)` | Disables a button during async operations (prevents multiple submissions). |
| `waitForUser(fn, interval?)`      | Polls `window.user` until it exists, then executes a callback.             |
| `formatCurrency(value)`           | Formats a number to Vietnamese Dong (VND) currency.                        |
| `getIdFromUrl(resource?)`         | Extracts an ID (e.g., user ID) from the current URL path.                  |
| `debounce` (from lodash)          | Debounce utility to limit how often a function is called.                  |
| `updateUserCount(total)`          | Updates a `.card-title` element to display total users in UI.              |
| `showError({message, errors})`    | Extracts and shows API error messages (including validation).              |
| `showLoadingState()`              | Shows loading overlay (`#loading-overlay`).                                |
| `hideLoadingState()`              | Hides loading overlay (`#loading-overlay`).                                |
| `showSuccessMessage(msg)`         | Displays a green toast using [Notyf](https://github.com/caroso1222/notyf). |
| `showErrorMessage(msg)`           | Displays a red toast using Notyf.                                          |
| `extractCodePrefix(code)`         | Extracts prefix from a string like `ORD12345 → ORD`.                       |
| `formatDateTime(dateString)`      | Formats a datetime string to `vi-VN` short format.                         |

---

### 🧪 Example Usage

```js
import {
    withButtonControl,
    showSuccessMessage,
    showErrorMessage,
    showLoadingState,
    hideLoadingState,
    getIdFromUrl,
    debounce,
    formatCurrency
} from '../utils/common.js';

const handleSubmit = withButtonControl(async () => {
    showLoadingState();
    try {
        const id = getIdFromUrl('orders');
        // Perform your logic...
        showSuccessMessage('Order submitted successfully!');
    } catch (err) {
        showErrorMessage('Something went wrong.');
    } finally {
        hideLoadingState();
    }
}, '#submit-button');
```

---

## 🔁 Standard Form Flow

1. Call `withButtonControl()` to show loading state and disable submit.
2. Submit data using your API module.
3. Show notification using `showSuccessMessage()` or `showErrorMessage()`.
4. Redirect or update UI.

---

## 🚫 What NOT to Do

* ❌ Do not use `public/js/` — all JavaScript should live in `resources/js/`.
* ❌ Do not use jQuery or `$()` syntax.
* ❌ Do not use CDN for JS libraries — install via npm instead.
* ❌ Do not use Bootstrap components — use TailwindCSS for all styling.

---

## ✨ Vite Config (`vite.config.js`)

```js
import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```
