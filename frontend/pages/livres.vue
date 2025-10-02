<template>
  <div class="first-step">
    <h1 class="title text-center text-4xl mt-5 font-roboto text-gray-800">Notre collection de livres !</h1>

    <div class="search-fields flex flex-row gap-6 justify-center mb-4 max-lg:flex-col max-lg:items-center max-lg:gap-4">
      <input
        v-model="search"
        type="text"
        placeholder="Rechercher un livre..."
        class="search-bar w-[400px] mx-auto block py-3 px-5 text-lg rounded-lg border border-gray-300 font-roboto max-lg:w-[90%] max-lg:max-w-[350px] max-md:text-base max-md:py-2 max-md:px-4"
      />
      <input
        v-model="searchAuthor"
        type="text"
        placeholder="Rechercher un auteur..."
        class="search-bar w-[400px] mx-auto block py-3 px-5 text-lg rounded-lg border border-gray-300 font-roboto max-lg:w-[90%] max-lg:max-w-[350px] max-md:text-base max-md:py-2 max-md:px-4"
      />
      <input
        v-model="searchCategory"
        type="text"
        placeholder="Rechercher une catégorie..."
        class="search-bar w-[400px] mx-auto block py-3 px-5 text-lg rounded-lg border border-gray-300 font-roboto max-lg:w-[90%] max-lg:max-w-[350px] max-md:text-base max-md:py-2 max-md:px-4"
      />
    </div>

    <div class="book-grid grid">
      <template v-for="(group, i) in Math.ceil(filteredBooks.length / 3)" :key="'group-' + i">
        <div class="grid-row w-full h-[600px] rounded-xl my-10 shadow-custom flex items-center justify-center gap-10 max-lg:flex-col max-lg:h-auto max-lg:py-8 max-lg:gap-6">
          <Card v-for="book in filteredBooks.slice(i * 3, i * 3 + 3)" :key="book.id" :book="book" class="blue-square w-[300px] h-[500px] bg-blue-600 rounded-2xl m-12 shadow-book p-0 max-lg:m-4 max-lg:w-[280px] max-lg:h-[480px] max-md:w-[260px] max-md:h-[460px]" />
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
    console.log('Books loaded:', books.value.length) 
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
/* CSS personnalisé pour les effets avancés */

/* Police personnalisée */
.font-roboto {
  font-family: 'Roboto', sans-serif;
}


.shadow-custom {
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

.shadow-book {
  box-shadow: 0 2px 8px rgba(0,0,0,0.10);
}
</style>
