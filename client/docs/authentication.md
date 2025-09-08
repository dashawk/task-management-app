# Authentication Implementation

This document describes the authentication functionality implemented using Pinia for state management and `nuxt-auth-sanctum` for cookie-based authentication.

## Overview

The authentication system uses:
- **nuxt-auth-sanctum**: For Laravel Sanctum integration with cookie-based authentication
- **Pinia**: For state management (store created but currently using direct composables)
- **Vue 3 Composition API**: For reactive components

## Components

### 1. LoginForm Component (`components/auth/LoginForm.vue`)
- Handles user login with email/password
- Includes form validation and error handling
- Shows loading states during authentication
- Uses `useSanctumAuth()` composable directly

### 2. Authentication Store (`stores/auth.ts`)
- Pinia store that wraps nuxt-auth-sanctum composables
- Provides centralized state management for authentication
- Currently not used due to auto-import issues, but ready for future use

### 3. Layout Integration (`layouts/default.vue`)
- Uses `useSanctumUser()` to get current user data
- Handles logout functionality
- Shows user information in navigation bar

## Configuration

### Nuxt Configuration (`nuxt.config.ts`)
```typescript
modules: [
  '@pinia/nuxt',
  ['nuxt-auth-sanctum', {
    baseUrl: 'http://localhost',
    endpoints: {
      login: '/api/login',
      logout: '/api/logout',
      user: '/api/v1/user',
      csrf: '/sanctum/csrf-cookie',
    },
    mode: 'cookie',
    redirect: {
      onLogin: '/',
      onLogout: '/login'
    }
  }]
]
```

## Testing

### Test Credentials
You can use any of these test users to login:

- **Email**: `user1@example.com` | **Password**: `password`
- **Email**: `user2@example.com` | **Password**: `password`
- **Email**: `user3@example.com` | **Password**: `password`
- **Email**: `alice@email.com` | **Password**: `password`

### Testing Flow
1. Navigate to `/login`
2. Enter test credentials
3. Click "Sign In"
4. Should redirect to `/` (home page) on successful login
5. User info should appear in navigation bar
6. Click logout to test logout functionality

## Features

### Login Page
- Email and password fields with validation
- "Remember me" checkbox
- Loading states with spinner
- Error message display
- Responsive design

### Navigation Integration
- Shows authenticated user's name and email
- User avatar with dropdown menu
- Logout functionality
- Automatic redirect handling

### Security
- CSRF protection via Laravel Sanctum
- Cookie-based authentication (secure for SSR)
- Automatic session management
- Protected routes with middleware

## Middleware

### Route Protection
- `sanctum:auth` - Protects routes requiring authentication
- `sanctum:guest` - Redirects authenticated users (login page)

### Usage
```vue
<script setup>
definePageMeta({
  middleware: 'sanctum:auth' // Requires authentication
})
</script>
```

## Future Improvements

1. **Store Auto-import**: Fix Pinia store auto-import issues
2. **Error Handling**: Enhanced error messages and validation
3. **Registration**: Add user registration functionality
4. **Password Reset**: Implement forgot password feature
5. **Profile Management**: User profile editing capabilities
