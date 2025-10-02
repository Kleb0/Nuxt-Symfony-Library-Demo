<template>
  <header class="header-bg bg-[#ff6f49] min-h-[380px] w-full shadow-custom transition-shadow duration-300 flex items-center justify-between relative px-20 py-5 gap-10 max-xl:flex-col max-xl:px-5 max-xl:gap-8">
    <div class="rectangles-wrapper flex flex-col items-center justify-center flex-1 gap-8 ml-[150px]">
      <div class="rectangle rectangle-red-text">
        <span class="header-title text-[#ede9d0] text-6xl font-bold font-roboto tracking-wide text-center max-md:text-4xl">Nuxt Symfony Library</span>
      </div>
      <nav class="menu-bar flex flex-row items-center justify-center gap-2.5 w-[700px] h-[100px] rounded-3xl max-lg:w-[90%] max-lg:max-w-[600px] max-md:w-[95%] max-md:max-w-[500px] max-md:gap-1 max-sm:flex-wrap max-sm:h-auto max-sm:py-2">
        <ManagementButton />
        <NuxtLink to="/" class="menu-item text-[#ede9d0] text-3xl font-roboto font-medium no-underline py-3 px-6 rounded-xl transition-all duration-200 max-lg:text-2xl max-lg:py-2 max-lg:px-4 max-md:text-xl max-md:py-1 max-md:px-3 max-sm:text-lg">Accueil</NuxtLink>
        <NuxtLink to="/livres" class="menu-item text-[#ede9d0] text-3xl font-roboto font-medium no-underline py-3 px-6 rounded-xl transition-all duration-200 max-lg:text-2xl max-lg:py-2 max-lg:px-4 max-md:text-xl max-md:py-1 max-md:px-3 max-sm:text-lg">Livres</NuxtLink>
        <NuxtLink to="/about" class="menu-item text-[#ede9d0] text-3xl font-roboto font-medium no-underline py-3 px-6 rounded-xl transition-all duration-200 max-lg:text-2xl max-lg:py-2 max-lg:px-4 max-md:text-xl max-md:py-1 max-md:px-3 max-sm:text-lg">À propos</NuxtLink>
        <PanierButton />
      </nav>
    </div>
    <div class="loginstate-wrapper w-[300px] h-[280px] flex items-center justify-center flex-shrink-0 max-xl:w-full max-xl:max-w-[300px] max-xl:mb-[50px] max-xl:ml-[150px]">
      <LoginState />
    </div>
  </header>
</template>

<script setup>
import { inject } from 'vue'
import LoginState from './LoginState.vue'
import PanierButton from './PanierButton.vue'
import ManagementButton from './ManagementButton.vue'

const currentUser = inject('currentUser')
const updateCurrentUser = inject('updateCurrentUser')
</script>

<style scoped>

.font-roboto {
  font-family: 'Roboto', sans-serif !important;
}

.shadow-custom {
  box-shadow: 0 4px 16px 0 rgba(92, 235, 156, 0.15);
}

.menu-item:hover {
  background: #3b3434;
  color: #fff;
}

@media (max-width: 1200px) {
  .header-bg {
    transform: translateX(-50px);
    width: calc(100% + 50px);
  }
}
</style>