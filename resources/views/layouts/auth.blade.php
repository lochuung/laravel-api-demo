<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Authentication')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom animations and gradients */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-focused {
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center animate-gradient p-5">
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-md lg:max-w-lg">
                <div class="glass-card rounded-3xl shadow-2xl p-10 animate-slide-up hover:shadow-3xl hover:-translate-y-0.5 transition-all duration-300">
                    @yield('card-content')
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add smooth form interactions
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');

            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.classList.add('input-focused');
                    this.closest('.mb-4, .mb-3')?.classList.add('focused');
                });

                input.addEventListener('blur', function () {
                    this.classList.remove('input-focused');
                    this.closest('.mb-4, .mb-3')?.classList.remove('focused');
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
