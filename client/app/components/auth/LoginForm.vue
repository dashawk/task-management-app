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
          v-model="form.email"
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
          v-model="form.password"
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
        v-model="form.remember"
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

// Form state
const form = reactive({
  email: '',
  password: '',
  remember: false
})

// Auth store
const { login } = useAuthStore()

// Loading and error state
const isLoading = ref(false)
const error = ref<string | null>(null)

// Computed
const isFormValid = computed(() => {
  return form.email.trim() !== '' && form.password.trim() !== ''
})

// Methods
const handleSubmit = async () => {
  if (!isFormValid.value || isLoading.value) return

  try {
    isLoading.value = true
    error.value = null

    // Attempt login using nuxt-auth-sanctum
    await login({
      email: form.email.trim(),
      password: form.password,
      remember: form.remember
    })

    // Success - nuxt-auth-sanctum will handle the redirect
    console.log('Login successful')
  } catch (err: any) {
    console.error('Login failed:', err)

    // Handle different types of errors
    if (err?.data?.message) {
      error.value = err.data.message
    } else if (err?.message) {
      error.value = err.message
    } else {
      error.value = 'Login failed. Please check your credentials and try again.'
    }
  } finally {
    isLoading.value = false
  }
}

// Clear error when user starts typing
watch([() => form.email, () => form.password], () => {
  if (error.value) {
    error.value = null
  }
})
</script>
