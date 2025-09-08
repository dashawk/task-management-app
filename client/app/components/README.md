# Navigation Components

This directory contains the navigation bar components for the Vue.js Task Management Application.

## Component Architecture

The navigation system follows atomic design principles with reusable, modular components:

### Directory Structure

```
components/
├── ui/                     # Atomic UI components
│   ├── AppLogo.vue        # Logo component
│   ├── SearchForm.vue     # Search input component
│   └── UserAvatar.vue     # User avatar with dropdown
├── TheNavigationBar.vue   # Main navigation bar component
└── README.md              # This file
```

## Components

### 1. AppLogo (`ui/AppLogo.vue`)

A simple, reusable logo component.

**Props:**
- `size?: 'sm' | 'md' | 'lg'` - Logo size (default: 'md')
- `className?: string` - Additional CSS classes

**Events:**
- `@click` - Emitted when logo is clicked

**Usage:**
```vue
<AppLogo size="lg" @click="navigateHome" />
```

### 2. SearchForm (`ui/SearchForm.vue`)

A search input component with icon and proper event handling.

**Props:**
- `placeholder?: string` - Input placeholder (default: 'Search...')
- `modelValue?: string` - v-model value
- `disabled?: boolean` - Disable input
- `className?: string` - Additional CSS classes

**Events:**
- `@update:modelValue` - v-model update
- `@search` - Emitted on Enter key press
- `@focus` - Input focus event
- `@blur` - Input blur event

**Usage:**
```vue
<SearchForm 
  v-model="searchQuery"
  placeholder="Search tasks..."
  @search="handleSearch"
/>
```

### 3. UserAvatar (`ui/UserAvatar.vue`)

User avatar component with dropdown menu functionality.

**Props:**
- `user: User` - User object (required)
- `size?: 'sm' | 'md' | 'lg'` - Avatar size (default: 'md')
- `showDropdown?: boolean` - Show dropdown menu (default: true)
- `className?: string` - Additional CSS classes

**Events:**
- `@profile-click` - Profile menu item clicked
- `@settings-click` - Settings menu item clicked
- `@logout-click` - Logout menu item clicked

**Usage:**
```vue
<UserAvatar 
  :user="currentUser"
  size="md"
  @profile-click="goToProfile"
  @logout-click="logout"
/>
```

### 4. TheNavigationBar (`TheNavigationBar.vue`)

Main navigation bar that combines all atomic components.

**Props:**
- `user?: User` - Current user object
- `searchPlaceholder?: string` - Search placeholder text
- `showSearch?: boolean` - Show search form (default: true)
- `className?: string` - Additional CSS classes

**Events:**
- `@search` - Search query submitted
- `@logo-click` - Logo clicked
- `@profile-click` - Profile menu clicked
- `@settings-click` - Settings menu clicked
- `@logout-click` - Logout clicked

**Usage:**
```vue
<TheNavigationBar
  :user="currentUser"
  search-placeholder="Search tasks..."
  @search="handleSearch"
  @logo-click="goHome"
  @logout-click="logout"
/>
```

## TypeScript Interfaces

All components use **global TypeScript interfaces** that are available throughout the application without imports:

- `User` - User data structure
- `AppLogoProps` - Logo component props
- `SearchFormProps` & `SearchFormEmits` - Search form types
- `UserAvatarProps` & `UserAvatarEmits` - Avatar component types
- `NavigationBarProps` & `NavigationBarEmits` - Navigation bar types

These types are globally declared in `types/global.d.ts` and automatically available in all Vue components.

## Styling

Components follow the existing design system:
- **Colors:** Gray color palette (gray-900, gray-700, gray-500, etc.)
- **Typography:** Font weights (font-medium, font-semibold)
- **Spacing:** Consistent padding and margins
- **Borders:** Rounded corners and subtle borders
- **Focus states:** Gray-500 ring for accessibility

## Auto-Import

Components and utilities are automatically imported by Nuxt 3:

- **Components**: Use directly without manual imports
- **Types**: Global TypeScript interfaces available everywhere
- **Constants**: `SIZE_CLASSES` auto-imported from `utils/navigation-constants.ts`

```vue
<template>
  <TheNavigationBar :user="user" @search="handleSearch" />
</template>

<script setup lang="ts">
// No imports needed! Global types and constants are available
const props = defineProps<NavigationBarProps>();
const logoSize = SIZE_CLASSES.logo.md; // Auto-imported constant
</script>
```

## Testing

To test the components:

1. Start the development server: `pnpm dev`
2. Navigate to `http://localhost:3001`
3. Test the search functionality
4. Click the user avatar to test the dropdown
5. Click the logo to test navigation

## Accessibility

All components include proper accessibility features:
- ARIA attributes for screen readers
- Keyboard navigation support
- Focus management
- Semantic HTML structure
