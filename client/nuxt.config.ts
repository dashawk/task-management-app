import tailwindcss from '@tailwindcss/vite'
import { defineNuxtConfig } from 'nuxt/config'


// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  pages: true,

  // Auto-import configuration
  // imports: {
  //   dirs: [
  //     'utils/**'
  //   ]
  // },

  // Development server configuration
  devServer: {
    host: '0.0.0.0', // Allow external connections (important for Docker)
    port: 3000,
  },

  vite: {
    plugins: [tailwindcss()],
    server: {
      // Same-origin HMR: browser connects back to the page origin (localhost:3000)
      hmr: {
        clientPort: 3000,
        protocol: 'ws',
      },
      watch: {
        usePolling: true, // Enable polling for file changes (important for Docker)
        interval: 1000, // Poll every 1 second
      },
    },
  },

  components: [
    {path: '~/components/layout', pathPrefix: false},
    {path: '~/components/ui', pathPrefix: false},
    {path: '~/components/task', pathPrefix: false},
  ],

  // Enable file watching
  watch: ['~/pages/**/*', '~/components/**/*', '~/layouts/**/*', '~/assets/**/*'],

  modules: ['@nuxt/image', 'nuxt-auth-sanctum'],
  sanctum: {
    baseUrl: 'http://localhost'
  }
})