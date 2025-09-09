# Focus Fix Test

## Issue

When creating tasks, the input is not focused after task creation.

## Expected Behavior

1. When the app loads with no tasks, the center input should be focused (this already works)
2. When creating the first task from the empty state, the bottom input should automatically be focused
3. When creating subsequent tasks, the bottom input should remain focused

## Test Steps

1. Open the application at http://localhost:3001
2. Login if needed
3. Verify the center input is focused on empty state
4. Type a task and press Enter
5. **Check**: The bottom input should automatically be focused after the task is created
6. Type another task and press Enter
7. **Check**: The bottom input should remain focused

## Implementation Details (Updated Approach)

- Added `ref="bottomTaskInputRef"` to the bottom TaskInput component in TaskManagementLayout.vue
- Added `defineExpose({ inputRef, focus: () => inputRef.value?.focus() })` to TaskInput.vue
- Added `focusBottomInput()` method to TaskManagementLayout.vue and exposed it via `defineExpose`
- Added ref to TaskManagementLayout in index.vue: `ref="taskManagementLayoutRef"`
- Modified `handleTaskSubmit` in index.vue to call `taskManagementLayoutRef.value?.focusBottomInput()` after successful task creation
- This approach directly focuses the input after task creation instead of relying on reactive watchers

## Files Modified

1. `client/app/components/task/TaskManagementLayout.vue`
2. `client/app/components/task/TaskInput.vue`
3. `client/app/pages/index.vue`
