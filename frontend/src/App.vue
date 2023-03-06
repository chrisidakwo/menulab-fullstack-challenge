<script setup lang="ts">
import { RouterView } from "vue-router";
</script>

<template>
  <main class="container mx-auto xs:mx-0 xs:px-4 xs:max-w-full">
    <header class="mb-4 h-32 flex w-full items-center">
      <h1 class="font-semibold">Weather Display Application</h1>
    </header>

    <RouterView />
  </main>
</template>

<style scoped>

</style>
