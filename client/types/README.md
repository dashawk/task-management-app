# Global Types Configuration

This directory contains the global TypeScript configuration for the Vue.js Task Management Application.

## Files

### `global.d.ts`
Contains global type declarations that are available throughout the entire application without imports.

**Includes:**
- Asset module declarations (SVG, PNG, JPG, etc.)
- Navigation component interfaces
- User data structures
- Component props and emit types

### `navigation.ts` (Legacy)
Original navigation types file. **This file is now deprecated** as all types have been moved to global declarations.

## Global Types Available

All these types are available in any Vue component without imports:

```typescript
// User interface
interface User {
  id: string | number;
  name: string;
  email: string;
  avatar?: string;
}

// Component props interfaces
interface AppLogoProps { /* ... */ }
interface SearchFormProps { /* ... */ }
interface UserAvatarProps { /* ... */ }
interface NavigationBarProps { /* ... */ }

// Component emit interfaces
interface SearchFormEmits { /* ... */ }
interface UserAvatarEmits { /* ... */ }
interface NavigationBarEmits { /* ... */ }
```

## Usage in Components

### Before (with imports)
```vue
<script setup lang="ts">
import type { User, NavigationBarProps } from '../types/navigation';

const props = defineProps<NavigationBarProps>();
const user = ref<User>({ /* ... */ });
</script>
```

### After (global types)
```vue
<script setup lang="ts">
// No imports needed!
const props = defineProps<NavigationBarProps>();
const user = ref<User>({ /* ... */ });
</script>
```

## Constants

Navigation constants like `SIZE_CLASSES` are auto-imported from `utils/navigation-constants.ts`:

```vue
<script setup lang="ts">
// SIZE_CLASSES is automatically available
const logoClasses = SIZE_CLASSES.logo.md;
</script>
```

## Configuration

Global types are configured in:
1. **`types/global.d.ts`** - Type declarations
2. **`nuxt.config.ts`** - Auto-import configuration for utils
3. **`tsconfig.json`** - TypeScript compiler configuration

## Benefits

✅ **No import statements** needed for navigation types  
✅ **Consistent typing** across all components  
✅ **Better developer experience** with auto-completion  
✅ **Reduced boilerplate** code  
✅ **Type safety** maintained throughout the application
