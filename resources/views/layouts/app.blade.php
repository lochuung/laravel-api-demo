<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel CRUD Demo')</title>
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("css/dashboard.css") }}">
    <script src="{{ asset('/js/auth.js')  }}"></script>
    @stack('styles')
    <style>
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .thumbnail-img {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .thumbnail-img:hover {
            opacity: 0.8;
        }

        .thumbnail-img.active {
            border-color: #0d6efd !important;
            border-width: 2px !important;
        }

        #main-image {
            transition: opacity 0.3s ease;
        }

        .badge {
            font-size: 0.85em;
        }

        .table td {
            vertical-align: middle;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<!-- Loading overlay -->
<x-layouts.navbar/>
@push('scripts')
    <script>
        $(document).ready(async function () {
            window.user = await getCurrentUser();
            if (!window.user) {
                window.location.href = "{{ route('login') }}";
            }
        });
    </script>
@endpush

<main class="container mt-4">
    <div id="loading-overlay" class="d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    @yield('content')
</main>

<x-layouts.footer/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="{{ asset('/js/functions.js')  }}"></script>
@stack('scripts')
</body>
</html>
