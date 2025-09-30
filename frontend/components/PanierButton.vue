<template>
  <NuxtLink to="/votre-panier" class="panier-btn" v-if="isLoggedIn">
    <ShoppingCartIcon class="icon" />
    Votre panier
  </NuxtLink>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { ShoppingCartIcon } from '@heroicons/vue/24/outline'

const isLoggedIn = ref(false)

onMounted(() => {
  const savedLoginState = localStorage.getItem('isLoggedIn')
  const savedUserId = localStorage.getItem('userId')
  
  if (savedLoginState === 'true' && savedUserId) {
    isLoggedIn.value = true
  }
  
  const checkLoginState = () => {
    const savedLoginState = localStorage.getItem('isLoggedIn')
    const savedUserId = localStorage.getItem('userId')
    isLoggedIn.value = savedLoginState === 'true' && !!savedUserId
  }
  
  window.addEventListener('storage', checkLoginState)
  
  const interval = setInterval(checkLoginState, 1000)
  
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