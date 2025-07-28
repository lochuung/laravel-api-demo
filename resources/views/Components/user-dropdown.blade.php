<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" 
            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-user me-2"></i>
        <span id="userName">Loading...</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
        <li><h6 class="dropdown-header">Account</h6></li>
        <li><a class="dropdown-item" href="#" id="profileLink">
            <i class="fas fa-user me-2"></i>Profile
        </a></li>
        <li><a class="dropdown-item" href="#" id="settingsLink">
            <i class="fas fa-cog me-2"></i>Settings
        </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="#" id="logoutLink">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a></li>
    </ul>
</div>

<script>
    $(document).ready(function() {
        // Wait for user data to be loaded
        function updateUserInfo() {
            if (window.user && window.user.name) {
                $('#userName').text(window.user.name);
            } else {
                // Try to fetch user data if not available
                getCurrentUser().then(user => {
                    if (user && user.name) {
                        $('#userName').text(user.name);
                    } else {
                        $('#userName').text('Guest');
                    }
                });
            }
        }

        // Initial update
        updateUserInfo();

        // Update every 5 seconds if user data is not available
        const userCheckInterval = setInterval(() => {
            if (window.user && window.user.name) {
                clearInterval(userCheckInterval);
            } else {
                updateUserInfo();
            }
        }, 1000);

        // Handle logout click
        $('#logoutLink').on('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                handleLogout();
            }
        });
    });
</script>
