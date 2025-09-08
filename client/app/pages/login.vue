<template>
  <div class="min-h-screen bg-white flex items-center justify-center p-4">
    <div class="w-full max-w-md flex flex-col items-center">
      <NuxtImg src="/logo.svg" alt="Logo" class="w-16 h-16 mb-6" />

      <div class="w-full bg-white rounded-2xl border border-gray-200 p-6">
        <div class="text-center mb-6">
          <h1 class="text-xl font-semibold text-gray-900 mb-2">Sign In</h1>
          <p class="text-gray-500 text-sm">Login to continue using this app</p>
        </div>

        <form class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1"> Email </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Mail class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="email"
                type="email"
                placeholder="Enter your email"
                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
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
                type="password"
                placeholder="Enter your password"
                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Login Button -->
          <button
            type="submit"
            class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-900 transition-colors duration-200 flex items-center justify-center space-x-2"
          >
            <span>Login</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Mail, Lock } from 'lucide-vue-next'

definePageMeta({
  layout: 'auth',
  middleware: 'sanctum:guest'
})
</script>
