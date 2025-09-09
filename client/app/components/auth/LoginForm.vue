<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <div v-if="error" class="bg-red-50 border border-red-200 rounded-md p-3">
      <p class="text-sm text-red-600">{{ error }}</p>
    </div>

    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1"> Email </label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <Mail class="h-5 w-5 text-gray-400" />
        </div>
        <input
          id="email"
          v-model="email"
          type="email"
          required
          placeholder="Enter your email"
          :disabled="isLoading"
          class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500"
        />
      </div>
    </div>

    <div>
      <div class="flex items-center justify-between">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1"> Password </label>
        <a href="#" class="text-sm text-gray-600 hover:text-gray-900 mb-1"> Forgot your password? </a>
      </div>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <Lock class="h-5 w-5 text-gray-400" />
        </div>
        <input
          id="password"
          v-model="password"
          type="password"
          required
          placeholder="Enter your password"
          :disabled="isLoading"
          class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500"
        />
      </div>
    </div>

    <div class="flex items-center">
      <input
        id="remember"
        v-model="rememberPref"
        type="checkbox"
        :disabled="isLoading"
        class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded disabled:opacity-50"
      />
      <label for="remember" class="ml-2 block text-sm text-gray-700"> Remember me </label>
    </div>

    <button
      type="submit"
      :disabled="isLoading || !isFormValid"
      class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-900 transition-colors duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <div v-if="isLoading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
      <span>{{ isLoading ? 'Signing in...' : 'Sign In' }}</span>
    </button>
  </form>
</template>

<script setup lang="ts">
import { Mail, Lock } from 'lucide-vue-next'

// Auth store
const auth = useAuthStore()
const { isLoading, error, rememberPref } = storeToRefs(auth)
const { login, clearError } = auth

const email = ref<string>('')
const password = ref<string>('')

// Computed
const isFormValid = computed(() => {
  return email.value.trim() !== '' && password.value.trim() !== ''
})

// Methods
const handleSubmit = async () => {
  if (!isFormValid.value || isLoading.value) return

  await login({
    email: email.value.trim(),
    password: password.value.trim()
  })
}

// Clear error when user starts typing
watch([email, password], () => {
  if (error.value) {
    clearError()
  }
})
</script>
