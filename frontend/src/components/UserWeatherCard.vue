<script setup lang="ts">
import { RouterLink } from "vue-router";
import type { User, WeatherHighlight } from "@/models";
import { ref, onMounted } from "vue";
import UserWeatherCardSkeleton from "@/components/UserWeatherCardSkeleton.vue";

const props = defineProps<{ user: User }>();

const weatherHighlight = ref<WeatherHighlight | null>(null);

const getUserWeatherHighlight = async (user: User) => {
  const response = await fetch(
    `http://localhost/${user.id}/weather/${user.latitude},${user.longitude}?highlight=true`
  );

  return response.json();
};

onMounted(async () => {
  const data = await getUserWeatherHighlight(props.user);
  weatherHighlight.value = data.data;
});
</script>

<template>
  <template v-if="weatherHighlight === null">
    <user-weather-card-skeleton />
  </template>

  <div
    v-else
    class="block relative bg-white text-white min-h-[150px] border rounded shadow-sm"
    :style="`background-image: url('${weatherHighlight.icon}'); background-repeat: no-repeat; background-size: cover`"
  >
    <div class="block p-4 bg-blue-900/40">
      <div class="h-full">
        <div class="w-full mb-5">
          <h6 class="mb-1 hover:underline">
            <RouterLink :to="`/${user.id}`">{{ user.name }}</RouterLink>
          </h6>
          <small class="flex items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 26 26"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-5 h-5 mr-1"
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

            <span>
              {{ weatherHighlight.location.city }},
              {{ weatherHighlight.location.state }}
            </span>
          </small>
        </div>

        <div class="w-full pb-3 mb-4">
          <div class="flex items-end font-serif">
            <div class="min-w-[40%]">
              <h1>
                <span v-if="weatherHighlight.temperature">
                  {{ weatherHighlight.temperature.value }}
                  <sup>o</sup>{{ weatherHighlight.temperature.unit }}
                </span>
              </h1>
              <p class="flex">
                <span v-if="weatherHighlight.temperatureTrend">
                  <svg
                    v-if="weatherHighlight.temperatureTrend === 'falling'"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 26 26"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-5 h-5 mr-2"
                    title="Temperature falling"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181"
                    />
                  </svg>

                  <svg
                    v-else
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 26 26"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-5 h-5 mr-2"
                    title="Temperature rising"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"
                    />
                  </svg>
                </span>

                <span>Today</span>
              </p>
            </div>

            <div>
              <small class="text-xs">
                {{ weatherHighlight.shortDescription }}
              </small>
            </div>
          </div>
        </div>

        <div class="w-full">
          <ul class="font-sans">
            <li class="text-xs mb-1">
              Precipitation: {{ weatherHighlight.precipitation.value ?? 0 }}%
            </li>
            <li class="text-xs mb-1">
              Humidity: {{ weatherHighlight.humidity.value ?? 0 }}%
            </li>
            <li class="text-xs mb-1">
              Wind Speed: {{ weatherHighlight.wind?.speed }}
            </li>
            <li class="text-xs mb-1">
              Wind Direction: {{ weatherHighlight.wind?.direction }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
