<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation Bar -->
    <NavigationBar
      :user="currentUser"
      search-placeholder="Search tasks, projects..."
      :show-search="true"
      @search="handleSearch"
      @logo-click="handleLogoClick"
      @profile-click="handleProfileClick"
      @settings-click="handleSettingsClick"
      @logout-click="handleLogoutClick"
    />

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
import type { User } from '~~/types/navigation'

// Global types are now available without imports

// Mock user data - replace with actual user data from your auth system
const currentUser = ref<User>({
  id: '1',
  name: 'John Doe',
  email: 'john.doe@example.com',
  avatar: '' // Leave empty to show initials
})

// Event handlers
const handleSearch = (query: string) => {
  console.log('Search query:', query)
  // Implement search functionality
}

const handleLogoClick = () => {
  console.log('Logo clicked')
  // Navigate to home page
  navigateTo('/')
}

const handleProfileClick = () => {
  console.log('Profile clicked')
  // Navigate to profile page
  // navigateTo('/profile');
}

const handleSettingsClick = () => {
  console.log('Settings clicked')
  // Navigate to settings page
  // navigateTo('/settings');
}

const handleLogoutClick = () => {
  console.log('Logout clicked')
  // Implement logout functionality
  // await logout();
  // navigateTo('/login');
}
</script>
