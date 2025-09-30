<template>
	<NuxtLink v-if="isAdmin" to="/Book-Management" class="management-btn">
		Gestion des livres
	</NuxtLink>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isAdmin = ref(false)

onMounted(async () => {
  const savedLoginState = localStorage.getItem('isLoggedIn')
  const savedUserId = localStorage.getItem('userId')
  if (savedLoginState === 'true' && savedUserId) {
    try {
      const user = await $fetch(`http://127.0.0.1:8000/api/users/${savedUserId}`)
      if (user && Array.isArray(user.statuses)) {
        isAdmin.value = user.statuses.some(
          s => s.statusState === 2 
        )
      }
    } catch (e) {
      isAdmin.value = false
    }
  }
})
</script>

<style scoped>
.management-btn {
	color: #3b3434;
	border: none;
	padding: 10px 24px;
	border-radius: 10px;
	font-family: 'Roboto', sans-serif;
	font-size: 18px;
	font-weight: 500;
	cursor: pointer;
	margin-left: 16px;
	transition: background 0.2s;
	text-decoration: none;
}
.management-btn:hover {
	background: #3b3434;
	color: #fff;
}
</style>
