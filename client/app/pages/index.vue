<template>
  <div class="p-8">
    <div class="hmr-test mb-4">
      <h1>🚀 HMR Test - Docker Environment</h1>
      <p>Hot Module Replacement is working perfectly!</p>
    </div>

    <div class="space-y-4">
      <p class="text-lg">This is a test to check if HMR is working properly.</p>
      <p class="text-blue-600 font-semibold">Current time: {{ new Date().toLocaleTimeString() }}</p>
      <p class="text-green-600 font-bold">✅ HMR is working in Docker! 🎉</p>

      <div class="bg-gray-100 p-4 rounded-lg">
        <h2 class="text-xl font-bold mb-2">HMR Features Working:</h2>
        <ul class="list-disc list-inside space-y-1">
          <li>Vue component updates</li>
          <li>CSS/TailwindCSS updates</li>
          <li>File watching with polling</li>
          <li>Docker container networking</li>
        </ul>
      </div>
    </div>
  </div>
</template>
