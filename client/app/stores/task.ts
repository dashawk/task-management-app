import { defineStore } from 'pinia'
import type { TaskDisplay, CreateTaskRequest, UpdateTaskRequest } from '~~/types/task-management'
import { taskService, transformTaskToDisplay, formatDateForApi } from '~/services/taskService'

interface TaskState {
  tasks: TaskDisplay[]
  isLoading: boolean
  error: string | null
  lastFetchedDate: string | null
}

export const useTaskStore = defineStore('task', () => {
  // State
  const state = reactive<TaskState>({
    tasks: [],
    isLoading: false,
    error: null,
    lastFetchedDate: null
  })

  // Computed
  const tasks = computed(() => state.tasks)
  const isLoading = computed(() => state.isLoading)
  const error = computed(() => state.error)

  // Get tasks for a specific date
  const getTasksForDate = computed(() => (date: Date) => {
    const dateStr = formatDateForApi(date)
    return state.tasks.filter(task => task.dueDate && formatDateForApi(task.dueDate) === dateStr)
  })

  // Get completed tasks
  const completedTasks = computed(() => state.tasks.filter(task => task.completed))

  // Get pending tasks
  const pendingTasks = computed(() => state.tasks.filter(task => !task.completed))

  // Actions
  const fetchTasks = async (date?: Date) => {
    try {
      state.isLoading = true
      state.error = null

      const params = date ? { date: formatDateForApi(date) } : undefined
      const response = await taskService.getTasks(params)

      console.log(response)
      if (response.success) {
        state.tasks = response.data.map(transformTaskToDisplay)
        state.lastFetchedDate = date ? formatDateForApi(date) : null
      } else {
        throw new Error(response.message)
      }
    } catch (err: any) {
      console.error('Failed to fetch tasks:', err)
      state.error = err.message || 'Failed to fetch tasks'
      throw err
    } finally {
      state.isLoading = false
    }
  }

  const createTask = async (taskData: CreateTaskRequest) => {
    try {
      state.isLoading = true
      state.error = null

      const response = await taskService.createTask(taskData)

      if (response.success) {
        const newTask = transformTaskToDisplay(response.data)

        // Optimistically add to local state
        state.tasks.unshift(newTask)

        return newTask
      } else {
        throw new Error(response.message)
      }
    } catch (err: any) {
      console.error('Failed to create task:', err)
      state.error = err.message || 'Failed to create task'
      throw err
    } finally {
      state.isLoading = false
    }
  }

  const updateTask = async (id: number, taskData: UpdateTaskRequest) => {
    try {
      state.isLoading = true
      state.error = null

      const response = await taskService.updateTask(id, taskData)

      if (response.success) {
        const updatedTask = transformTaskToDisplay(response.data)

        // Update local state
        const index = state.tasks.findIndex(task => task.id === id)
        if (index !== -1) {
          state.tasks[index] = updatedTask
        }

        return updatedTask
      } else {
        throw new Error(response.message)
      }
    } catch (err: any) {
      console.error('Failed to update task:', err)
      state.error = err.message || 'Failed to update task'
      throw err
    } finally {
      state.isLoading = false
    }
  }

  const deleteTask = async (id: number) => {
    try {
      state.isLoading = true
      state.error = null

      const response = await taskService.deleteTask(id)

      if (response.success) {
        // Remove from local state
        state.tasks = state.tasks.filter(task => task.id !== id)
      } else {
        throw new Error(response.message)
      }
    } catch (err: any) {
      console.error('Failed to delete task:', err)
      state.error = err.message || 'Failed to delete task'
      throw err
    } finally {
      state.isLoading = false
    }
  }

  const toggleTaskCompletion = async (id: number) => {
    try {
      state.error = null

      // Optimistic update
      const taskIndex = state.tasks.findIndex(task => task.id === id)
      if (taskIndex !== -1 && state.tasks[taskIndex]) {
        state.tasks[taskIndex].completed = !state.tasks[taskIndex].completed
      }

      const response = await taskService.toggleTaskCompletion(id)

      if (response.success) {
        const updatedTask = transformTaskToDisplay(response.data)

        // Update with server response
        if (taskIndex !== -1) {
          state.tasks[taskIndex] = updatedTask
        }

        return updatedTask
      } else {
        // Revert optimistic update on failure
        if (taskIndex !== -1 && state.tasks[taskIndex]) {
          state.tasks[taskIndex].completed = !state.tasks[taskIndex].completed
        }
        throw new Error(response.message)
      }
    } catch (err: any) {
      console.error('Failed to toggle task completion:', err)
      state.error = err.message || 'Failed to toggle task completion'
      throw err
    }
  }

  const clearError = () => {
    state.error = null
  }

  const clearTasks = () => {
    state.tasks = []
    state.lastFetchedDate = null
  }

  const reorderTasks = async (reorderData: Array<{ id: number; order: number }>) => {
    try {
      state.error = null

      // Optimistically update the order of tasks in local state
      reorderData.forEach(({ id, order }) => {
        const taskIndex = state.tasks.findIndex(task => task.id === id)
        if (taskIndex !== -1 && state.tasks[taskIndex]) {
          state.tasks[taskIndex].order = order
        }
      })

      // Re-sort tasks by order
      state.tasks.sort((a, b) => {
        const orderA = a.order || 0
        const orderB = b.order || 0
        if (orderA === orderB) {
          return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
        }
        return orderA - orderB
      })

      // Call API to persist the changes
      const response = await taskService.reorderTasks(reorderData)

      if (response.success) {
        // Update with server response
        const updatedTasks = response.data.map(transformTaskToDisplay)
        updatedTasks.forEach(updatedTask => {
          const taskIndex = state.tasks.findIndex(task => task.id === updatedTask.id)
          if (taskIndex !== -1) {
            state.tasks[taskIndex] = updatedTask
          }
        })
      } else {
        throw new Error(response.message)
      }
    } catch (err: any) {
      console.error('Failed to reorder tasks:', err)
      state.error = err.message || 'Failed to reorder tasks'
      throw err
    }
  }

  return {
    // State
    tasks,
    isLoading,
    error,
    getTasksForDate,
    completedTasks,
    pendingTasks,

    // Actions
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    toggleTaskCompletion,
    clearError,
    clearTasks,
    reorderTasks
  }
})
