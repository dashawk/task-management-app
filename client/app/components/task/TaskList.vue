<template>
  <div class="space-y-2">
    <TaskListItem
      v-for="task in tasks"
      :key="task.id"
      :task="task"
      @toggle-completion="toggleTaskCompletion"
      @delete-task="deleteTask"
    />

    <!-- Empty State -->
    <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500">
      <p class="text-sm">No tasks for this date</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { TaskDisplay } from '~~/types/task-management'

import TaskListItem from './TaskListItem.vue'

interface Props {
  tasks: TaskDisplay[]
}

defineProps<Props>()

const emit = defineEmits<{
  'toggle-completion': [taskId: string]
  'delete-task': [taskId: string]
}>()

// Methods
const toggleTaskCompletion = (taskId: string) => {
  emit('toggle-completion', taskId)
}

const deleteTask = (taskId: string) => {
  emit('delete-task', taskId)
}
</script>
