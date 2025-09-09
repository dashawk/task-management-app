<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation Bar -->
    <NavigationBar
      :user="userForNav"
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
const { logout } = authStore
const userForNav = computed<import('~~/types/navigation').User | undefined>(() => (authStore.user as any) ?? undefined)

// Event handlers
const taskStore = useTaskStore()
const handleSearch = async (query: string) => {
  await taskStore.setSearchQuery(query)
  // Ensure results are visible in the main task page
  if (typeof window !== 'undefined' && window.location.pathname !== '/') {
    navigateTo('/')
  }
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
