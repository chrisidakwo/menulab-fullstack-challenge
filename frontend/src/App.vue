<script setup lang="ts">
import { RouterView } from "vue-router";
</script>

<template>
  <main class="container mx-auto">
    <header class="mb-8 h-32 flex w-full items-center">
      <h1 class="font-semibold">Weather Display Application</h1>
    </header>

    <RouterView />
  </main>
</template>

<style scoped>

</style>
