<template>
	<NuxtLink v-if="isAdmin" to="/Book-Management" class="management-btn">
		Gestion des livres
	</NuxtLink>
</template>

<script setup>
import { ref, watch, inject } from 'vue'

const isAdmin = ref(false)

const currentUser = inject('currentUser', ref(null))

watch(() => currentUser.value, (newUser) => {
  if (newUser && Array.isArray(newUser.statuses)) {
    isAdmin.value = newUser.statuses.some(s => s.status_state === 2)
  } else {
    isAdmin.value = false
  }
}, { immediate: true })
</script>

<style scoped>
.management-btn {
	color: #ede9d0;
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
