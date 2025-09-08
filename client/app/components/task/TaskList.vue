<template>
  <div class="space-y-2">
    <div
      v-for="task in tasks"
      :key="task.id"
      class="group relative flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200"
      @mouseenter="hoveredTaskId = task.id"
      @mouseleave="hoveredTaskId = null"
    >
      <!-- Checkbox/Completion Status -->
      <button
        type="button"
        class="flex-shrink-0 mr-3 w-5 h-5 rounded-full border-2 transition-all duration-200 flex items-center justify-center"
        :class="[
          task.completed
            ? 'bg-black border-black text-white'
            : 'border-gray-300 hover:border-gray-400'
        ]"
        @click="toggleTaskCompletion(task.id)"
      >
        <Check
          v-if="task.completed"
          :size="12"
          class="text-white"
        />
      </button>

      <!-- Task Content -->
      <div class="flex-1 min-w-0">
        <p
          class="text-sm transition-all duration-200"
          :class="[
            task.completed
              ? 'text-gray-500 line-through'
              : 'text-gray-900'
          ]"
        >
          {{ task.title }}
        </p>
        <p
          v-if="task.description"
          class="text-xs mt-1 transition-all duration-200"
          :class="[
            task.completed
              ? 'text-gray-400 line-through'
              : 'text-gray-600'
          ]"
        >
          {{ task.description }}
        </p>
      </div>

      <!-- Delete Button (shown on hover) -->
      <button
        v-if="hoveredTaskId === task.id"
        type="button"
        class="flex-shrink-0 ml-3 p-1.5 text-gray-400 hover:text-red-500 transition-colors duration-200 rounded-md hover:bg-red-50"
        @click="deleteTask(task.id)"
        title="Delete task"
      >
        <Trash2 :size="16" />
      </button>

      <!-- Task Time/Date (if available) -->
      <div
        v-if="task.createdAt && hoveredTaskId !== task.id"
        class="flex-shrink-0 ml-3 text-xs text-gray-400"
      >
        {{ formatTaskTime(task.createdAt) }}
      </div>
    </div>

    <!-- Empty State -->
    <div
      v-if="tasks.length === 0"
      class="text-center py-8 text-gray-500"
    >
      <p class="text-sm">No tasks for this date</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Check, Trash2 } from 'lucide-vue-next'
import type { Task } from '~/types/task-management'

interface Props {
  tasks: Task[]
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'toggle-completion': [taskId: string]
  'delete-task': [taskId: string]
}>()

// Local state for hover effects
const hoveredTaskId = ref<string | null>(null)

// Methods
const toggleTaskCompletion = (taskId: string) => {
  emit('toggle-completion', taskId)
}

const deleteTask = (taskId: string) => {
  emit('delete-task', taskId)
}

const formatTaskTime = (date: Date): string => {
  const now = new Date()
  const taskDate = new Date(date)
  
  // If it's today, show time
  if (taskDate.toDateString() === now.toDateString()) {
    return taskDate.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    })
  }
  
  // If it's this year, show month and day
  if (taskDate.getFullYear() === now.getFullYear()) {
    return taskDate.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric'
    })
  }
  
  // Otherwise show full date
  return taskDate.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>
