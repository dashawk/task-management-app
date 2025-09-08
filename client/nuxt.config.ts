import tailwindcss from '@tailwindcss/vite'

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
    {path: '~/components/auth', pathPrefix: false},
  ],

  // Enable file watching
  watch: ['~/pages/**/*', '~/components/**/*', '~/layouts/**/*', '~/assets/**/*'],

  modules: [
    '@nuxt/image',
    '@pinia/nuxt',
    ['nuxt-auth-sanctum', {
      baseUrl: 'http://localhost',
      endpoints: {
        login: '/api/login',
        logout: '/api/logout',
        user: '/api/v1/user',
        csrf: '/sanctum/csrf-cookie',
      },
      mode: 'cookie',
      redirect: {
        onLogin: '/',
        onLogout: '/login'
      }
    }]
  ],
  runtimeConfig: {
    public: {
      baseUrl: 'http://localhost'
    }
  }
})