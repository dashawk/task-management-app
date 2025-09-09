<template>
  <div
    class="group relative flex items-center p-3 rounded-sm bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
    :class="{
      'opacity-50 scale-95': isDragging,
      'border-2 border-blue-400 border-dashed bg-blue-50': isDragOver,
      'cursor-text': isEditing
    }"
    :draggable="!isEditing"
    @dragstart="handleDragStart"
    @dragend="handleDragEnd"
    @dragover.prevent="handleDragOver"
    @dragleave="handleDragLeave"
    @drop.prevent="handleDrop"
  >
    <!-- Drag Handle -->
    <div
      v-if="!isEditing"
      class="flex-shrink-0 mr-2 text-gray-400 hover:text-gray-600 cursor-move opacity-0 group-hover:opacity-100 transition-opacity duration-200"
    >
      <GripVertical :size="16" />
    </div>

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
      <!-- Edit Mode -->
      <div v-if="isEditing" class="space-y-2">
        <input
          ref="titleInputRef"
          v-model="editTitle"
          type="text"
          class="w-full text-sm bg-white border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          @keydown.enter="saveEdit"
          @keydown.escape="cancelEdit"
          @blur="saveEdit"
        />
        <div class="flex items-center space-x-2">
          <button
            type="button"
            class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
            @click="saveEdit"
          >
            Save
          </button>
          <button
            type="button"
            class="text-xs px-2 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors"
            @click="cancelEdit"
          >
            Cancel
          </button>
        </div>
      </div>

      <!-- Display Mode -->
      <div v-else>
        <p
          class="text-sm transition-all duration-200 cursor-pointer hover:bg-gray-100 rounded px-1 py-0.5 -mx-1"
          :class="[
            task.completed ? 'text-gray-500 line-through' : 'text-gray-900',
            isDragging ? 'pointer-events-none' : ''
          ]"
          @click="startEdit"
        >
          {{ task.title }}
        </p>
        <p
          v-if="task.description"
          class="text-xs mt-1 transition-all duration-200 cursor-pointer hover:bg-gray-100 rounded px-1 py-0.5 -mx-1"
          :class="[
            task.completed ? 'text-gray-400 line-through' : 'text-gray-600',
            isDragging ? 'pointer-events-none' : ''
          ]"
          @click="startEdit"
        >
          {{ task.description }}
        </p>
      </div>
    </div>

    <!-- Right-side actions/time: no layout shift on hover -->
    <div class="flex-shrink-0 ml-3 h-6 relative flex items-center">
      <!-- Delete button (shown on hover via CSS, space always reserved) -->
      <button
        type="button"
        class="h-6 w-6 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200 rounded-md hover:bg-red-50 opacity-0 group-hover:opacity-100 relative z-10"
        @click.stop="onDelete(task.id.toString())"
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
import { Check, Trash2, GripVertical } from 'lucide-vue-next'
import type { TaskDisplay, UpdateTaskRequest } from '~~/types/task-management'

interface Props {
  task: TaskDisplay
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'toggle-completion': [taskId: string]
  'delete-task': [taskId: string]
  'update-task': [taskId: string, updates: UpdateTaskRequest]
  'drag-start': [taskId: string]
  'drag-end': []
  'drag-over': [taskId: string]
  drop: [draggedTaskId: string, targetTaskId: string]
}>()

// Editing state
const isEditing = ref(false)
const editTitle = ref('')
const titleInputRef = ref<HTMLInputElement>()

// Drag and drop state
const isDragging = ref(false)
const isDragOver = ref(false)

const onToggle = (taskId: string) => emit('toggle-completion', taskId)
const onDelete = (taskId: string) => emit('delete-task', taskId)

const startEdit = () => {
  if (props.task.completed || isDragging.value) return // Don't allow editing completed tasks or during drag

  isEditing.value = true
  editTitle.value = props.task.title

  nextTick(() => {
    titleInputRef.value?.focus()
    titleInputRef.value?.select()
  })
}

const saveEdit = () => {
  if (!isEditing.value) return

  const trimmedTitle = editTitle.value.trim()
  if (!trimmedTitle) {
    // Don't save if title is empty
    cancelEdit()
    return
  }

  const updates: UpdateTaskRequest = {
    title: trimmedTitle
  }

  emit('update-task', props.task.id.toString(), updates)
  isEditing.value = false
}

const cancelEdit = () => {
  isEditing.value = false
  editTitle.value = ''
}

// Drag and drop handlers
const handleDragStart = (event: DragEvent) => {
  if (isEditing.value) {
    event.preventDefault()
    return
  }

  isDragging.value = true
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move'
    event.dataTransfer.setData('text/plain', props.task.id.toString())
  }
  emit('drag-start', props.task.id.toString())
}

const handleDragEnd = () => {
  isDragging.value = false
  emit('drag-end')
}

const handleDragOver = (event: DragEvent) => {
  if (isEditing.value) return

  event.preventDefault()
  if (event.dataTransfer) {
    event.dataTransfer.dropEffect = 'move'
  }
  isDragOver.value = true
  emit('drag-over', props.task.id.toString())
}

const handleDragLeave = () => {
  isDragOver.value = false
}

const handleDrop = (event: DragEvent) => {
  if (isEditing.value) return

  event.preventDefault()
  isDragOver.value = false

  const draggedTaskId = event.dataTransfer?.getData('text/plain')
  if (draggedTaskId && draggedTaskId !== props.task.id.toString()) {
    emit('drop', draggedTaskId, props.task.id.toString())
  }
}

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
