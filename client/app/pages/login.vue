<template>
  <div class="min-h-screen bg-white flex items-center justify-center p-4">
    <div class="w-full max-w-md flex flex-col items-center">
      <NuxtImg src="/logo.svg" alt="Logo" class="w-16 h-16 mb-6" />

      <div class="w-full bg-white rounded-2xl border border-gray-200 p-6">
        <div class="text-center mb-6">
          <h1 class="text-xl font-semibold text-gray-900 mb-2">Sign In</h1>
          <p class="text-gray-500 text-sm">Login to continue using this app</p>
        </div>

        <LoginForm />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import LoginForm from '~/components/auth/LoginForm.vue'

definePageMeta({
  layout: 'auth',
  middleware: 'sanctum:guest'
})
</script>
