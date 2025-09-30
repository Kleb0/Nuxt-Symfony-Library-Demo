<template>
  <div class="max-w-[200px] mx-auto bg-white border-2 border-gray-400 rounded-xl shadow-md overflow-hidden">
    <!-- Header avec titre -->
    <div class="bg-gray-50 border-b border-gray-300 p-3 text-center">
      <h3 class="font-roboto text-lg font-bold text-gray-900">{{ book.titre }}</h3>
    </div>
    
    <!-- Container image avec bordure interne -->
    <div class="p-4">
      <div class="w-full h-32 border-2 border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden">
        <img
          :src="book.image"
          :alt="book.titre"
          class="max-w-full max-h-full object-contain"
          @error="handleImageError"
          loading="lazy"
        />
      </div>
    </div>
    
    <!-- Content body -->
    <div class="px-4 pb-4">
      <!-- Auteur -->
      <div class="mb-3">
        <span class="font-roboto text-sm font-bold text-gray-900">auteur</span>
        <p class="font-roboto text-sm text-gray-700">{{ book.auteur }}</p>
      </div>
      
      <!-- Résumé -->
      <div class="mb-4">
        <span class="font-roboto text-sm font-bold text-gray-900">résumé :</span>
        <p class="font-roboto text-sm text-gray-700 leading-relaxed line-clamp-3">{{ book.resume }}</p>
      </div>
      
      <!-- Prix -->
      <div class="text-right">
        <span class="font-roboto text-lg font-bold text-gray-900">prix</span>
        <p class="font-roboto text-lg font-bold text-emerald-700">{{ book.unitPrice }}€</p>
      </div>
    </div>
  </div>
</template><script setup lang="ts">
import type { Book as BookType } from '../composables/useBooks';

defineProps<{ book: BookType }>();

const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement;
  img.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE5MiIgdmlld0JveD0iMCAwIDIwMCAxOTIiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMTkyIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMDAgOTZDMTA2LjYyNyA5NiAxMTIgOTAuNjI3NCAxMTIgODRDMTEyIDc3LjM3MjYgMTA2LjYyNyA3MiAxMDAgNzJDOTMuMzcyNiA3MiA4OCA3Ny4zNzI2IDg4IDg0Qzg4IDkwLjYyNzQgOTMuMzcyNiA5NiAxMDAgOTZaIiBmaWxsPSIjOUNBM0FGIi8+CjxwYXRoIGQ9Ik0xNjAgMTQ0SDQwVjEyOEw2NCA5NkwxMDAgMTI4TDE0NCAxMDRMMTYwIDEyOFYxNDRaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo=';
  img.alt = 'Image non disponible';
};
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
