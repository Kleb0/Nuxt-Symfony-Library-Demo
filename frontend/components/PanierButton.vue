<template>
  <button 
    @click="goToCart" 
    class="panier-btn text-[#ede9d0] text-[28px] font-roboto no-underline py-3 px-6 rounded-xl transition-all duration-200 flex items-center gap-2 bg-transparent border-none cursor-pointer max-lg:text-2xl max-lg:py-2 max-lg:px-4 max-md:text-xl max-md:py-1 max-md:px-3 max-sm:text-lg" 
    v-if="isLoggedIn"
  >
    <div class="cart-icon-container relative inline-flex">
      <ShoppingCartIcon class="icon w-5 h-5" />
      <div v-if="totalItems > 0" class="cart-badge absolute -top-[20px] left-[120px] bg-red-600 text-white rounded-full min-w-[30px] h-[30px] flex items-center justify-center text-sm font-bold leading-none max-lg:left-[70px] max-lg:-top-[15px] max-lg:min-w-[25px] max-lg:h-[25px] max-lg:text-xs max-md:left-[50px] max-md:-top-[10px] max-md:min-w-[20px] max-md:h-[20px] max-md:text-xs max-sm:left-[120px]">
        + {{ totalItems }}
      </div>
    </div>
    Votre panier
  </button>
</template>

<script setup>
import { ref, inject, onMounted, onUnmounted } from 'vue'
import { ShoppingCartIcon } from '@heroicons/vue/24/outline'

const isLoggedIn = ref(false)
const totalItems = ref(0)
const currentUser = inject('currentUser', ref(null))

const loadCartCount = async () => {
  if (!currentUser.value?.id) {
    totalItems.value = 0
    return
  }
  
  try {
    const headers = { 'Content-Type': 'application/json' }
    const token = localStorage.getItem('auth_token')
    if (token) headers['Authorization'] = `Bearer ${token}`
    
    const cartItems = await $fetch('http://localhost:8000/api/cart/current-user', {
      method: 'GET',
      headers,
      credentials: 'include'
    })
    
    totalItems.value = Array.isArray(cartItems) 
      ? cartItems.reduce((total, item) => total + item.quantity, 0) 
      : 0
  } catch (error) {
    totalItems.value = 0
    if (error.status === 401) {
      localStorage.removeItem('auth_token')
      isLoggedIn.value = false
    }
  }
}

const updateUserStatus = () => {
  isLoggedIn.value = !!(currentUser.value?.id)
  if (isLoggedIn.value) {
    loadCartCount()
  } else {
    totalItems.value = 0
  }
}

const goToCart = () => {
  window.location.href = '/votre-panier'
}

onMounted(() => {
  updateUserStatus()
  window.addEventListener('cart-updated', loadCartCount)
  window.addEventListener('user-status-changed', updateUserStatus)
})

onUnmounted(() => {
  window.removeEventListener('cart-updated', loadCartCount)
  window.removeEventListener('user-status-changed', updateUserStatus)
})
</script>

<style scoped>
/* CSS personnalisé pour les effets avancés */

/* Police personnalisée */
.font-roboto {
  font-family: 'Roboto', sans-serif;
}

/* Effet hover personnalisé */
.panier-btn:hover {
  background: #3b3434;
  color: #fff;
}
</style>