export interface Task {
  id: string
  title: string
  description?: string
  completed: boolean
  createdAt: Date
  updatedAt: Date
  dueDate?: Date
}

export interface DateItem {
  id: string
  label: string
  date: Date
  isToday?: boolean
  isYesterday?: boolean
  taskCount?: number
}

export interface TaskInputProps {
  placeholder?: string
  title?: string
  modelValue?: string
}

export interface DateSidebarProps {
  selectedDate?: Date
  dates?: DateItem[]
}

export interface TaskManagementLayoutProps {
  hasTasks?: boolean
  selectedDate?: Date
}

export interface TaskInputEmits {
  'update:modelValue': [value: string]
  'submit': [value: string]
  'focus': []
  'blur': []
}

export interface DateSidebarEmits {
  'date-select': [date: Date]
}
