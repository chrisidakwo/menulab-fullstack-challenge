import type { MeasuredItem } from "@/models/MeasuredItem";

export interface WeatherHighlight {
  location: {
    city: string;
    state: string;
  };
  isDayTime: boolean;
  temperature: MeasuredItem;
  temperatureTrend: string;
  precipitation: MeasuredItem;
  humidity: MeasuredItem;
  wind: {
    speed: string;
    direction: string;
  };
  icon: string;
  shortDescription: string;
}
