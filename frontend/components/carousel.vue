<template>
  <div class="carousel-container bg-[#ede9d0]">
    <div v-if="loading" class="loading-message text-center text-xl text-gray-600">Chargement des livres...</div>
    <div v-else-if="allBooks.length === 0" class="no-books-message text-center text-xl text-gray-600">Aucun livre trouvé</div>
    <div v-else class="carousel-wrapper flex items-center justify-center gap-10 w-full relative">
      <button 
        @click="previousCards" 
        @mouseenter="startContinuousScrollRight"
        @mouseleave="stopContinuousScroll"
        class="nav-arrow nav-arrow-left border-none rounded-full w-20 h-20 flex items-center justify-center cursor-pointer transition-all duration-300 text-white shadow-lg opacity-0 flex-shrink-0"
      >
        <Icon name="heroicons:chevron-left" size="40" />
      </button>

      <div class="carousel-viewport w-[1580px] h-[600px] overflow-hidden relative">
        <div class="carousel-track flex flex-row gap-20 items-center justify-start h-full py-[75px]" :style="{ transform: `translateX(${scrollOffset}px)` }">
          <Card 
            v-for="(book, index) in infiniteBooks" 
            :key="`${book.id}-${index}`" 
            :book="book" 
            class="carousel-card flex-shrink-0 w-[220px] relative z-[1] transition-all duration-300" 
          />
        </div>
      </div>

      <button 
        @click="nextCards" 
        @mouseenter="startContinuousScrollLeft"
        @mouseleave="stopContinuousScroll"
        class="nav-arrow nav-arrow-right border-none rounded-full w-20 h-20 flex items-center justify-center cursor-pointer transition-all duration-300 text-white shadow-lg opacity-0 flex-shrink-0"
      >
        <Icon name="heroicons:chevron-right" size="40" />
      </button>
    </div>
  </div>
</template>

<script setup>
import Card from './Card.vue';

const allBooks = ref([]);
const loading = ref(true);
const currentIndex = ref(0);
const scrollOffset = ref(0);
const continuousScrollInterval = ref(null);
const cardWidth = 300; 

const infiniteBooks = computed(() => {
  if (allBooks.value.length === 0) return [];
  const tripleBooks = [...allBooks.value, ...allBooks.value, ...allBooks.value];
  return tripleBooks;
});

const maxIndex = computed(() => {
  return Math.max(0, allBooks.value.length - 5);
});

const visibleBooks = computed(() => {
  return allBooks.value.slice(currentIndex.value, currentIndex.value + 5);
});

const fetchBooks = async () => {
  try {
    const response = await $fetch('http://localhost:8000/api/books');
    
    if (response && response['hydra:member']) {
      allBooks.value = response['hydra:member'];
    } else if (response && response.member) {
      allBooks.value = response.member;
    } else if (Array.isArray(response)) {
      allBooks.value = response;
    } else {
      allBooks.value = [];
    }
    
    allBooks.value = allBooks.value.filter(book => typeof book === 'object' && book !== null);
    
    scrollOffset.value = -(allBooks.value.length * cardWidth);
    

  } catch (error) {
    allBooks.value = [];
  } finally {
    loading.value = false;
  }
};

const nextCards = () => {
  if (currentIndex.value < maxIndex.value) {
    currentIndex.value++;
  }
};

const previousCards = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  }
};

const startContinuousScrollRight = () => {
  stopContinuousScroll();
  continuousScrollInterval.value = setInterval(() => {
    scrollOffset.value -= 2; 

    if (scrollOffset.value <= -(allBooks.value.length * 2 * cardWidth)) {
      scrollOffset.value = -(allBooks.value.length * cardWidth);
    }
  }, 16); 
};


const startContinuousScrollLeft = () => {
  stopContinuousScroll();
  continuousScrollInterval.value = setInterval(() => {
    scrollOffset.value += 2; 
    
    if (scrollOffset.value >= 0) {
      scrollOffset.value = -(allBooks.value.length * cardWidth);
    }
  }, 16);
};


const stopContinuousScroll = () => {
  if (continuousScrollInterval.value) {
    clearInterval(continuousScrollInterval.value);
    continuousScrollInterval.value = null;
  }
};

onMounted(() => {
  fetchBooks();
});

onUnmounted(() => {
  stopContinuousScroll();
});
</script>

<style scoped>


.carousel-viewport {
  mask: linear-gradient(
    to right, 
    transparent 0%, 
    black 10%, 
    black 90%, 
    transparent 100%
  );
  -webkit-mask: linear-gradient(
    to right, 
    transparent 0%, 
    black 10%, 
    black 90%, 
    transparent 100%
  );
}


.carousel-track {
  transition: transform 0.1s linear;
  will-change: transform;
}

.nav-arrow {
  background: rgba(222, 165, 74, 0.9);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.nav-arrow:hover {
  opacity: 1;
}

.nav-arrow:hover:not(:disabled) {
  background: rgba(222, 165, 74, 1);
  transform: scale(1.1);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.nav-arrow:disabled {
  background: rgba(170, 170, 170, 0.5);
  cursor: not-allowed;
  color: rgba(255, 255, 255, 0.5);
}

.nav-arrow:disabled:hover {
  transform: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.carousel-card:hover {
  z-index: 10;
  transform: scale(1.20);
}
</style>
