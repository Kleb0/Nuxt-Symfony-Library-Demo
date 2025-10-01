<template>
  <div class="card">
    <div class="card-content" :style="{ backgroundImage: `url(${getImageUrl(book?.image)})` }">
    </div>
    <div class="card-title">
      <h3>{{ book?.titre }}</h3>
    </div>
    <div class="card-separator">
      <button class="detail-button" @click="goToDetail">Détail</button>
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
  border-radius: 16px;
  box-shadow: 0 2px 12px 0 rgba(225, 29, 72, 0.12);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: #fff;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.card:hover {
  transform: scale(1.25);
  box-shadow: 0 8px 32px 0 rgba(225, 29, 72, 0.25);
}


.card-content {
  flex: 1;
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  background-color: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-title {
  padding: 12px 16px;
  background: #fff;
  border-top: 1px solid #e2e8f0;
  min-height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-title h3 {
  font-family: 'Roboto', sans-serif;
  font-size: 14px;
  font-weight: 600;
  color: #3b3434;
  text-align: center;
  margin: 0;
  line-height: 1.3;
}

.card-separator {
  height: 60px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.detail-button {
  background: #3b3434;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}

.detail-button:hover {
  background: #2a1f1f;
}
</style>