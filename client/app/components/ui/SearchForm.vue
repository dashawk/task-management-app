<template>
  <div :class="containerClasses">
    <div class="relative">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <Search class="h-5 w-5 text-gray-400" />
      </div>
      <input
        ref="searchInput"
        v-model="searchQuery"
        type="text"
        :placeholder="placeholder"
        :disabled="disabled"
        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
        @keyup.enter="handleSearch"
        @focus="handleFocus"
        @blur="handleBlur"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { Search } from 'lucide-vue-next'
import type { SearchFormEmits, SearchFormProps } from '~~/types/navigation'
// Global types are now available without imports

// Props with defaults
const props = withDefaults(defineProps<SearchFormProps>(), {
  placeholder: 'Search...',
  modelValue: '',
  disabled: false,
  className: ''
})

// Emits
const emit = defineEmits<SearchFormEmits>()

// Template ref
const searchInput = ref<HTMLInputElement>()

// Reactive search query
const searchQuery = computed({
  get: () => props.modelValue,
  set: (value: string) => {
    emit('update:modelValue', value)
  }
})

// Computed classes for the container
const containerClasses = computed(() => {
  return `w-full max-w-md ${props.className}`.trim()
})

// Event handlers
const handleSearch = () => {
  emit('search', searchQuery.value)
}

const handleFocus = () => {
  emit('focus')
}

const handleBlur = () => {
  emit('blur')
}

// Expose focus method for parent components
defineExpose({
  focus: () => searchInput.value?.focus(),
  blur: () => searchInput.value?.blur()
})
</script>
