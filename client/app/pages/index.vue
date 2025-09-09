<template>
  <main class="flex-1 h-[calc(100vh-65px)]">
    <!-- Error Message -->
    <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4 mx-4 mt-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path
              fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm">{{ error }}</p>
        </div>
        <div class="ml-auto pl-3">
          <button @click="taskStore.clearError()" class="text-red-400 hover:text-red-600">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <TaskManagementLayout
      ref="taskManagementLayoutRef"
      :has-tasks="hasTasks"
      :selected-date="selectedDate"
      :tasks="tasks"
      :is-loading="isLoading"
      @task-submit="handleTaskSubmit"
      @date-select="handleDateSelect"
      @toggle-completion="handleToggleCompletion"
      @delete-task="handleDeleteTask"
      @update-task="handleUpdateTask"
      @reorder-tasks="handleReorderTasks"
    />
  </main>
</template>

<script setup lang="ts">
import { formatDateForApi } from '~/services/taskService'

definePageMeta({
  layout: 'default',
  middleware: 'sanctum:auth'
})

// Store
const taskStore = useTaskStore()

// Reactive state
const selectedDate = ref(new Date())
const isSubmitting = ref(false)
const taskManagementLayoutRef = ref()

// Computed properties
const tasks = computed(() => taskStore.getTasksForDate(selectedDate.value))
const hasTasks = computed(() => tasks.value.length > 0)
const isLoading = computed(() => taskStore.isLoading)
const error = computed(() => taskStore.error)

// Event handlers
const handleTaskSubmit = async (taskTitle: string) => {
  if (isSubmitting.value) return

  const wasEmpty = !hasTasks.value

  try {
    isSubmitting.value = true
    taskStore.clearError()

    await taskStore.createTask({
      title: taskTitle,
      due_date: formatDateForApi(selectedDate.value),
      completed: false
    })

    console.log('Task created successfully')

    // Focus the bottom input after successful task creation
    if (wasEmpty) {
      nextTick(() => {
        taskManagementLayoutRef.value?.focusBottomInput()
      })
    }
  } catch (error) {
    console.error('Failed to create task:', error)
    // Error is handled by the store and can be displayed in UI
  } finally {
    isSubmitting.value = false
  }
}

const handleDateSelect = async (date: Date) => {
  selectedDate.value = date
  console.log('Date selected:', date)

  try {
    taskStore.clearError()
    await taskStore.fetchTasks(date)
  } catch (error) {
    console.error('Failed to fetch tasks for date:', error)
  }
}

const handleToggleCompletion = async (taskId: string) => {
  try {
    taskStore.clearError()
    await taskStore.toggleTaskCompletion(Number(taskId))
    console.log('Task completion toggled successfully')
  } catch (error) {
    console.error('Failed to toggle task completion:', error)
  }
}

const handleDeleteTask = async (taskId: string) => {
  try {
    taskStore.clearError()
    await taskStore.deleteTask(Number(taskId))
    console.log('Task deleted successfully')
  } catch (error) {
    console.error('Failed to delete task:', error)
  }
}

const handleUpdateTask = async (taskId: string, updates: import('~~/types/task-management').UpdateTaskRequest) => {
  try {
    taskStore.clearError()
    await taskStore.updateTask(Number(taskId), updates)
    console.log('Task updated successfully')
  } catch (error) {
    console.error('Failed to update task:', error)
  }
}

const handleReorderTasks = async (draggedTaskId: string, targetTaskId: string) => {
  try {
    taskStore.clearError()

    // Get current tasks for the selected date
    const currentTasks = tasks.value
    const draggedIndex = currentTasks.findIndex(task => task.id.toString() === draggedTaskId)
    const targetIndex = currentTasks.findIndex(task => task.id.toString() === targetTaskId)

    if (draggedIndex === -1 || targetIndex === -1) {
      console.warn('Invalid task indices for reordering')
      return
    }

    // Don't reorder if dropping on the same position
    if (draggedIndex === targetIndex) {
      return
    }

    // Create new order for tasks
    const reorderedTasks = [...currentTasks]
    const draggedTask = reorderedTasks[draggedIndex]
    if (!draggedTask) {
      console.warn('Dragged task not found')
      return
    }

    reorderedTasks.splice(draggedIndex, 1)
    reorderedTasks.splice(targetIndex, 0, draggedTask)

    // Update order values
    const reorderData = reorderedTasks.map((task, index) => ({
      id: task.id,
      order: index + 1
    }))

    // Call API to persist the new order
    console.log('Task reordering:', { draggedTaskId, targetTaskId, reorderData })

    // Update local state and persist to API
    await taskStore.reorderTasks(reorderData)
    console.log('Tasks reordered successfully')
  } catch (error) {
    console.error('Failed to reorder tasks:', error)
    // The error is already handled by the store, but we could show a toast notification here
  }
}

// Initialize with today's date and load tasks
onMounted(async () => {
  selectedDate.value = new Date()

  try {
    await taskStore.fetchTasks(selectedDate.value)
  } catch (error) {
    console.error('Failed to load initial tasks:', error)
  }
})
</script>
