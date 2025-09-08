/// <reference types="nuxt" />

// Asset module declarations
declare module '*.svg' {
  const content: string;
  export default content;
}

declare module '*.png' {
  const content: string;
  export default content;
}

declare module '*.jpg' {
  const content: string;
  export default content;
}

declare module '*.jpeg' {
  const content: string;
  export default content;
}

declare module '*.gif' {
  const content: string;
  export default content;
}

declare module '*.webp' {
  const content: string;
  export default content;
}

// // Global Navigation Types
// declare global {
//   // User data interface
//   interface User {
//     id: string | number;
//     name: string;
//     email: string;
//     avatar?: string;
//   }

//   // Logo component props
//   interface AppLogoProps {
//     size?: 'sm' | 'md' | 'lg';
//     className?: string;
//   }

//   // Search form props
//   interface SearchFormProps {
//     placeholder?: string;
//     modelValue?: string;
//     disabled?: boolean;
//     className?: string;
//   }

//   // Search form emitted events
//   interface SearchFormEmits {
//     (event: 'update:modelValue', value: string): void;
//     (event: 'search', query: string): void;
//     (event: 'focus'): void;
//     (event: 'blur'): void;
//   }

//   // User avatar props
//   interface UserAvatarProps {
//     user: User;
//     size?: 'sm' | 'md' | 'lg';
//     showDropdown?: boolean;
//     className?: string;
//   }

//   // User avatar emitted events
//   interface UserAvatarEmits {
//     (event: 'profile-click'): void;
//     (event: 'logout-click'): void;
//     (event: 'settings-click'): void;
//   }

//   // Navigation bar props
//   interface NavigationBarProps {
//     user?: User;
//     searchPlaceholder?: string;
//     showSearch?: boolean;
//     className?: string;
//   }

//   // Navigation bar emitted events
//   interface NavigationBarEmits {
//     (event: 'search', query: string): void;
//     (event: 'profile-click'): void;
//     (event: 'logout-click'): void;
//     (event: 'settings-click'): void;
//     (event: 'logo-click'): void;
//   }
// }

// export {}
