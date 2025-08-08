@push('scripts')
    <script>
        waitForUser(function (user) {
            $('.user-name').text(user.name);
        });

        // Add active class to current page
        $(document).ready(function () {
            const currentPath = window.location.pathname;
            $('.navbar-nav .nav-link').each(function () {
                if ($(this).attr('href') === currentPath) {
                    $(this).addClass('active');
                }
            });

            $('#logout').on('click', function (e) {
                e.preventDefault();
                handleLogout().then(() => {
                    window.location.href = "{{ route('login') }}";
                }).catch(error => {
                    console.error('Logout failed:', error);
                    alert('Logout failed. Please try again.');
                });
            });
        });
    </script>
@endpush

<nav class="navbar navbar-expand-lg modern-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-cube"></i> Laravel Demo
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i> Products
                    </a>
                </li>
                {{--                <li class="nav-item">--}}
                {{--                    <a class="nav-link" href="{{ route('orders.index') }}">--}}
                {{--                        <i class="fas fa-shopping-cart"></i> Orders--}}
                {{--                    </a>--}}
                {{--                </li>--}}
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <span class="user-name"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" id="logout" href="#"><i
                                    class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
