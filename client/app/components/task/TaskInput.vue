<template>
  <div v-if="!compact" class="flex flex-col items-center justify-center min-h-[60vh] px-8">
    <!-- Title -->
    <h1 class="text-3xl font-semibold text-gray-900 mb-8 text-center">
      {{ title }}
    </h1>

    <!-- Task Input -->
    <div class="w-full max-w-2xl relative">
      <div class="relative">
        <input
          ref="inputRef"
          v-model="inputValue"
          type="text"
          :placeholder="placeholder"
          :disabled="disabled"
          class="w-full px-6 py-4 text-lg border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 pr-16 disabled:opacity-50 disabled:cursor-not-allowed"
          @keydown.enter="handleSubmit"
          @focus="handleFocus"
          @blur="handleBlur"
        />

        <!-- Submit Button -->
        <button
          v-if="inputValue.trim()"
          type="button"
          :disabled="disabled"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 p-2 bg-black text-white rounded-full hover:bg-gray-800 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          @click="handleSubmit"
        >
          <ArrowUp :size="20" />
        </button>
      </div>

      <!-- Helper Text -->
      <p class="text-gray-500 text-sm mt-3 text-center">Press Enter or click the arrow to add your task</p>
    </div>

    <!-- Quick Actions (Optional) -->
    <div class="mt-8 flex flex-wrap gap-3 justify-center">
      <button
        v-for="quickAction in quickActions"
        :key="quickAction.id"
        type="button"
        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors duration-200"
        @click="handleQuickAction(quickAction.text)"
      >
        {{ quickAction.text }}
      </button>
    </div>
  </div>

  <!-- Compact mode for fixed footer usage -->
  <div v-else class="relative">
    <input
      ref="inputRef"
      v-model="inputValue"
      type="text"
      :placeholder="placeholder"
      :disabled="disabled"
      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12 disabled:opacity-50 disabled:cursor-not-allowed"
      @keydown.enter="handleSubmit"
      @focus="handleFocus"
      @blur="handleBlur"
    />
    <button
      v-if="inputValue.trim()"
      type="button"
      :disabled="disabled"
      class="absolute right-3 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
      @click="handleSubmit"
    >
      <ArrowUp :size="16" />
    </button>
  </div>
</template>

<script setup lang="ts">
import { ArrowUp } from 'lucide-vue-next'
interface Props {
  placeholder?: string
  title?: string
  modelValue?: string
  compact?: boolean
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Write the task you plan to do today here...',
  title: 'What do you have in mind?',
  modelValue: '',
  compact: false,
  disabled: false
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
  submit: [value: string]
  focus: []
  blur: []
}>()

const inputRef = ref<HTMLInputElement>()
const inputValue = ref(props.modelValue)

// Quick action suggestions
const quickActions = ref([
  { id: '1', text: 'Review project proposal' },
  { id: '2', text: 'Call team meeting' },
  { id: '3', text: 'Update documentation' },
  { id: '4', text: 'Plan next sprint' }
])

// Watch for external changes to modelValue
watch(
  () => props.modelValue,
  newValue => {
    inputValue.value = newValue
  }
)

// Watch for internal changes and emit updates
watch(inputValue, newValue => {
  emit('update:modelValue', newValue)
})

const handleSubmit = () => {
  const value = inputValue.value.trim()
  if (value) {
    emit('submit', value)
    inputValue.value = '' // Clear input after submission
  }
}

const handleFocus = () => {
  emit('focus')
}

const handleBlur = () => {
  emit('blur')
}

const handleQuickAction = (text: string) => {
  inputValue.value = text
  nextTick(() => {
    inputRef.value?.focus()
  })
}

// Auto-focus the input when component mounts
onMounted(() => {
  nextTick(() => {
    inputRef.value?.focus()
  })
})
</script>
