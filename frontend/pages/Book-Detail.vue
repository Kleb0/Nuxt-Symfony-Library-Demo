<template>
  <div class="Page">
    <div class="Book-card">
      <div class="Image">
        <img v-if="book.image" :src="book.image" :alt="book.titre" />
        <div v-else class="Image-placeholder">Aucune image</div>
      </div>
      <div class="Details">
        <div class="Details-content">
          <p class="meta"><strong>Auteur:</strong> {{ book.auteur || '—' }}</p>
          <p class="meta"><strong>Catégorie:</strong> {{ book.categorie || '—' }}</p>
          <h2 class="title">Résumé</h2>
          <p class="resume">{{ book.resume || '—' }}</p>
          <div class="price"><strong>Prix:</strong> <span>{{ book.unitPrice ? book.unitPrice + '€' : '—' }}</span></div>
          <button 
            v-if="isLoggedIn" 
            @click="addToCart" 
            class="add-to-cart-btn"
            :disabled="isAdding"
          >
            {{ isAdding ? 'Ajout...' : 'Ajouter au panier' }}
          </button>
          <div v-if="!isLoggedIn" class="login-required">
            Connectez-vous pour ajouter au panier
          </div>
        </div>
      </div>
    </div>
    <div class="BackWrapper">
      <NuxtLink to="/" class="button-accueil">← Retour à l'accueil</NuxtLink>
    </div>
  </div>
  
</template>

<script setup>
import { useRoute } from 'vue-router'
import { ref, onMounted, onUnmounted } from 'vue'

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

const checkLoginStatus = () => {
  const savedLoginState = localStorage.getItem('isLoggedIn')
  const savedUserId = localStorage.getItem('userId')
  isLoggedIn.value = savedLoginState === 'true' && !!savedUserId
}

const addToCart = async () => {
  if (!isLoggedIn.value || isAdding.value) return
  
  isAdding.value = true
  
  try {
    const userId = localStorage.getItem('userId')
    if (!userId) {
      isAdding.value = false
      return
    }

    const response = await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}/add`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        bookId: parseInt(book.id),
        quantity: 1
      })
    })


    setTimeout(() => {
      isAdding.value = false
    }, 500)
    
  } catch (error) {
    isAdding.value = false
  }
}

onMounted(() => {
  checkLoginStatus()
  
  const interval = setInterval(checkLoginStatus, 1000)
  
  onUnmounted(() => {
    clearInterval(interval)
  })
})
</script>

<style scoped>
.Page {
  min-height: 70vh;
  display: flex;
  flex-direction: column; 
  margin: 40px auto;
  align-items: center;
  justify-content: center;
  padding: 40px 16px;
  gap: 16px;
}

.Book-card 
{
  width: min(90vw, 850px);
  height: 1100px;
  background: #f0ece4;
  border-radius: 24px;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
  border: 2px solid rgba(255, 255, 255, 0.2);
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center; 
}

.Image {
  height: 700px;
  border-radius: 20px;
  width: 1000px;
  display: flex;
  position: absolute;
  justify-content: center;
}

.Image img {
  width: 45%;
  height: 100%;
  object-fit: cover; 
  border-radius: inherit;
}

.Image-placeholder {
  color: white;
  font-weight: 600;
}

.Details {
  height: 300px;
  width: 40%;
  position: absolute;
  display: flex;
  justify-content: center;
  padding: 10;
  margin-top: 750px; 
  border-radius: 20px;
}

.Details-content {
  width: 60%;
  height: 80%;
  max-width: none; 
  color: #063c2b;
  background: rgba(255,255,255,0.9);
  border-radius: 20px;
  padding: 12px 16px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  box-sizing: border-box;
  overflow-wrap: break-word;
  word-break: break-word;
  hyphens: auto;
}

.Details-content .title {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 8px 0 6px;
}

.Details-content .resume {
  line-height: 1.6;
}

.Details-content .price {
  margin-top: 12px;
  font-size: 1.1rem;
}

.add-to-cart-btn {
  background: #DEA54A;
  color: #3b3434;
  border: none;
  padding: 12px 24px;
  border-radius: 10px;
  font-weight: 600;
  font-family: 'Roboto', sans-serif;
  font-size: 1.0rem;
  cursor: pointer;
  margin-top: 250px;
  width: 100%;
  transition: background 0.2s, transform 0.1s;
}

.add-to-cart-btn:hover:not(:disabled) {
  background: #2a1f1f;
  color: #fff;
}

.add-to-cart-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.login-required {
  margin-top: 250px;
  padding: 12px;
  background: #f3f4f6;
  border-radius: 8px;
  color: #ff0000;
  text-align: center;
  font-style: italic;
}

.BackWrapper {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 24px 0 40px;
}

.button-accueil {
  background: #1d4ed8; 
  color: #fff;
  padding: 10px 20px;
  border-radius: 10px;
  text-decoration: none;
  font-family: 'Roboto', sans-serif;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  transition: background 0.2s, color 0.2s;
}

.button-accueil:hover {
  background: #3b3434;
  color: #fff;
}

@media (max-width: 1200px) {
  .Book-card {
    height: auto;
    padding-bottom: 24px;
  }
  .Image {
    position: static;
    width: 90%;
    height: 420px;
  }
  .Image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .Details {
    position: static;
    width: 95%;
    height: auto;
    margin-top: 16px;
  }
  .Details-content {
    width: 100%;

  }
}
</style>
