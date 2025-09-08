import type { ModuleOptions } from "@nuxt/schema";

declare module '@nuxt/schema' {
  interface NuxtConfig {
    sanctum?: ModuleOptions
  }

  interface NuxtOptions {
    sanctum?: ModuleOptions
  }
}
