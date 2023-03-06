<script setup lang="ts">
import { onMounted, ref } from "vue";
import type { User } from "@/models";
import { useRoute } from "vue-router";

const userId = useRoute().params.userId;
const user = ref<User | null>(null);
const weatherDetail = ref(null);

const getWeatherDetails = async () => {
  const response = await fetch(`http://localhost/${userId}/weather`);

  return response.json();
};

onMounted(async () => {
  const data = await getWeatherDetails();

  user.value = data.user;
  weatherDetail.value = data.weather;
});
</script>

<template>
  <div class="w-full">
    <div class="border-t pt-4" v-if="weatherDetail">
      <div class="w-full mb-12">
        <h1>{{ user.name }}</h1>
        <div class="flex items-end">
          <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-6 h-6 mr-2"
          >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"
            />
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"
            />
          </svg>

          <h6>
            {{ weatherDetail.location.city }}, {{ weatherDetail.location.state }}
          </h6>
        </div>
      </div>

      <div class="w-full">
        <h1>{{ weatherDetail.temperature.value }}</h1>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
