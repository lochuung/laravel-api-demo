<script>
    async function handleLogin({email, password}) {
        try {
            const response = await api.post('/auth/login', {email, password});
            const {access_token, refresh_token} = response.data?.data || {};

            if (access_token) {
                localStorage.setItem('access_token', access_token);
                localStorage.setItem('refresh_token', refresh_token);
                alert('Login successful!');
                window.location.href = '/dashboard';
            } else {
                showError({message: response.data?.message || 'Login failed. Please try again.'});
            }
        } catch (error) {
            const {data} = error.response || {};
            if (data?.errors) {
                showError({errors: data.errors});
            } else {
                showError({message: data?.message || 'An unexpected error occurred. Please try again later.'});
            }
        }
    }

    async function handleRegister({name, email, password, password_confirmation}) {
        try {
            const response = await api.post('/auth/register', {name, email, password, password_confirmation});
            if (response.data?.success) {
                alert('Registration successful! Please check your email to verify your account.');
                window.location.href = '/login';
            } else {
                showError({message: response.data?.message || 'Registration failed. Please try again.'});
            }
        } catch (error) {
            const {data} = error.response || {};
            if (data?.errors) {
                showError({errors: data.errors});
            } else {
                showError({message: data?.message || 'An unexpected error occurred. Please try again later.'});
            }
        }
    }

    async function handleLogout() {
        try {
            const response = await api.post('/auth/logout');
            if (response.data?.success) {
                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                alert('Logout successful!');
                window.location.href = '/login';
            } else {
                showError({message: response.data?.message || 'Logout failed. Please try again.'});
            }
        } catch (error) {
            const {data} = error.response || {};
            showError({message: data?.message || 'An unexpected error occurred. Please try again later.'});
        }
    }

    async function getCurrentUser() {
        try {
            const response = await api.get('/auth/me');
            return response.data?.data || null;
        } catch (error) {
            console.error('Error fetching current user:', error);
            return null;
        }
    }
</script>
