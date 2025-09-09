import { defineStore } from 'pinia'
import type { TaskDisplay, CreateTaskRequest, UpdateTaskRequest } from '~~/types/task-management'
import { taskService, transformTaskToDisplay, formatDateForApi } from '~/services/taskService'

interface TaskState {
  tasks: TaskDisplay[]
  isLoading: boolean
  error: string | null
  lastFetchedDate: string | null
  loadedAll: boolean
  searchQuery: string
}

export const useTaskStore = defineStore('task', () => {
  // State
  const tasks = ref<TaskDisplay[]>([])
  const isLoading = ref<boolean>(false)
  const error = ref<string | null>(null)
  const lastFetchedDate = ref<string | null>(null)
  const loadedAll = ref<boolean>(false)
  const searchQuery = ref<string>('')

  // Helpers
  const sortTasksByOrder = (list: TaskDisplay[]) =>
    list.sort((a, b) => {
      const ao = a.order ?? Number.POSITIVE_INFINITY
      const bo = b.order ?? Number.POSITIVE_INFINITY
      if (ao !== bo) return ao - bo
      // When order is equal/missing, keep older items first so newly created ones end up at the bottom
      return a.createdAt.getTime() - b.createdAt.getTime()
    })

  const updateTaskInList = (t: TaskDisplay) => {
    const i = tasks.value.findIndex(task => task.id === t.id)
    if (i !== -1) {
      tasks.value[i] = t
    }
  }

  const removeTaskFromList = (id: number) => {
    tasks.value = tasks.value.filter(task => task.id !== id)
  }

  // Getters
  const getTasksForDate = (date: Date) => {
    const dateStr = formatDateForApi(date)
    return tasks.value.filter(task => task.dueDate && formatDateForApi(task.dueDate) === dateStr)
  }
  const completedTasks = computed(() => tasks.value.filter(task => task.completed))
  const pendingTasks = computed(() => tasks.value.filter(task => !task.completed))

  const tasksFilteredByQuery = computed(() => {
    const q = searchQuery.value.trim().toLowerCase()
    if (!q) return tasks.value
    return tasks.value.filter(
      t => (t.title ?? '').toLowerCase().includes(q) || (t.description ?? '').toLowerCase().includes(q)
    )
  })

  // Actions
  const fetchTasks = async (date?: Date) => {
    isLoading.value = true
    error.value = null

    try {
      const params = date ? { date: formatDateForApi(date) } : undefined
      const res = await taskService.getTasks(params)
      if (!res.success) throw new Error(res.message)

      tasks.value = sortTasksByOrder(res.data.map(transformTaskToDisplay))
      lastFetchedDate.value = date ? formatDateForApi(date) : null
      loadedAll.value = !date
    } catch (e: unknown) {
      error.value = toMessage(e) || 'Failed to fetch tasks'
      throw e
    } finally {
      isLoading.value = false
    }
  }

  const createTask = async (payload: CreateTaskRequest) => {
    isLoading.value = true
    error.value = null

    try {
      const res = await taskService.createTask(payload)
      if (!res.success) throw new Error(res.message)

      const newTask = transformTaskToDisplay(res.data)
      tasks.value = sortTasksByOrder([...tasks.value, newTask])

      return newTask
    } catch (e: unknown) {
      error.value = toMessage(e) || 'Failed to create task'
      throw e
    } finally {
      isLoading.value = false
    }
  }

  const updateTask = async (id: number, payload: UpdateTaskRequest) => {
    isLoading.value = true
    error.value = null

    try {
      const res = await taskService.updateTask(id, payload)
      if (!res.success) throw new Error(res.message)

      const updatedTask = transformTaskToDisplay(res.data)
      updateTaskInList(updatedTask)

      tasks.value = sortTasksByOrder([...tasks.value])

      return updatedTask
    } catch (e: unknown) {
      error.value = toMessage(e) || 'Failed to update task'
      throw e
    } finally {
      isLoading.value = false
    }
  }

  const deleteTask = async (id: number) => {
    isLoading.value = true
    error.value = null

    try {
      const res = await taskService.deleteTask(id)
      if (!res.success) throw new Error(res.message)

      removeTaskFromList(id)
    } catch (e: unknown) {
      error.value = toMessage(e) || 'Failed to delete task'
      throw e
    } finally {
      isLoading.value = false
    }
  }

  const toggleTaskCompletion = async (id: number) => {
    error.value = null
    const index = tasks.value.findIndex(task => task.id === id)
    if (index === -1) return
    if (!tasks.value[index]) return

    const prev = tasks.value[index].completed
    tasks.value[index].completed = !prev

    try {
      const res = await taskService.toggleTaskCompletion(id)
      if (!res.success) throw new Error(res.message)

      const updatedTask = transformTaskToDisplay(res.data)
      updateTaskInList(updatedTask)
      return updatedTask
    } catch (e: unknown) {
      tasks.value[index].completed = prev // Revert
      error.value = toMessage(e) || 'Failed to toggle task completion'
      throw e
    }
  }

  const reorderTasks = async (reorderData: Array<{ id: number; order: number }>) => {
    error.value = null
    // snapshot for rollback
    const snapshot = tasks.value.map(t => ({ ...t }))
    try {
      // optimistic local reorder
      reorderData.forEach(({ id, order }) => {
        const i = tasks.value.findIndex(t => t.id === id)
        const t = i !== -1 ? tasks.value[i] : undefined
        if (t) t.order = order
      })

      tasks.value = sortTasksByOrder([...tasks.value])
      const res = await taskService.reorderTasks(reorderData)
      if (!res.success) throw new Error(res.message)

      const updated = res.data.map(transformTaskToDisplay)
      updated.forEach(updateTaskInList)

      tasks.value = sortTasksByOrder([...tasks.value])
    } catch (e: unknown) {
      tasks.value = snapshot // rollback
      error.value = toMessage(e) || 'Failed to reorder tasks'
      throw e
    }
  }

  // Search
  const setSearchQuery = async (q: string) => {
    searchQuery.value = q

    if (q.trim() && !loadedAll.value) {
      try {
        await fetchTasks()
      } catch {}
    }
  }
  const clearSearch = () => {
    searchQuery.value = ''
  }

  const clearError = () => {
    error.value = null
  }

  const clearTasks = () => {
    tasks.value = []
    lastFetchedDate.value = null
    loadedAll.value = false
  }

  return {
    // state
    tasks,
    isLoading,
    error,
    lastFetchedDate,
    loadedAll,
    searchQuery,
    // derived
    getTasksForDate,
    completedTasks,
    pendingTasks,
    tasksFilteredByQuery,
    // actions
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    toggleTaskCompletion,
    reorderTasks,
    setSearchQuery,
    clearSearch,
    clearError,
    clearTasks
  }
})
