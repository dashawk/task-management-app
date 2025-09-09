<template>
  <div class="space-y-2">
    <TaskListItem
      v-for="task in tasks"
      :key="task.id"
      :task="task"
      @toggle-completion="toggleTaskCompletion"
      @delete-task="deleteTask"
      @update-task="updateTask"
      @drag-start="handleDragStart"
      @drag-end="handleDragEnd"
      @drag-over="handleDragOver"
      @drop="handleDrop"
    />
  </div>
</template>

<script setup lang="ts">
import type { TaskDisplay, UpdateTaskRequest } from '~~/types/task-management'

import TaskListItem from './TaskListItem.vue'

interface Props {
  tasks: TaskDisplay[]
}

defineProps<Props>()

const emit = defineEmits<{
  'toggle-completion': [taskId: string]
  'delete-task': [taskId: string]
  'update-task': [taskId: string, updates: UpdateTaskRequest]
  'reorder-tasks': [draggedTaskId: string, targetTaskId: string]
}>()

// Drag state
const draggedTaskId = ref<string | null>(null)

// Methods
const toggleTaskCompletion = (taskId: string) => {
  emit('toggle-completion', taskId)
}

const updateTask = (taskId: string, updates: UpdateTaskRequest) => {
  emit('update-task', taskId, updates)
}

// Drag and drop handlers
const handleDragStart = (taskId: string) => {
  draggedTaskId.value = taskId
}

const handleDragEnd = () => {
  draggedTaskId.value = null
}

const handleDragOver = (_taskId: string) => {
  // Visual feedback is handled by individual TaskListItem components
}

const handleDrop = (draggedId: string, targetId: string) => {
  if (draggedId !== targetId) {
    emit('reorder-tasks', draggedId, targetId)
  }
  draggedTaskId.value = null
}

const deleteTask = (taskId: string) => {
  emit('delete-task', taskId)
}
</script>
