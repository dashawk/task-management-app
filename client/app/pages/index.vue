<template>
  <main class="flex-1 h-[calc(100vh-65px)]">
    <TaskManagementLayout
      :has-tasks="hasTasks"
      :selected-date="selectedDate"
      :tasks="tasks"
      @task-submit="handleTaskSubmit"
      @date-select="handleDateSelect"
      @task-input-focus="handleTaskInputFocus"
      @task-input-blur="handleTaskInputBlur"
      @toggle-completion="handleToggleCompletion"
      @delete-task="handleDeleteTask"
    />
  </main>
</template>

<script setup lang="ts">
import type { Task } from '~~/types/task-management'

definePageMeta({
  layout: 'default'
})

// Reactive state
const tasks = ref<Task[]>([])
const selectedDate = ref(new Date())

// Computed properties
const hasTasks = computed(() => tasks.value.length > 0)

// Event handlers
const handleTaskSubmit = (taskTitle: string) => {
  console.log('New task submitted:', taskTitle)

  // Create new task
  const newTask: Task = {
    id: crypto.randomUUID(),
    title: taskTitle,
    completed: false,
    createdAt: new Date(),
    updatedAt: new Date(),
    dueDate: selectedDate.value
  }

  tasks.value.push(newTask)

  // TODO: Save to backend/database
  console.log('Current tasks:', tasks.value)
}

const handleDateSelect = (date: Date) => {
  selectedDate.value = date
  console.log('Date selected:', date)

  // TODO: Load tasks for selected date
}

const handleTaskInputFocus = () => {
  console.log('Task input focused')
}

const handleTaskInputBlur = () => {
  console.log('Task input blurred')
}

const handleToggleCompletion = (taskId: string) => {
  console.log('Toggle completion for task:', taskId)

  // Find and update the task
  const taskIndex = tasks.value.findIndex((task: Task) => task.id === taskId)
  if (taskIndex !== -1) {
    tasks.value[taskIndex].completed = !tasks.value[taskIndex].completed
    tasks.value[taskIndex].updatedAt = new Date()

    // TODO: Update in backend/database
    console.log('Task updated:', tasks.value[taskIndex])
  }
}

const handleDeleteTask = (taskId: string) => {
  console.log('Delete task:', taskId)

  // Remove the task from the array
  const taskIndex = tasks.value.findIndex((task: Task) => task.id === taskId)
  if (taskIndex !== -1) {
    tasks.value.splice(taskIndex, 1)

    // TODO: Delete from backend/database
    console.log('Task deleted, remaining tasks:', tasks.value)
  }
}

// Initialize with today's date
onMounted(() => {
  selectedDate.value = new Date()
  // TODO: Load existing tasks from backend
})
</script>
