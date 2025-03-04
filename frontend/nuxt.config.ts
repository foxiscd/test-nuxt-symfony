// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },

  css: [
    '~/assets/scss/main.scss',
    '~/assets/scss/media.scss',
  ],

  vite: {
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: `@import "~/assets/scss/variables.scss";`
        }
      }
    }
  },

  modules: [
    '@pinia/nuxt'
  ],

  compatibilityDate: '2024-07-05',
})