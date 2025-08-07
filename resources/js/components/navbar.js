import { waitForUser } from '../utils/common.js';
import { handleLogout } from '../api/auth.api.js';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize navbar functionality
    initializeNavbar();
    initializeUserDropdown();
    initializeMobileMenu();
    setupLogoutHandlers();
    highlightActiveNavItem();
});

function initializeNavbar() {
    // Wait for user data and update navbar
    waitForUser((user) => {
        const userNameElements = document.querySelectorAll('.user-name');
        userNameElements.forEach(element => {
            element.textContent = user.name;
        });
    });
}

function initializeUserDropdown() {
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
}

function initializeMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

function setupLogoutHandlers() {
    const logoutButtons = document.querySelectorAll('#logout, #logout-mobile');
    
    logoutButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            
            if (confirm('Are you sure you want to logout?')) {
                try {
                    await handleLogout();
                    window.location.href = '/login';
                } catch (error) {
                    console.error('Logout failed:', error);
                    alert('Logout failed. Please try again.');
                }
            }
        });
    });
}

function highlightActiveNavItem() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('bg-blue-100', 'text-blue-700');
            link.classList.remove('text-gray-700');
        }
    });
}
