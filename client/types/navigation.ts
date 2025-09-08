/**
 * TypeScript interfaces for navigation components
 */

// User data interface
export interface User {
  id: string | number;
  name: string;
  email: string;
  avatar?: string;
}

// Logo component props
export interface AppLogoProps {
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}

// Search form props
export interface SearchFormProps {
  placeholder?: string;
  modelValue?: string;
  disabled?: boolean;
  className?: string;
}

// Search form emitted events
export interface SearchFormEmits {
  (event: 'update:modelValue', value: string): void;
  (event: 'search', query: string): void;
  (event: 'focus'): void;
  (event: 'blur'): void;
}

// User avatar props
export interface UserAvatarProps {
  user: User;
  size?: 'sm' | 'md' | 'lg';
  showDropdown?: boolean;
  className?: string;
}

// User avatar emitted events
export interface UserAvatarEmits {
  (event: 'profile-click'): void;
  (event: 'logout-click'): void;
  (event: 'settings-click'): void;
}

// Navigation bar props
export interface NavigationBarProps {
  user?: User;
  searchPlaceholder?: string;
  showSearch?: boolean;
  className?: string;
}

// Navigation bar emitted events
export interface NavigationBarEmits {
  (event: 'search', query: string): void;
  (event: 'profile-click'): void;
  (event: 'logout-click'): void;
  (event: 'settings-click'): void;
  (event: 'logo-click'): void;
}

// Size mapping for consistent sizing across components
export const SIZE_CLASSES = {
  logo: {
    sm: 'w-1 h-1',
    md: 'w-12 h-12',
    lg: 'w-16 h-16'
  },
  avatar: {
    sm: 'w-8 h-8',
    md: 'w-10 h-10',
    lg: 'w-12 h-12'
  }
} as const;
