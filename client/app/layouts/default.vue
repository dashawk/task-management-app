<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation Bar -->
    <NavigationBar
      :user="user"
      search-placeholder="Search tasks, projects..."
      :show-search="true"
      @search="handleSearch"
      @logo-click="handleLogoClick"
      @profile-click="handleProfileClick"
      @settings-click="handleSettingsClick"
      @logout-click="handleLogout"
    />

    <!-- Main Content -->

    <slot />
  </div>
</template>

<script setup lang="ts">
// Auth store
const { logout } = useAuthStore()
const user = useAuthStore().user

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

const handleLogout = async () => {
  try {
    await logout()
    // nuxt-auth-sanctum will handle the redirect to /login
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>
