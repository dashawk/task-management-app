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
const authStore = useAuthStore()
const { logout, user } = authStore

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
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>
