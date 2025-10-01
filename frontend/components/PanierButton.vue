<template>
  <button @click="goToCart" class="panier-btn" v-if="isLoggedIn">
    <div class="cart-icon-container">
      <ShoppingCartIcon class="icon" />
      <div v-if="totalItems > 0" class="cart-badge">
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
.panier-btn {
  color: #ede9d0;
  font-size: 28px;
  font-family: 'Roboto', sans-serif;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 12px;
  transition: background 0.2s, color 0.2s;
  display: flex;
  align-items: center;
  gap: 8px;
  background: none;
  border: none;
  cursor: pointer;
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