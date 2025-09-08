<template>
  <div class="p-8">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8">
        <h1 class="text-3xl font-semibold text-gray-900 mb-4">Welcome to Task Management App</h1>
        <p class="text-gray-600 mb-6">Your navigation bar is now ready! Test the search functionality and user menu above.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-2">🔍 Search</h3>
            <p class="text-gray-600 text-sm">Try searching for tasks and projects using the search bar in the navigation.</p>
          </div>

          <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-2">👤 User Menu</h3>
            <p class="text-gray-600 text-sm">Click on your avatar to access profile, settings, and logout options.</p>
          </div>

          <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-2">🏠 Logo</h3>
            <p class="text-gray-600 text-sm">Click the logo to navigate back to the home page.</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Navigation Components</h2>
        <div class="space-y-3">
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span class="text-gray-700">AppLogo - Responsive logo component with click handling</span>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span class="text-gray-700">SearchForm - Search input with icon and event handling</span>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span class="text-gray-700">UserAvatar - User avatar with dropdown menu</span>
          </div>
          <div class="flex items-center space-x-3">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span class="text-gray-700">NavigationBar - Main navigation layout component</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: "default"
})
</script>
