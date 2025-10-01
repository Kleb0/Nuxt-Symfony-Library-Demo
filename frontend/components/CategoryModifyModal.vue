<template>
  <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier la catégorie</h3>
        
        <form @submit.prevent="saveCategory" class="space-y-4">
          <!-- Nom -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
            <input
              v-model="editCategoryForm.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="editCategoryForm.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <!-- Boutons -->
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
import type { Category } from '~/types/index'

// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://127.0.0.1:8000/api'

// Définition des props
interface Props {
  show: boolean
  category: Category | null
}

const props = defineProps<Props>()

// Définition des événements émis
const emit = defineEmits<{
  close: []
  categoryUpdated: []
}>()

// Formulaire de modification de catégorie
const editCategoryForm = ref({
  name: '',
  description: ''
})

// Watcher pour remplir le formulaire quand une catégorie est sélectionnée
watch(() => props.category, (newCategory) => {
  if (newCategory) {
    editCategoryForm.value = {
      name: newCategory.name,
      description: newCategory.description || ''
    }
  }
}, { immediate: true })

// Fonction pour sauvegarder les modifications de catégorie
const saveCategory = async () => {
  if (!props.category) return
  
  try {
    const updateData = {
      name: editCategoryForm.value.name,
      description: editCategoryForm.value.description || null
    }
    
    await $fetch(`${baseURL}/categories/${props.category.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json'
      },
      body: JSON.stringify(updateData)
    })
    
    // Émettre l'événement de modification réussie
    emit('categoryUpdated')
    emit('close')
    
    alert('Catégorie modifiée avec succès !')
  } catch (error: any) {
    console.error('Erreur lors de la modification de la catégorie:', error)
    
    let errorMessage = 'Erreur lors de la modification de la catégorie'
    if (error.data && error.data.detail) {
      errorMessage = error.data.detail
    } else if (error.data && error.data.error) {
      errorMessage = error.data.error
    }
    
    alert(errorMessage)
  }
}
</script>
