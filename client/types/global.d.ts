/// <reference types="nuxt" />

// Middleware type declarations
declare module 'nuxt/app' {
  interface PageMeta {
    middleware?:
      | 'sanctum:auth'
      | 'sanctum:guest'
      | Array<'sanctum:auth' | 'sanctum:guest'>
      | import('vue-router').NavigationGuard
      | Array<import('vue-router').NavigationGuard>
  }
}

// Asset module declarations
declare module '*.svg' {
  const content: string
  export default content
}

declare module '*.png' {
  const content: string
  export default content
}

declare module '*.jpg' {
  const content: string
  export default content
}

declare module '*.jpeg' {
  const content: string
  export default content
}

declare module '*.gif' {
  const content: string
  export default content
}

declare module '*.webp' {
  const content: string
  export default content
}
