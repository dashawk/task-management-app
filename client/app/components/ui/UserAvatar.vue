<template>
  <div class="relative" :class="className">
    <!-- Avatar Button -->
    <button
      ref="avatarButton"
      type="button"
      :class="avatarClasses"
      @click="toggleDropdown"
      :aria-expanded="isDropdownOpen"
      aria-haspopup="true"
    >
      <!-- Avatar Image or Initials -->
      <img v-if="user.avatar" :src="user.avatar" :alt="`${user.name} avatar`" class="w-full h-full object-cover" />
      <span v-else class="text-white font-medium text-sm">
        {{ userInitials }}
      </span>
    </button>

    <!-- Dropdown Menu -->
    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="showDropdown && isDropdownOpen"
        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-50"
        role="menu"
        aria-orientation="vertical"
      >
        <!-- User Info -->
        <div class="px-4 py-2 border-b border-gray-200">
          <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
          <p class="text-sm text-gray-500">{{ user.email }}</p>
        </div>

        <!-- Menu Items -->
        <button
          type="button"
          class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
          role="menuitem"
          @click="handleProfileClick"
        >
          <User class="h-4 w-4" />
          <span>Profile</span>
        </button>

        <button
          type="button"
          class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
          role="menuitem"
          @click="handleSettingsClick"
        >
          <Settings class="h-4 w-4" />
          <span>Settings</span>
        </button>

        <hr class="my-1 border-gray-200" />

        <button
          type="button"
          class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
          role="menuitem"
          @click="handleLogoutClick"
        >
          <LogOut class="h-4 w-4" />
          <span>Sign out</span>
        </button>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { User, Settings, LogOut } from 'lucide-vue-next'
import { SIZE_CLASSES, type UserAvatarEmits, type UserAvatarProps } from '~~/types/navigation'
// Global types and constants are now available without imports

// Props with defaults
const props = withDefaults(defineProps<UserAvatarProps>(), {
  size: 'md',
  showDropdown: true,
  className: ''
})

// Emits
const emit = defineEmits<UserAvatarEmits>()

// Template refs
const avatarButton = ref<HTMLButtonElement>()

// Reactive state
const isDropdownOpen = ref(false)

// Computed properties
const userInitials = computed(() => {
  return props.user.name
    .split(' ')
    .map(name => name.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const avatarClasses = computed(() => {
  const sizeClass = SIZE_CLASSES.avatar[props.size]
  return `${sizeClass} rounded-full bg-gray-600 flex items-center justify-center hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2`.trim()
})

// Methods
const toggleDropdown = () => {
  if (props.showDropdown) {
    isDropdownOpen.value = !isDropdownOpen.value
  }
}

const closeDropdown = () => {
  isDropdownOpen.value = false
}

// Event handlers
const handleProfileClick = () => {
  emit('profile-click')
  closeDropdown()
}

const handleSettingsClick = () => {
  emit('settings-click')
  closeDropdown()
}

const handleLogoutClick = () => {
  emit('logout-click')
  closeDropdown()
}

// Click outside to close dropdown
const handleClickOutside = (event: Event) => {
  if (avatarButton.value && !avatarButton.value.contains(event.target as Node)) {
    closeDropdown()
  }
}

// Lifecycle
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
