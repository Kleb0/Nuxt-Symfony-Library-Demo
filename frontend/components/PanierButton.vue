<template>
  <NuxtLink to="/votre-panier" class="panier-btn" v-if="isLoggedIn">
    <div class="cart-icon-container">
      <ShoppingCartIcon class="icon" />
      <div v-if="totalItems > 0" class="cart-badge">
        + {{ totalItems }}
      </div>
    </div>
    Votre panier
  </NuxtLink>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { ShoppingCartIcon } from '@heroicons/vue/24/outline'

const isLoggedIn = ref(false)
const totalItems = ref(0)

const loadCartCount = async () => {
  try {
    const userId = localStorage.getItem('userId')
    if (!userId) {
      totalItems.value = 0
      return
    }
    
    const response = await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}`)
    const cartItems = response || []
    
    totalItems.value = cartItems.reduce((total, item) => total + item.quantity, 0)
  } catch (error) {
    console.error('Erreur lors du chargement du compteur panier:', error)
    totalItems.value = 0
  }
}

const checkLoginState = async () => {
  const savedLoginState = localStorage.getItem('isLoggedIn')
  const savedUserId = localStorage.getItem('userId')
  const wasLoggedIn = isLoggedIn.value
  
  isLoggedIn.value = savedLoginState === 'true' && !!savedUserId
  
  if (isLoggedIn.value) {
    await loadCartCount()
  } else {
    totalItems.value = 0
  }
  
  if (wasLoggedIn !== isLoggedIn.value) {
    if (isLoggedIn.value) {
      await loadCartCount()
    } else {
      totalItems.value = 0
    }
  }
}

onMounted(async () => {
  await checkLoginState()
  
  window.addEventListener('storage', checkLoginState)
  
  const interval = setInterval(async () => {
    await checkLoginState()
    if (isLoggedIn.value) {
      await loadCartCount()
    }
  }, 2000)
  
  onUnmounted(() => {
    window.removeEventListener('storage', checkLoginState)
    clearInterval(interval)
  })
})
</script>

<style scoped>
.panier-btn {
  color: #3b3434;
  font-size: 24px;
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 12px;
  transition: background 0.2s, color 0.2s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.panier-btn:hover {
  background: #3b3434;
  color: #fff;
}

.cart-icon-container {
  position: relative;
  display: inline-flex;
}

.cart-badge {
  position: absolute;
  top: -30px;
  left: 150px;
  background-color: #dc2626;
  color: white;
  border-radius: 50%;
  min-width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: bold;
  line-height: 1;
}

.icon {
  width: 20px;
  height: 20px;
}

@media (max-width: 768px) {
  .panier-btn {
    font-size: 20px;
    padding: 8px 16px;
  }
}
</style>