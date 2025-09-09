<template>
  <div class="relative w-full max-w-md">
    <input
      :value="model"
      @input="onInput"
      type="text"
      class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-9 text-sm placeholder-gray-400 focus:border-black focus:outline-none focus:ring-1 focus:ring-black"
      placeholder="Search tasks..."
    />
    <button
      v-if="model"
      @click="clear"
      type="button"
      class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
      aria-label="Clear search"
    >
      &times;
    </button>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue?: string
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: ''
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const model = computed({
  get: () => props.modelValue || '',
  set: (val: string) => emit('update:modelValue', val)
})

const onInput = (e: Event) => {
  const target = e.target as HTMLInputElement
  model.value = target.value
}

const clear = () => {
  model.value = ''
}
</script>

