<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div v-if="loading" class="text-center">
      <div class="loading-spinner"></div>
      <p>Chargement...</p>
    </div>
    
    <div v-else-if="error" class="text-center text-red-500">
      <p>{{ error }}</p>
    </div>
    
    <div v-else class="profile-container">
      <div class="profile-header">
        <div class="profile-image-container">
          <img 
            v-if="user.imageProfil && user.imageProfil.trim() !== ''" 
            :src="getImageSrc(user.imageProfil)" 
            alt="Photo de profil" 
            class="profile-image"            
          />
          <div v-else class="profile-image-placeholder">
            {{ user.prenom?.[0] }}{{ user.nom?.[0] }}
          </div>
        </div>
        <h1 class="profile-name">{{ user.prenom }} {{ user.nom }}</h1>
        <p class="profile-pseudo">@{{ user.pseudo }}</p>
      </div>

      <div class="profile-details">
        <div class="detail-item">
          <label class="detail-label">Email</label>
          <span class="detail-value">{{ user.mail }}</span>
        </div>
        
        <div class="detail-item">
          <label class="detail-label">Téléphone</label>
          <span class="detail-value">{{ user.telephone || 'Non renseigné' }}</span>
        </div>
        
        <div class="detail-item">
          <label class="detail-label">Adresse</label>
          <span class="detail-value">{{ user.adresse || 'Non renseignée' }}</span>
        </div>
        
        <div class="detail-item">
          <label class="detail-label">Mot de passe</label>
          <button class="change-password-btn">Modifier le mot de passe</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface User {
  id?: number
  nom: string
  prenom: string
  pseudo: string
  mail: string
  telephone?: string
  adresse?: string
  imageProfil?: string
}

const user = ref<User>({
  nom: '',
  prenom: '',
  pseudo: '',
  mail: '',
  telephone: '',
  adresse: '',
  imageProfil: undefined
})

const loading = ref(true)
const error = ref('')

function getImageSrc(imageProfil: string) {
  if (!imageProfil) return ''
  
  if (imageProfil.startsWith('data:')) {
    return imageProfil
  }
  
  return imageProfil
}


onMounted(async () => {
  try {
    const userId = localStorage.getItem('userId')
    
    if (!userId) {
      error.value = 'Utilisateur non connecté'
      loading.value = false
      return
    }

    const response = await $fetch(`http://localhost:8000/api/users/${userId}`, {
      headers: {
        'Accept': 'application/ld+json',
      }
    }) as any

    if (response) {
      user.value = {
        id: response.id,
        nom: response.nom,
        prenom: response.prenom,
        pseudo: response.pseudo,
        mail: response.mail,
        telephone: response.telephone,
        adresse: response.adresse,
        imageProfil: response.imageProfil
      }
      
    }
  } catch (err) {
    console.error('Erreur lors de la récupération des données utilisateur:', err)
    error.value = 'Erreur lors du chargement des données'
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.profile-container {
  background: white;
  border-radius: 24px;
  padding: 32px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  width: 100%;
  text-align: center;
}

.profile-header {
  margin-bottom: 32px;
}

.profile-image-container {
  margin: 0 auto 16px;
  width: 120px;
  height: 120px;
}

.profile-image {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #e5e7eb;
}

.profile-image-placeholder {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: #6b7280;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  font-weight: bold;
  border: 4px solid #e5e7eb;
}

.profile-name {
  font-size: 28px;
  font-weight: bold;
  color: #1f2937;
  margin: 0 0 8px 0;
}

.profile-pseudo {
  font-size: 16px;
  color: #6b7280;
  margin: 0;
}

.profile-details {
  text-align: left;
}

.detail-item {
  margin-bottom: 20px;
  padding: 16px;
  background: #f9fafb;
  border-radius: 12px;
}

.detail-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.detail-value {
  font-size: 16px;
  color: #1f2937;
}

.change-password-btn {
  background: #dc2626;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}

.change-password-btn:hover {
  background: #b91c1c;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top: 4px solid #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 16px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>