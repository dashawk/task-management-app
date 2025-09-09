import { defineStore } from 'pinia'
import type { User } from '~~/types/navigation'

interface LoginCredentials {
  email: string
  password: string
  remember?: boolean
}

interface AuthState {
  user: User | null
  isLoading: boolean
  error: string | null
}

export const useAuthStore = defineStore('auth', () => {
  // State
  const state = reactive<AuthState>({
    user: null,
    isLoading: false,
    error: null
  })

  const sanctumAuth = useSanctumAuth()
  const { login: sanctumLogin, logout: sanctumLogout, user: sanctumUser, isAuthenticated } = sanctumAuth

  // Computed
  const user = computed(() => state.user || sanctumUser.value)
  const isLoggedIn = computed(() => isAuthenticated.value && !!user.value)
  const isLoading = computed(() => state.isLoading)
  const error = computed(() => state.error)

  // Actions
  const login = async (credentials: LoginCredentials) => {
    try {
      state.isLoading = true
      state.error = null

      // Use nuxt-auth-sanctum login
      await sanctumLogin(credentials)

      // Update local state with user data
      if (sanctumUser.value) {
        state.user = sanctumUser.value as User
      }

      // Clear any previous errors
      state.error = null
    } catch (err: any) {
      console.error('Login error:', err)

      // Handle different types of errors
      if (err?.data?.message) {
        state.error = err.data.message
      } else if (err?.message) {
        state.error = err.message
      } else {
        state.error = 'Login failed. Please check your credentials and try again.'
      }

      throw err
    } finally {
      state.isLoading = false
    }
  }

  const logout = async () => {
    try {
      state.isLoading = true
      state.error = null

      // Use nuxt-auth-sanctum logout
      await sanctumLogout()

      // Clear local state
      state.user = null
    } catch (err: any) {
      console.error('Logout error:', err)
      state.error = 'Logout failed. Please try again.'
      throw err
    } finally {
      state.isLoading = false
    }
  }

  const refreshUser = async () => {
    try {
      // The nuxt-auth-sanctum composable handles user refresh automatically
      // We just need to sync our local state
      if (sanctumUser.value) {
        state.user = sanctumUser.value as User
      } else {
        state.user = null
      }
    } catch (err: any) {
      console.error('Refresh user error:', err)
      state.user = null
    }
  }

  const clearError = () => {
    state.error = null
  }

  // Initialize user state on store creation
  const initialize = () => {
    // Sync with sanctum user state
    if (sanctumUser.value) {
      state.user = sanctumUser.value as User
    }
  }

  // Watch for changes in sanctum user state
  watch(
    sanctumUser,
    newUser => {
      if (newUser) {
        state.user = newUser as User
      } else {
        state.user = null
      }
    },
    { immediate: true }
  )

  // Initialize on store creation
  initialize()

  return {
    // State
    user,
    isLoggedIn,
    isLoading,
    error,

    // Actions
    login,
    logout,
    refreshUser,
    clearError
  }
})
