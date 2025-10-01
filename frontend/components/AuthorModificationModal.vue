<template>
  <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier l'auteur</h3>
        
        <form @submit.prevent="saveAuthor" class="space-y-4">
          <!-- Prénom -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
            <input
              v-model="editAuthorForm.firstName"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Nom -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
            <input
              v-model="editAuthorForm.lastName"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Nationalité -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
            <input
              v-model="editAuthorForm.nationality"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Date de naissance -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
            <input
              v-model="editAuthorForm.birthDate"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Boutons -->
          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
            >
              Sauvegarder
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Author } from '~/types/index'

interface Props {
  show: boolean
  author: Author | null
}

interface Emits {
  (e: 'close'): void
  (e: 'author-updated'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://127.0.0.1:8000/api'

// Formulaire de modification
const editAuthorForm = ref({
  firstName: '',
  lastName: '',
  nationality: '',
  birthDate: '' as string | undefined
})

// Watcher pour remplir le formulaire quand l'auteur change
watch(() => props.author, (newAuthor) => {
  if (newAuthor) {
    // Séparer le fullName en firstName et lastName
    const names = newAuthor.fullName.trim().split(' ')
    const firstName = names[0] || ''
    const lastName = names.slice(1).join(' ') || ''
    
    editAuthorForm.value = {
      firstName: firstName,
      lastName: lastName,
      nationality: newAuthor.nationality || '',
      birthDate: (newAuthor.birthDate && typeof newAuthor.birthDate === 'string') ? newAuthor.birthDate.split('T')[0] : ''
    }
  }
}, { immediate: true })

// Fonction pour fermer le modal
const closeModal = () => {
  emit('close')
}

// Fonction pour sauvegarder les modifications d'auteur
const saveAuthor = async () => {
  if (!props.author) return
  
  try {
    const updateData = {
      firstName: editAuthorForm.value.firstName,
      lastName: editAuthorForm.value.lastName,
      nationality: editAuthorForm.value.nationality || null,
      birthDate: editAuthorForm.value.birthDate || null
    }
    
    console.log('Données à envoyer pour l\'auteur:', updateData)
    console.log('Auteur en cours de modification:', props.author)
    
    const response = await $fetch(`${baseURL}/authors/${props.author.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json'
      },
      body: JSON.stringify(updateData)
    })
    
    console.log('Réponse de l\'API pour l\'auteur:', response)
    
    // Émettre l'événement de mise à jour réussie
    emit('author-updated')
    closeModal()
    
    alert('Auteur modifié avec succès !')
  } catch (error: any) {
    console.error('Erreur lors de la modification de l\'auteur:', error)
    console.error('Détails de l\'erreur:', error.data)
    
    let errorMessage = 'Erreur lors de la modification de l\'auteur'
    if (error.data && error.data.detail) {
      errorMessage = error.data.detail
    } else if (error.data && error.data.error) {
      errorMessage = error.data.error
    }
    
    alert(errorMessage)
  }
}
</script>
