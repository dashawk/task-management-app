<template>
  <aside class="w-64 bg-white border-r border-gray-200 h-full overflow-y-auto">
    <div class="p-4">
      <!-- Header with refresh icon -->
      <div class="flex items-center justify-between mb-6">
        <button type="button" class="p-2 text-gray-400 hover:text-gray-600 transition-colors" @click="refreshDates">
          <RefreshCw :size="20" />
        </button>
        <button type="button" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
          <User :size="20" />
        </button>
      </div>

      <!-- Date Navigation -->
      <nav class="space-y-1">
        <div v-for="dateItem in dateItems" :key="dateItem.id" class="group">
          <button
            type="button"
            class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors"
            :class="[isSelected(dateItem.date) ? 'bg-black text-white' : 'text-gray-700 hover:bg-gray-100']"
            @click="selectDate(dateItem.date)"
          >
            <div class="flex items-center justify-between">
              <span>{{ dateItem.label }}</span>
              <span
                v-if="dateItem.taskCount && dateItem.taskCount > 0"
                class="text-xs px-2 py-1 rounded-full"
                :class="[isSelected(dateItem.date) ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-600']"
              >
                {{ dateItem.taskCount }}
              </span>
            </div>
          </button>
        </div>
      </nav>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { RefreshCw, User } from 'lucide-vue-next'
import type { DateItem } from '~~/types/task-management'

interface Props {
  selectedDate?: Date
  dates?: DateItem[]
}

const props = withDefaults(defineProps<Props>(), {
  selectedDate: () => new Date(),
  dates: () => []
})

const emit = defineEmits<{
  'date-select': [date: Date]
}>()

// Generate date items for the sidebar
const dateItems = computed<DateItem[]>(() => {
  if (props.dates && props.dates.length > 0) {
    return props.dates
  }

  // Generate default date items
  const items: DateItem[] = []
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)

  // Add Today
  items.push({
    id: 'today',
    label: 'Today',
    date: today,
    isToday: true,
    taskCount: 0
  })

  // Add Yesterday
  items.push({
    id: 'yesterday',
    label: 'Yesterday',
    date: yesterday,
    isYesterday: true,
    taskCount: 0
  })

  // Add current week dates
  const currentWeekStart = new Date(today)
  currentWeekStart.setDate(today.getDate() - today.getDay())

  for (let i = 2; i <= 6; i++) {
    const date = new Date(currentWeekStart)
    date.setDate(currentWeekStart.getDate() + (7 - i))

    if (date < yesterday) {
      items.push({
        id: `day-${i}`,
        label: formatDateLabel(date),
        date: date,
        taskCount: 0
      })
    }
  }

  // Add previous weeks
  const weeksToShow = 3
  for (let week = 1; week <= weeksToShow; week++) {
    const weekStart = new Date(currentWeekStart)
    weekStart.setDate(currentWeekStart.getDate() - week * 7)

    // Add week header
    items.push({
      id: `week-${week}-header`,
      label: getWeekLabel(weekStart),
      date: weekStart,
      taskCount: 0
    })

    // Add days of the week
    for (let day = 0; day < 7; day++) {
      const date = new Date(weekStart)
      date.setDate(weekStart.getDate() + day)

      items.push({
        id: `week-${week}-day-${day}`,
        label: formatDateLabel(date),
        date: date,
        taskCount: 0
      })
    }
  }

  return items
})

const formatDateLabel = (date: Date): string => {
  const options: Intl.DateTimeFormatOptions = {
    weekday: 'long',
    month: 'long',
    day: 'numeric'
  }
  return date.toLocaleDateString('en-US', options)
}

const getWeekLabel = (weekStart: Date): string => {
  const weekEnd = new Date(weekStart)
  weekEnd.setDate(weekStart.getDate() + 6)

  const startMonth = weekStart.toLocaleDateString('en-US', { month: 'long' })
  const endMonth = weekEnd.toLocaleDateString('en-US', { month: 'long' })

  if (startMonth === endMonth) {
    return `${weekStart.getDate()}-${weekEnd.getDate()} ${startMonth}`
  } else {
    return `${weekStart.getDate()} ${startMonth} - ${weekEnd.getDate()} ${endMonth}`
  }
}

const isSelected = (date: Date): boolean => {
  if (!props.selectedDate) return false
  return date.toDateString() === props.selectedDate.toDateString()
}

const selectDate = (date: Date) => {
  emit('date-select', date)
}

const refreshDates = () => {
  // Emit refresh event or handle refresh logic
  console.log('Refreshing dates...')
}
</script>
