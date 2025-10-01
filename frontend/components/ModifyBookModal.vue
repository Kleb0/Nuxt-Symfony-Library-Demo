<template>
  <div v-if="show" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le livre</h3>
        
        <form @submit.prevent="saveBook" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Titre -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
              <input
                v-model="editForm.titre"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Prix -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Prix *</label>
              <input
                v-model="editForm.prix"
                type="number"
                step="0.01"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Stock -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
              <input
                v-model="editForm.stock"
                type="number"
                required
                min="0"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- ISBN -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
              <input
                v-model="editForm.isbn"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Date de publication -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date de publication</label>
              <input
                v-model="editForm.datePublication"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Nombre de pages -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de pages</label>
              <input
                v-model="editForm.nombrePages"
                type="number"
                min="0"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="editForm.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            ></textarea>
          </div>

          <!-- Auteurs -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Auteurs</label>
            <select
              v-model="editForm.authors"
              multiple
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[100px]"
            >
              <option 
                v-for="author in authors" 
                :key="author.id" 
                :value="author.id"
              >
                {{ author.fullName }}
              </option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs auteurs</p>
          </div>

          <!-- Catégories -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catégories</label>
            <select
              v-model="editForm.categories"
              multiple
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[100px]"
            >
              <option 
                v-for="category in categories" 
                :key="category.id" 
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs catégories</p>
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
import type { Book, Author, Category } from '~/types/index'

interface Props {
  show: boolean
  book: Book | null
  authors: Author[]
  categories: Category[]
}

interface Emits {
  (e: 'close'): void
  (e: 'book-updated'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://127.0.0.1:8000/api'

// Formulaire de modification
const editForm = ref({
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

// Watcher pour remplir le formulaire quand le livre change
watch(() => props.book, (newBook) => {
  if (newBook) {
    editForm.value = {
      titre: newBook.titre,
      description: newBook.description || '',
      prix: newBook.prix,
      stock: newBook.stock,
      isbn: newBook.isbn || '',
      datePublication: (newBook.datePublication && typeof newBook.datePublication === 'string') ? newBook.datePublication.split('T')[0] : '',
      nombrePages: newBook.nombrePages || 0,
      authors: newBook.authors.map(author => author.id),
      categories: newBook.categories.map(category => category.id)
    }
  }
}, { immediate: true })

// Fonction pour fermer le modal
const closeModal = () => {
  emit('close')
}

// Fonction pour sauvegarder les modifications
const saveBook = async () => {
  if (!props.book) return
  
  try {
    const updateData = {
      titre: editForm.value.titre,
      description: editForm.value.description,
      prix: String(editForm.value.prix),
      stock: editForm.value.stock,
      isbn: editForm.value.isbn,
      datePublication: editForm.value.datePublication || null,
      nombrePages: editForm.value.nombrePages || null,
      authors: editForm.value.authors.map(id => `/api/authors/${id}`),
      categories: editForm.value.categories.map(id => `/api/categories/${id}`),
      image: props.book.image 
    }
    
    await $fetch(`${baseURL}/books/${props.book.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json'
      },
      body: JSON.stringify(updateData)
    })
    
    // Émettre l'événement de mise à jour réussie
    emit('book-updated')
    closeModal()
    
    alert('Livre modifié avec succès !')
  } catch (error: any) {
    console.error('Erreur lors de la modification:', error)
    console.error('Détails de l\'erreur:', error.data)
    
    let errorMessage = 'Erreur lors de la modification du livre'
    if (error.data && error.data.detail) {
      errorMessage = error.data.detail
    } else if (error.data && error.data.error) {
      errorMessage = error.data.error
    }
    
    alert(errorMessage)
  }
}
</script>
