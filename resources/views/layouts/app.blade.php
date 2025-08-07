<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel CRUD Demo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        /* Loading overlay */
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Image thumbnails */
        .thumbnail-img {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .thumbnail-img:hover {
            opacity: 0.8;
        }

        .thumbnail-img.active {
            border-color: #3b82f6 !important;
            border-width: 2px !important;
        }

        #main-image {
            transition: opacity 0.3s ease;
        }

        /* Card hover effects */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50 font-inter">
<!-- Loading overlay -->
<x-layouts.navbar/>

<main class="container mx-auto px-4 pt-6 max-w-7xl">
    <div id="loading-overlay" class="hidden">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600">
        </div>
    </div>
    @yield('content')
</main>

<x-layouts.footer/>

@stack('scripts')
</body>
</html>
