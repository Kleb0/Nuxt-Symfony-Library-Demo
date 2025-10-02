<template>
  <div class="card w-75 h-112 rounded-2xl shadow-lg flex flex-col overflow-hidden bg-white transition-all duration-300 ease-in-out cursor-pointer">
    <div 
      class="card-content flex-1 bg-cover bg-center bg-no-repeat bg-gray-100 flex items-center justify-center"
      :style="{ backgroundImage: `url(${getImageUrl(book?.image)})` }"
    >
    </div>
    <div class="card-title p-4 bg-white min-h-30 flex flex-col items-center justify-between gap-3">
      <h3 class="font-roboto text-sm font-semibold text-gray-800 text-center m-0 leading-tight flex-1 flex items-center">
        {{ book?.titre }}
      </h3>
      <button 
        class="detail-button bg-gray-800 text-white border-none py-2.5 px-5 rounded-lg font-medium text-base cursor-pointer transition-colors duration-200"
        @click="goToDetail"
      >
        Détail
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const props = defineProps({
  book: {
    type: Object,
    default: () => ({})
  }
});

const router = useRouter()

const getImageUrl = (imagePath) => {
  if (!imagePath) return ''
  return `http://localhost:8000/${imagePath}`
}

const goToDetail = () => {
  const b = props.book || {}
  
  const authorNames = b.authors ? b.authors.map(author => 
    author.fullName || `${author.firstName || ''} ${author.lastName || ''}`.trim()
  ).join(', ') : ''
  const categoryNames = b.categories ? b.categories.map(category => category.name).join(', ') : ''
  
  router.push({
    path: '/Book-Detail',
    query: {
      id: b.id || '',
      titre: b.titre || '',
      auteur: authorNames,
      categorie: categoryNames,
      image: getImageUrl(b.image) || '',
      resume: b.description || '',
      unitPrice: b.prix ? String(b.prix) : ''
    }
  })
}


</script>

<style scoped>
.card {
  width: 300px;
  height: 450px;
  box-shadow: 0 2px 12px 0 rgba(225, 29, 72, 0.12);
  will-change: transform;
  backface-visibility: hidden;
  transform: translateZ(0);
}

.card:hover {
  transform: scale(1.05) translateZ(0);
  box-shadow: 0 8px 32px 0 rgba(225, 29, 72, 0.25);
}

.detail-button:hover {
  background-color: #2a1f1f;
}

.font-roboto {
  font-family: 'Roboto', sans-serif;
}
</style>