import type { Task, CreateTaskRequest, UpdateTaskRequest } from '~~/types/task-management'
import { useSanctumClient } from '#imports'

interface ApiResponse<T> {
  success: boolean
  message: string
  data: T
}

interface ApiPaginatedList<T> {
  success: boolean
  message: string
  data: T[]
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    from: number | null
    last_page: number
    links: Array<{ url: string | null; label: string; page: number | null; active: boolean }>
    path: string
    per_page: number
    to: number | null
    total: number
  }
}

export class TaskService {
  private readonly basePath = '/api/v1/tasks'

  /**
   * Get all tasks for the authenticated user
   */
  async getTasks(params?: { date?: string; per_page?: number }): Promise<ApiPaginatedList<Task>> {
    const query = new URLSearchParams()

    if (params?.date) {
      query.append('date', params.date)
    }

    if (params?.per_page) {
      query.append('per_page', params.per_page.toString())
    }

    const url = query.toString() ? `${this.basePath}?${query.toString()}` : this.basePath

    const client = useSanctumClient()
    const response = await client<ApiPaginatedList<Task>>(url, {
      method: 'GET'
    })

    return response
  }

  /**
   * Create a new task
   */
  async createTask(taskData: CreateTaskRequest): Promise<ApiResponse<Task>> {
    const client = useSanctumClient()
    const response = await client<ApiResponse<Task>>(this.basePath, {
      method: 'POST',
      body: taskData
    })

    return response
  }

  /**
   * Get a specific task by ID
   */
  async getTask(id: number): Promise<ApiResponse<Task>> {
    const client = useSanctumClient()
    const response = await client<ApiResponse<Task>>(`${this.basePath}/${id}`, {
      method: 'GET'
    })

    return response
  }

  /**
   * Update a task
   */
  async updateTask(id: number, taskData: UpdateTaskRequest): Promise<ApiResponse<Task>> {
    const client = useSanctumClient()
    const response = await client<ApiResponse<Task>>(`${this.basePath}/${id}`, {
      method: 'PATCH',
      body: taskData
    })

    return response
  }

  /**
   * Delete a task
   */
  async deleteTask(id: number): Promise<ApiResponse<null>> {
    const client = useSanctumClient()
    const response = await client<ApiResponse<null>>(`${this.basePath}/${id}`, {
      method: 'DELETE'
    })

    return response
  }

  /**
   * Toggle task completion status
   */
  async toggleTaskCompletion(id: number): Promise<ApiResponse<Task>> {
    const client = useSanctumClient()
    const response = await client<ApiResponse<Task>>(`${this.basePath}/${id}/toggle-completion`, {
      method: 'PATCH'
    })

    return response
  }

  /**
   * Reorder tasks in bulk
   */
  async reorderTasks(tasks: Array<{ id: number; order: number }>): Promise<ApiResponse<Task[]>> {
    const client = useSanctumClient()
    const response = await client<ApiResponse<Task[]>>(`${this.basePath}/reorder`, {
      method: 'POST',
      body: { tasks }
    })

    return response
  }
}

// Export a singleton instance
export const taskService = new TaskService()

// Utility functions for data transformation
export function transformTaskToDisplay(task: Task): import('~~/types/task-management').TaskDisplay {
  return {
    id: task.id,
    title: task.title,
    description: task.description,
    completed: task.completed,
    createdAt: new Date(task.created_at),
    updatedAt: new Date(task.updated_at),
    dueDate: task.due_date ? new Date(task.due_date) : undefined,
    userId: task.user_id,
    order: task.order
  }
}

export function formatDateForApi(date: Date): string {
  return date.toISOString().slice(0, 10) // YYYY-MM-DD format
}
