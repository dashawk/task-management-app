<template>
  <nav :class="navigationClasses">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex-shrink-0">
          <AppLogo width="w-16" height="h-16" @click="handleLogoClick" />
        </div>

        <div v-if="showSearch" class="flex-1 flex justify-center px-4 lg:px-8">
          <SearchForm
            v-model="searchModel"
            :placeholder="searchPlaceholder"
            class="w-full max-w-lg"
            @search="handleSearch"
            @focus="handleSearchFocus"
            @blur="handleSearchBlur"
          />
        </div>

        <div class="flex-shrink-0">
          <UserAvatar
            v-if="user"
            :user="user"
            size="md"
            :show-dropdown="true"
            @profile-click="handleProfileClick"
            @settings-click="handleSettingsClick"
            @logout-click="handleLogoutClick"
          />

          <div v-else class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
            <User class="h-5 w-5 text-gray-500" />
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { User } from 'lucide-vue-next'
import type { NavigationBarEmits, User as CurrentUser } from '~~/types/navigation'

// Props with defaults
const props = withDefaults(
  defineProps<{
    user?: CurrentUser
    searchPlaceholder?: string
    showSearch?: boolean
    className?: string
  }>(),
  {
    searchPlaceholder: 'Search...',
    showSearch: true,
    className: ''
  }
)

// Emits
const emit = defineEmits<NavigationBarEmits>()

// Search state bound to Pinia
const taskStore = useTaskStore()
const searchModel = computed({
  get: () => taskStore.searchQuery,
  set: (val: string) => taskStore.setSearchQuery(val)
})

// Computed classes for the navigation
const navigationClasses = computed(() => {
  return `bg-white border-b border-gray-200 ${props.className}`.trim()
})

// Event handlers
const handleLogoClick = () => {
  emit('logo-click')
}

const handleSearch = (query: string) => {
  emit('search', query)
}

const handleSearchFocus = () => {
  // Optional: Add search focus handling logic
}

const handleSearchBlur = () => {
  // Optional: Add search blur handling logic
}

const handleProfileClick = () => {
  emit('profile-click')
}

const handleSettingsClick = () => {
  emit('settings-click')
}

const handleLogoutClick = () => {
  emit('logout-click')
}
</script>
