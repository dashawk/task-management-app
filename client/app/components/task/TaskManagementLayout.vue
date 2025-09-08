<template>
  <div class="flex h-full bg-gray-50">
    <!-- Date Sidebar -->
    <DateSidebar :selected-date="selectedDate" :dates="dateItems" @date-select="handleDateSelect" />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden min-h-0">
      <!-- Content Container with proper flex layout -->
      <div class="flex-1 flex flex-col bg-white border-l border-gray-200 min-h-0">
        <!-- Empty State - Show when no tasks -->
        <div v-if="!hasTasks" class="h-full">
          <TaskInput
            v-model="newTaskInput"
            :title="emptyStateTitle"
            :placeholder="taskInputPlaceholder"
            @submit="handleTaskSubmit"
            @focus="handleTaskInputFocus"
            @blur="handleTaskInputBlur"
          />
        </div>

        <!-- Task List State - Show when tasks exist -->
        <template v-else>
          <!-- Scrollable task list area that takes all available space -->
          <div ref="taskListScrollRef" class="flex-1 overflow-y-auto p-8 pb-0 min-h-0 flex flex-col">
            <div class="flex-1">
              <TaskList
                :tasks="filteredTasks"
                @toggle-completion="handleToggleCompletion"
                @delete-task="handleDeleteTask"
              />
              <div ref="bottomAnchorRef" class="h-px"></div>
            </div>
          </div>

          <!-- Fixed input area at bottom - always at viewport bottom -->
          <div class="flex-shrink-0 p-8 pt-6 bg-white border-t border-gray-100">
            <div class="relative">
              <input
                ref="addTaskInputRef"
                v-model="newTaskInput"
                type="text"
                placeholder="What else do you need to do?"
                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @keydown.enter="handleTaskSubmit"
                @focus="handleTaskInputFocus"
                @blur="handleTaskInputBlur"
              />
              <button
                v-if="newTaskInput.trim()"
                type="button"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors duration-200"
                @click="handleTaskSubmit"
              >
                <ArrowUp :size="16" />
              </button>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ArrowUp } from 'lucide-vue-next'
import type { DateItem, Task } from '~~/types/task-management'

interface Props {
  hasTasks?: boolean
  selectedDate?: Date
  tasks?: Task[]
}

const props = withDefaults(defineProps<Props>(), {
  hasTasks: false,
  selectedDate: () => new Date(),
  tasks: () => []
})

const emit = defineEmits<{
  'task-submit': [task: string]
  'date-select': [date: Date]
  'task-input-focus': []
  'task-input-blur': []
  'toggle-completion': [taskId: string]
  'delete-task': [taskId: string]
}>()

// Reactive state
const selectedDate = ref(props.selectedDate)
const newTaskInput = ref('')
const addTaskInputRef = ref<HTMLInputElement>()
const taskListScrollRef = ref<HTMLDivElement>()
const bottomAnchorRef = ref<HTMLDivElement>()

// Mock data - replace with actual data
const dateItems = ref<DateItem[]>([])

// Computed properties
const filteredTasks = computed(() => {
  if (!props.tasks) return []

  // Filter tasks for the selected date
  return props.tasks.filter(task => {
    if (!task.dueDate) return false
    const taskDate = new Date(task.dueDate)
    return taskDate.toDateString() === selectedDate.value.toDateString()
  })
})

const emptyStateTitle = computed(() => {
  const today = new Date()
  if (selectedDate.value.toDateString() === today.toDateString()) {
    return 'What do you have in mind?'
  }
  return `What did you plan for ${formatSelectedDate(selectedDate.value)}?`
})

const taskInputPlaceholder = computed(() => {
  const today = new Date()
  if (selectedDate.value.toDateString() === today.toDateString()) {
    return 'Write the task you plan to do today here...'
  }

  // Auto-scroll helpers
  const scrollToBottom = (smooth = true) => {
    nextTick(() => {
      if (taskListScrollRef.value) {
        taskListScrollRef.value.scrollTo({
          top: taskListScrollRef.value.scrollHeight,
          behavior: smooth ? 'smooth' : 'auto'
        })
      } else if (bottomAnchorRef.value) {
        bottomAnchorRef.value.scrollIntoView({ behavior: smooth ? 'smooth' : 'auto' })
      }
    })
  }

  onMounted(() => {
    // Ensure latest items visible on initial render
    scrollToBottom(false)
  })

  // Scroll to latest whenever the filtered list changes (e.g., new task added)
  watch(filteredTasks, (newVal, oldVal) => {
    if (!oldVal || newVal.length >= oldVal.length) {
      scrollToBottom(true)
    }
  })

  // When switching dates, jump to bottom without animation
  watch(selectedDate, () => {
    scrollToBottom(false)
  })

  return 'Write what you planned for this day...'
})

// Methods
const formatSelectedDate = (date: Date): string => {
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)

  if (date.toDateString() === today.toDateString()) {
    return 'Today'
  } else if (date.toDateString() === yesterday.toDateString()) {
    return 'Yesterday'
  } else {
    return date.toLocaleDateString('en-US', {
      weekday: 'long',
      month: 'long',
      day: 'numeric'
    })
  }
}

const handleDateSelect = (date: Date) => {
  selectedDate.value = date
  emit('date-select', date)
  newTaskInput.value = ''
}

const handleTaskSubmit = () => {
  const task = newTaskInput.value.trim()
  if (task) {
    emit('task-submit', task)
    newTaskInput.value = ''
  }
}

const handleTaskInputFocus = () => {
  emit('task-input-focus')
}

const handleTaskInputBlur = () => {
  emit('task-input-blur')
}

const handleToggleCompletion = (taskId: string) => {
  emit('toggle-completion', taskId)
}

const handleDeleteTask = (taskId: string) => {
  emit('delete-task', taskId)
}

// Watch for external prop changes
watch(
  () => props.selectedDate,
  newDate => {
    selectedDate.value = newDate
  }
)

watch(
  () => props.hasTasks,
  newHasTasks => {
    if (!newHasTasks) {
      newTaskInput.value = ''
    }
  }
)
</script>
