<template>
  <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Créer un nouveau livre</h3>
        <button
          @click="closeModal"
          type="button"
          class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <form @submit.prevent="createBook" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Titre -->
          <div>
            <label for="createTitre" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
            <input
              id="createTitre"
              v-model="createBookForm.titre"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- ISBN -->
          <div>
            <label for="createIsbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
            <input
              id="createIsbn"
              v-model="createBookForm.isbn"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- Prix -->
          <div>
            <label for="createPrix" class="block text-sm font-medium text-gray-700 mb-1">Prix *</label>
            <input
              id="createPrix"
              v-model="createBookForm.prix"
              type="number"
              step="0.01"
              min="0"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- Stock -->
          <div>
            <label for="createStock" class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
            <input
              id="createStock"
              v-model="createBookForm.stock"
              type="number"
              min="0"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- Date de publication -->
          <div>
            <label for="createDatePublication" class="block text-sm font-medium text-gray-700 mb-1">Date de publication</label>
            <input
              id="createDatePublication"
              v-model="createBookForm.datePublication"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- Nombre de pages -->
          <div>
            <label for="createNombrePages" class="block text-sm font-medium text-gray-700 mb-1">Nombre de pages</label>
            <input
              id="createNombrePages"
              v-model="createBookForm.nombrePages"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </div>
        
        <!-- Description -->
        <div>
          <label for="createDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea
            id="createDescription"
            v-model="createBookForm.description"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          ></textarea>
        </div>
        
        <!-- Auteurs -->
        <div>
          <label for="createAuthors" class="block text-sm font-medium text-gray-700 mb-1">Auteurs *</label>
          <select
            id="createAuthors"
            v-model="createBookForm.authors"
            multiple
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 h-24"
          >
            <option v-for="author in authors" :key="author.id" :value="author.id">
              {{ author.fullName }}
            </option>
          </select>
          <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs auteurs</p>
        </div>
        
        <!-- Catégories -->
        <div>
          <label for="createCategories" class="block text-sm font-medium text-gray-700 mb-1">Catégories *</label>
          <select
            id="createCategories"
            v-model="createBookForm.categories"
            multiple
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 h-24"
          >
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs catégories</p>
        </div>
        
        <!-- Image -->
        <div>
          <label for="createImage" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
          <input
            id="createImage"
            @change="handleImageSelect"
            type="file"
            accept="image/*"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <p class="text-xs text-gray-500 mt-1">Formats acceptés : JPG, PNG, GIF (max 5MB)</p>
        </div>
        
        <!-- Boutons -->
        <div class="flex justify-end space-x-3 pt-4">
          <button
            @click="closeModal"
            type="button"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors duration-200"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors duration-200"
          >
            Créer le livre
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Book, Author, Category } from '~/types/index'

interface Props {
  show: boolean
  authors: Author[]
  categories: Category[]
}

interface Emits {
  (e: 'close'): void
  (e: 'book-created'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://127.0.0.1:8000/api'

// Formulaire de création
const createBookForm = ref({
  titre: '',
  description: '',
  prix: '',
  stock: 0,
  isbn: '',
  datePublication: '' as string | undefined,
  nombrePages: 0,
  authors: [] as number[],
  categories: [] as number[]
})

// Fichier image sélectionné
const createBookImageFile = ref<File | null>(null)

// Fonction pour fermer le modal
const closeModal = () => {
  // Réinitialiser le formulaire
  createBookForm.value = {
    titre: '',
    description: '',
    prix: '',
    stock: 0,
    isbn: '',
    datePublication: '',
    nombrePages: 0,
    authors: [],
    categories: []
  }
  createBookImageFile.value = null
  emit('close')
}

// Fonction pour gérer la sélection d'image
const handleImageSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (!file) {
    createBookImageFile.value = null
    return
  }
  
  // Vérifier le type de fichier
  if (!file.type.startsWith('image/')) {
    alert('Veuillez sélectionner un fichier image valide')
    target.value = ''
    createBookImageFile.value = null
    return
  }
  
  // Vérifier la taille du fichier (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Le fichier est trop volumineux. Taille maximale : 5MB')
    target.value = ''
    createBookImageFile.value = null
    return
  }
  
  createBookImageFile.value = file
}

// Fonction pour créer un livre
const createBook = async () => {
  try {
    const createData = {
      titre: createBookForm.value.titre,
      description: createBookForm.value.description || null,
      prix: String(createBookForm.value.prix),
      stock: createBookForm.value.stock,
      isbn: createBookForm.value.isbn || null,
      datePublication: createBookForm.value.datePublication || null,
      nombrePages: createBookForm.value.nombrePages || null,
      authors: createBookForm.value.authors.map(id => `/api/authors/${id}`),
      categories: createBookForm.value.categories.map(id => `/api/categories/${id}`)
    }
    
    console.log('Données à envoyer pour le livre:', createData)
    console.log('Authors sélectionnés:', createBookForm.value.authors)
    console.log('Categories sélectionnées:', createBookForm.value.categories)
    
    const response = await fetch(`${baseURL}/books`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json'
      },
      body: JSON.stringify(createData)
    })
    
    console.log('Status de la réponse:', response.status)
    
    if (!response.ok) {
      const errorText = await response.text()
      console.error('Erreur détaillée du serveur:', errorText)
      throw new Error(`HTTP error! status: ${response.status} - ${errorText}`)
    }
    
    const bookData = await response.json()
    
    // Si une image a été sélectionnée, l'uploader
    if (createBookImageFile.value && bookData.id) {
      try {
        const formData = new FormData()
        formData.append('image', createBookImageFile.value)
        
        await $fetch(`${baseURL}/books/${bookData.id}/upload-image`, {
          method: 'POST',
          body: formData
        })
      } catch (imageError: any) {
        console.error('Erreur lors de l\'upload de l\'image:', imageError)
        // Ne pas bloquer la création du livre si l'image échoue
      }
    }
    
    // Émettre l'événement de création réussie
    emit('book-created')
    closeModal()
    
    alert('Livre créé avec succès !')
  } catch (error: any) {
    console.error('Erreur lors de la création du livre:', error)
    
    let errorMessage = 'Erreur lors de la création du livre'
    if (error.message) {
      errorMessage = error.message
    }
    
    alert(errorMessage)
  }
}
</script>
