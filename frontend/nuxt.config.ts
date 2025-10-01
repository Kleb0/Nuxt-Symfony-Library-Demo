import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  
  pages: true,
  ssr: true,
  
  // Force l'utilisation du système de pages
  dir: {
    pages: 'pages'
  },

  router: {
    options: {
      strict: false
    }
  },

  debug: true,
  
  components: {
    dirs: [
      '~/components'
    ]
  },
  
  css: ['~/assets/css/main.css'],
  
  modules: [
    '@nuxt/eslint',
    '@nuxt/fonts',
    '@nuxt/icon',
    '@nuxt/image'
  ],

  vite: {
    plugins: [
      tailwindcss()
    ]
  },

  image: {
    quality: 80,
    format: ['webp']
  },

  fonts: {
    families: [
      { name: 'Roboto', provider: 'google' }
    ]
  }
})
