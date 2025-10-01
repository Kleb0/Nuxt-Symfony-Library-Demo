<template>
  <div class="first-step">
    <h1 class="title">Notre collection de livres ! </h1>


    <div class="search-fields">
      <input
        v-model="search"
        type="text"
        placeholder="Rechercher un livre..."
        class="search-bar"
      />
      <input
        v-model="searchAuthor"
        type="text"
        placeholder="Rechercher un auteur..."
        class="search-bar"
      />
      <input
        v-model="searchCategory"
        type="text"
        placeholder="Rechercher une catégorie..."
        class="search-bar"
      />
    </div>

    <div class="book-grid">
      <template v-for="(group, i) in Math.ceil(filteredBooks.length / 3)" :key="'group-' + i">
        <div class="grid">
          <Card v-for="book in filteredBooks.slice(i * 3, i * 3 + 3)" :key="book.id" :book="book" class="blue-square" />
        </div>
      </template>
      <div class="cart"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Card from '@/components/Card.vue'

const books = ref([])
const search = ref('')
const searchAuthor = ref('')
const searchCategory = ref('')

const filteredBooks = computed(() => {
  return books.value.filter(book => {
    const matchTitle = search.value
      ? (book.titre && book.titre.toLowerCase().includes(search.value.toLowerCase()))
      : true
    
    const matchAuthor = searchAuthor.value
      ? (book.authors && book.authors.some(author => 
          (author.firstName && author.firstName.toLowerCase().includes(searchAuthor.value.toLowerCase())) ||
          (author.lastName && author.lastName.toLowerCase().includes(searchAuthor.value.toLowerCase())) ||
          (author.fullName && author.fullName.toLowerCase().includes(searchAuthor.value.toLowerCase()))
        ))
      : true
    
    const matchCategory = searchCategory.value
      ? (book.categories && book.categories.some(category => 
          category.name && category.name.toLowerCase().includes(searchCategory.value.toLowerCase())
        ))
      : true
    
    return matchTitle && matchAuthor && matchCategory
  })
})

const fetchBooks = async () => {
  try {
    const response = await $fetch('http://127.0.0.1:8000/api/books')
    console.log('API Response:', response)
    
    if (response && response.member) {
      books.value = response.member
    } else if (response && response['hydra:member']) {
      books.value = response['hydra:member']
    } else if (Array.isArray(response)) {
      books.value = response
    } else {
      books.value = []
    }
    
    books.value = books.value.filter(book => typeof book === 'object' && book !== null)
    console.log('Books loaded:', books.value.length) // Pour débugger
  } catch (error) {
    console.error('Erreur lors du chargement des livres:', error)
    books.value = []
  }
}

onMounted(() => {
  fetchBooks()
})
</script>

<style scoped>

.title {
  text-align: center;
  font-size: 2.5rem;
  margin-top: 20px;
  font-family: 'Roboto', sans-serif;
  color: #333;
}

.book-grid {
  display: grid;
}

.grid {
  width: 100%;
  height: 600px;
  border-radius: 12px;
  margin: 40px 0;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 40px;
}


.blue-square {
  width: 300px;
  height: 500px;
  background: #2563eb;
  border-radius: 16px;
  margin: 50px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.10);
  padding: 0;
}

.search-bar {
  width: 400px;
  margin: 24px auto 0 auto;
  display: block;
  padding: 12px 18px;
  font-size: 1.1rem;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-family: 'Roboto', sans-serif;
}

.search-fields {
  display: flex;
  flex-direction: row;
  gap: 24px;
  justify-content: center;
  margin-bottom: 16px;
}


</style>
