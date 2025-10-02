<template>
  <div class="w-full max-w-[1000px] min-h-[1000px] flex flex-col my-5 mx-auto items-center px-4 py-10 gap-4">
   
    <!-- Carte principale du livre -->
    <div class="w-full max-w-[850px] min-h-[800px] bg-[#e7e1d5] rounded-3xl border-2 border-white/20 p-5 flex flex-col items-center lg:pb-6 book-card">
     
      <!-- Container de l'image -->
      <div class="h-[400px] rounded-[20px] w-[480px] flex justify-center lg:w-[450px] lg:h-[350px] image-container">
        <img v-if="book.image" :src="book.image" :alt="book.titre" class="w-full h-full object-contain rounded-[inherit] book-image" />
        <div v-else class="text-white font-semibold flex items-center justify-center image-placeholder">Aucune image</div>
      </div>
      
      <!-- Container des détails -->
      <div class="w-[80%] flex justify-center p-2.5 mt-20 rounded-[20px] lg:w-[95%] lg:mt-20 flex-1">
        <div class="w-[80%] text-[#063c2b] bg-white/90 rounded-[20px] px-4 py-4 box-border break-words lg:w-full details-content overflow-y-auto">
          <p class="mb-2 book-meta"><strong>Auteur:</strong> {{ book.auteur || '—' }}</p>
          <p class="mb-2 book-meta"><strong>Catégorie:</strong> {{ book.categorie || '—' }}</p>
          <h2 class="text-xl font-bold my-2 mb-1.5 book-title">Résumé</h2>
          <p class="leading-relaxed book-resume mb-4">{{ book.resume || '—' }}</p>
          <div class="mt-3 text-lg book-price mb-4"><strong>Prix:</strong> <span>{{ book.unitPrice ? book.unitPrice + '€' : '—' }}</span></div>
          <button 
            v-if="isLoggedIn" 
            @click="addToCart" 
            class="border-none px-6 py-3 rounded-[20px] font-semibold text-base cursor-pointer mt-4 w-full transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed add-to-cart-btn"
            :disabled="isAdding"
          >
            {{ isAdding ? 'Ajout...' : 'Ajouter au panier' }}
          </button>
          <div v-if="!isLoggedIn" class="mt-4 p-3 bg-gray-100 rounded-lg text-red-500 text-center italic login-required">
            Connectez-vous pour ajouter au panier
          </div>
        </div>
      </div>
    </div>
    
    <!-- Bouton de retour -->
    <div class="w-full flex items-center justify-center my-6 mb-10">
      <NuxtLink to="/" class="bg-blue-700 text-white px-5 py-2.5 rounded-[10px] no-underline transition-all duration-200 back-button">← Retour à l'accueil</NuxtLink>
    </div>
  </div>
  
</template>

<script setup>
import { useRoute } from 'vue-router'
import { ref, inject, watch } from 'vue'

const route = useRoute()
const q = route.query || {}
const book = {
  id: q.id || '',
  titre: q.titre || '',
  auteur: q.auteur || '',
  categorie: q.categorie || '',
  image: q.image || '',
  resume: q.resume || '',
  unitPrice: q.unitPrice || ''
}

const isLoggedIn = ref(false)
const isAdding = ref(false)
const currentUser = inject('currentUser', ref(null))

watch(() => currentUser.value, (newUser) => {
  isLoggedIn.value = !!newUser
}, { immediate: true })

const addToCart = async () => {
  if (!isLoggedIn.value || isAdding.value || !currentUser.value) return
  
  isAdding.value = true
  
  try {
    // Préparer les headers avec le token si disponible
    const headers = {
      'Content-Type': 'application/json'
    }
    
    const token = localStorage.getItem('auth_token')
    if (token) {
      headers['Authorization'] = `Bearer ${token}`
    }
    
    await $fetch('http://localhost:8000/api/cart/current-user/add', {
      method: 'POST',
      headers,
      credentials: 'include',
      body: JSON.stringify({
        bookId: parseInt(book.id),
        quantity: 1
      })
    })

    setTimeout(() => {
      isAdding.value = false
    }, 500)
    
    // Informer les autres composants que le panier a été modifié
    window.dispatchEvent(new CustomEvent('cart-updated'))
    
  } catch (error) {
    console.error('Erreur lors de l\'ajout au panier:', error)
    // Supprimer le token invalide si erreur 401
    if (error.status === 401) {
      localStorage.removeItem('auth_token')
    }
    isAdding.value = false
  }
}
</script>

<style scoped>
/* 🎨 Approche sobre et professionnelle */

/* Carte principale - animation d'entrée douce */
.book-card {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  animation: slideInUp 0.6s ease-out;
}

.book-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

/* Container image - effet subtil */
.image-container {
  transition: transform 0.3s ease;
  overflow: hidden;
}

.book-image {
  transition: transform 0.3s ease;
}

.image-container:hover .book-image {
  transform: scale(1.05);
}


.image-placeholder {
  background: linear-gradient(135deg, #6b7280, #9ca3af);
}

.details-content {
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  animation: slideInUp 0.8s ease-out 0.2s both;
}

.book-meta {
  transition: color 0.2s ease;
}

.book-meta:hover {
  color: #3b82f6;
}


.book-title {
  background: linear-gradient(135deg, #063c2b 0%, #3b82f6 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}


.book-resume {
  text-align: justify;
  line-height: 1.7;
  animation: fadeIn 1s ease-out 0.4s both;
}


.add-to-cart-btn {
  background:  #f59e0b;
  color: #1f2937;
  font-family: 'Roboto', sans-serif;
  font-weight: 600;

}

.add-to-cart-btn:hover:not(:disabled) {
  background: #3b3434;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(31, 41, 55, 0.4);
}

.add-to-cart-btn:active:not(:disabled) {
  transform: translateY(0);
}

.login-required 
{
  background: #dbcccc;
  border-radius: 20px;
}

.back-button {
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
  background: #3b82f6;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);

}

.back-button:hover {
  background: #3b3434;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(59, 52, 52, 0.4);
}


@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}



</style>


