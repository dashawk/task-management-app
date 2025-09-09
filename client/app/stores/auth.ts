import { defineStore } from 'pinia'
import type { User } from '~~/types/navigation'

type LoginCredentials = {
  email: string
  password: string
  remember?: boolean
}

export const useAuthStore = defineStore('auth', () => {
  const { login: sanctumLogin, logout: sanctumLogout, user: sanctumUser, isAuthenticated } = useSanctumAuth<User>()

  const rememberPref = useCookie<boolean>('remember_me_pref', {
    default: () => false,
    sameSite: 'lax',
    maxAge: 60 * 60 * 24 * 365 // 1 year
  })

  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const user = computed<User | null>(() => sanctumUser.value ?? null)
  const isLoggedIn = computed<boolean>(() => isAuthenticated.value && !!user.value)

  const setRemember = (val: boolean) => {
    rememberPref.value = val
  }

  const login = async (credentials: LoginCredentials) => {
    isLoading.value = true
    error.value = null

    try {
      const payload = {
        ...credentials,
        remember: credentials.remember ?? rememberPref.value
      }
      await sanctumLogin(payload)

      // Persist latest intent (keep the checkbox state sticky)
      rememberPref.value = !!payload.remember
    } catch (err: unknown) {
      error.value = toMessage(err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    isLoading.value = true
    error.value = null

    try {
      await sanctumLogout()
    } catch (err: unknown) {
      error.value = toMessage(err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const clearError = () => {
    error.value = null
  }

  return {
    // State (derived from Sanctum)
    user,
    isLoggedIn,
    isLoading,
    error,

    // Persisted preferences
    rememberPref,
    setRemember,

    // Actions
    login,
    logout,
    clearError
  }
})
