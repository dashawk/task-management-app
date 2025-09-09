export interface Task {
  id: number
  title: string
  description?: string
  completed: boolean
  created_at: string
  updated_at: string
  due_date?: string
  user_id: number
}

// Frontend-friendly version with Date objects
export interface TaskDisplay {
  id: number
  title: string
  description?: string
  completed: boolean
  createdAt: Date
  updatedAt: Date
  dueDate?: Date
  userId: number
}

// API request/response types
export interface CreateTaskRequest {
  title: string
  description?: string
  due_date?: string
  completed?: boolean
}

export interface UpdateTaskRequest {
  title?: string
  description?: string
  due_date?: string
  completed?: boolean
}

export interface DateItem {
  id: string
  label: string
  date: Date
  isToday?: boolean
  isYesterday?: boolean
  isHeader?: boolean
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
  submit: [value: string]
  focus: []
  blur: []
}

export interface DateSidebarEmits {
  'date-select': [date: Date]
}
