<template>
  <div>
    <slot />
  </div>
</template>

<script setup>
import { ref, provide, onMounted, watch } from 'vue'

const currentUser = ref(null)

const updateCurrentUser = (user) => {
  currentUser.value = user
  window.dispatchEvent(new CustomEvent('user-status-changed'))
}

const checkSession = async () => {
  try {
    const headers = {
      'Content-Type': 'application/json'
    }
    
    const token = localStorage.getItem('auth_token')
    if (token) {
      headers['Authorization'] = `Bearer ${token}`
    }
    
    const response = await $fetch('http://localhost:8000/api/check-session', {
      method: 'GET',
      headers,
      credentials: 'include'
    })

    if (response.success && response.user) {
      currentUser.value = response.user
    } else {
      currentUser.value = null
    }
  } catch (error) {
    if (error.status === 401) {
      localStorage.removeItem('auth_token')
      currentUser.value = null
    } else if (error.status !== 401) {
      console.error('Erreur lors de la vérification de session:', error)
    }
    currentUser.value = null
  }
}

provide('currentUser', currentUser)
provide('updateCurrentUser', updateCurrentUser)

watch(currentUser, () => {
  window.dispatchEvent(new CustomEvent('user-status-changed'))
}, { immediate: false })

onMounted(() => {
  checkSession()
})
</script>