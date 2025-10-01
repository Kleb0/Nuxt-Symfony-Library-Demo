<template>
  <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Créer une nouvelle catégorie</h3>
        <button
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <form @submit.prevent="createCategory" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
          <input
            v-model="createCategoryForm.name"
            type="text"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
          <textarea
            v-model="createCategoryForm.description"
            rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
          ></textarea>
        </div>
        
        <div class="flex justify-end space-x-3 pt-4">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors"
          >
            Créer
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://127.0.0.1:8000/api'

// Définition des props
interface Props {
  show: boolean
}

const props = defineProps<Props>()

// Définition des événements émis
const emit = defineEmits<{
  close: []
  categoryCreated: []
}>()

// Formulaire de création de catégorie
const createCategoryForm = ref({
  name: '',
  description: ''
})

// Réinitialiser le formulaire quand le modal s'ouvre
watch(() => props.show, (newShow) => {
  if (newShow) {
    createCategoryForm.value = {
      name: '',
      description: ''
    }
  }
})

// Fonction de création de catégorie
const createCategory = async () => {
  try {
    const createData = {
      name: createCategoryForm.value.name,
      description: createCategoryForm.value.description || null
    }
    
    console.log('Données à envoyer pour la catégorie:', createData)
    console.log('URL:', `${baseURL}/categories`)
    console.log('Content-Type qui sera envoyé: application/ld+json')
    
    const response = await fetch(`${baseURL}/categories`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json'
      },
      body: JSON.stringify(createData)
    })
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    // Émettre l'événement de création réussie
    emit('categoryCreated')
    emit('close')
    
    alert('Catégorie créée avec succès !')
  } catch (error: any) {
    console.error('Erreur lors de la création de la catégorie:', error)
    console.error('Erreur complète:', error)
    
    let errorMessage = 'Erreur lors de la création de la catégorie'
    if (error.message) {
      errorMessage = error.message
    }
    
    alert(errorMessage)
  }
}
</script>
