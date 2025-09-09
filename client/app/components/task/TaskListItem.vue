<template>
  <div
    class="group relative flex items-center p-3 rounded-sm bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
  >
    <!-- Checkbox/Completion Status -->
    <button
      type="button"
      class="flex-shrink-0 mr-3 w-5 h-5 rounded-full border-2 transition-all duration-200 flex items-center justify-center"
      :class="[task.completed ? 'bg-black border-black text-white' : 'border-gray-300 hover:border-gray-400']"
      @click="onToggle(task.id.toString())"
    >
      <Check v-if="task.completed" :size="12" class="text-white" />
    </button>

    <!-- Task Content -->
    <div class="flex-1 min-w-0">
      <p
        class="text-sm transition-all duration-200"
        :class="[task.completed ? 'text-gray-500 line-through' : 'text-gray-900']"
      >
        {{ task.title }}
      </p>
      <p
        v-if="task.description"
        class="text-xs mt-1 transition-all duration-200"
        :class="[task.completed ? 'text-gray-400 line-through' : 'text-gray-600']"
      >
        {{ task.description }}
      </p>
    </div>

    <!-- Right-side actions/time: no layout shift on hover -->
    <div class="flex-shrink-0 ml-3 h-6 relative flex items-center">
      <!-- Delete button (shown on hover via CSS, space always reserved) -->
      <button
        type="button"
        class="h-6 w-6 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200 rounded-md hover:bg-red-50 opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto"
        @click="onDelete(task.id.toString())"
        title="Delete task"
      >
        <Trash2 :size="16" />
      </button>

      <!-- Task time/date (fades out on hover) -->
      <div
        v-if="task.createdAt"
        class="absolute inset-0 flex items-center justify-end text-xs text-gray-400 transition-opacity duration-200 opacity-100 group-hover:opacity-0"
      >
        {{ formatTaskTime(task.createdAt) }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Check, Trash2 } from 'lucide-vue-next'
import type { TaskDisplay } from '~~/types/task-management'

interface Props {
  task: TaskDisplay
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'toggle-completion': [taskId: string]
  'delete-task': [taskId: string]
}>()

const onToggle = (taskId: string) => emit('toggle-completion', taskId)
const onDelete = (taskId: string) => emit('delete-task', taskId)

const formatTaskTime = (date: Date): string => {
  const now = new Date()
  const taskDate = new Date(date)

  if (taskDate.toDateString() === now.toDateString()) {
    return taskDate.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    })
  }

  if (taskDate.getFullYear() === now.getFullYear()) {
    return taskDate.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric'
    })
  }

  return taskDate.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>
