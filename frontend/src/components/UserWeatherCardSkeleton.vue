<script lang="ts" setup></script>

<template>
  <div class="bg-white rounded shadow-sm border min-h-[150px] p-4">
    <div>
      <div class="w-full">
        <div
          class="bg-gray-100 pulse"
          style="height: 25px; margin-bottom: 4px; width: 70%"
        ></div>
        <div
          class="bg-gray-100 pulse"
          style="height: 20px; margin-bottom: 12px; width: 85%"
        ></div>
      </div>
    </div>
  </div>
</template>

<style scoped>
  .pulse {
    animation: pulse-bg 1s infinite;
    border-radius: 4px;
  }
</style>
