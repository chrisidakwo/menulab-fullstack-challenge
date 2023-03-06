<script setup lang="ts">
import UserWeatherCard from "@/components/UserWeatherCard.vue";
import type { User } from "@/models";
import { ref } from "vue";

const users = ref<User[]>([]);

const getUsers = async () => {
  const response = await fetch("http://localhost/");
  if (response.ok) {
    users.value = (await response.json()).users;
  }
};

// Call API
getUsers();
</script>

<template>
  <div class="grid xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
    <template v-for="user in users" :key="user.id">
      <user-weather-card :user="user" />
    </template>
  </div>
</template>
