import { createRouter, createWebHistory } from "vue-router";
import UsersWeather from "@/views/UsersWeather.vue";
import UserWeatherDetail from "@/views/UserWeatherDetail.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "users-weather",
      component: UsersWeather,
    },
    {
      path: "/:userId",
      name: "user-weather-detail",
      component: UserWeatherDetail,
    },
  ],
});

export default router;
