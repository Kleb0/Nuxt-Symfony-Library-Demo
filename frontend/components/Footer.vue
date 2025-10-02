<template>
  <footer class="w-full bg-gray-700 text-white pt-10 pb-5 mt-auto">
    <div class="max-w-6xl mx-auto flex justify-between px-5 md:flex-row flex-col md:gap-10 gap-5">
      <div class="flex-1">
        <h3 class="text-2xl mb-4 text-golden">Nuxt Symfony Library</h3>
        <p class="mb-2 text-gray-300">Une bibliothèque moderne pour vos projets Nuxt & Symfony</p>
      </div>
      
      <div class="flex-1">
        <h4 class="text-lg mb-3 text-golden">Navigation</h4>
        <ul class="list-none p-0">
          <li class="mb-2"><NuxtLink to="/" class="text-gray-300 no-underline transition-colors duration-200 hover:text-golden">Accueil</NuxtLink></li>
          <li class="mb-2"><NuxtLink to="/livres" class="text-gray-300 no-underline transition-colors duration-200 hover:text-golden">Livres</NuxtLink></li>
          <li class="mb-2"><NuxtLink to="/about" class="text-gray-300 no-underline transition-colors duration-200 hover:text-golden">À propos</NuxtLink></li>
        </ul>
      </div>
      
      <div class="flex-1">
        <h4 class="text-lg mb-3 text-golden">Contact</h4>
        <p class="mb-2 text-gray-300">© 2025 Nuxt Symfony Library</p>
        <p class="mb-2 text-gray-300">Tous droits réservés</p>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.text-golden {
  color: #DEA54A;
}
</style>