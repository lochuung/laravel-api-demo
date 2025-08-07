@vite('resources/js/components/navbar.js')

<nav class="bg-white shadow-lg border-b border-gray-200">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="flex justify-between items-center py-4">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 text-xl font-bold text-gray-800 hover:text-blue-600 transition-colors">
                <i class="fas fa-cube text-blue-600"></i>
                <span>Laravel Demo</span>
            </a>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="md:hidden flex items-center px-3 py-2 border rounded border-gray-400 text-gray-500 hover:text-gray-800 hover:border-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Desktop navigation -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <!-- Navigation Links -->
                <div class="flex space-x-6">
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-link flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </div>

                <!-- User Dropdown -->
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span class="user-name">Loading...</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    
                    <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-user-cog mr-3"></i>
                            Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-cog mr-3"></i>
                            Settings
                        </a>
                        <hr class="my-1 border-gray-200">
                        <a href="#" id="logout" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile navigation -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-2">
                <a href="{{ route('dashboard') }}" class="nav-link flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('products.index') }}" class="nav-link flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <hr class="my-2 border-gray-200">
                <a href="#" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                    <i class="fas fa-user-cog"></i>
                    <span>Profile</span>
                </a>
                <a href="#" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md transition-colors">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="#" id="logout-mobile" class="flex items-center space-x-2 text-red-600 hover:text-red-700 px-3 py-2 rounded-md transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</nav>
