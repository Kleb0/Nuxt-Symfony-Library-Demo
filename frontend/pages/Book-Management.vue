<template>
    <div class="Book-Management p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Gestion des Livres</h1>
        
        <!-- Section Livres -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">📚 Livres</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div v-if="booksLoading" class="p-4 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Chargement des livres...</p>
                </div>
                <div v-else-if="booksError" class="p-4 text-center text-red-600">
                    Erreur lors du chargement des livres: {{ booksError }}
                </div>
                <div v-else-if="books.length === 0" class="p-4 text-center text-gray-500">
                    Aucun livre trouvé
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résumé</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteurs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégories</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="book in books" :key="book.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ book.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 h-16 w-12">
                                            <img 
                                                v-if="book.image" 
                                                :src="`http://127.0.0.1:8000/${book.image}`" 
                                                :alt="book.titre"
                                                class="h-16 w-12 object-cover rounded-md border border-gray-200"
                                                @error="handleImageError"
                                            />
                                            <div 
                                                v-else 
                                                class="h-16 w-12 bg-gray-200 rounded-md flex items-center justify-center"
                                            >
                                                <span class="text-gray-400 text-xs">📖</span>
                                            </div>
                                        </div>
                                        <div>
                                            <input 
                                                :id="`fileInput-${book.id}`"
                                                type="file" 
                                                accept="image/*" 
                                                @change="(event) => handleImageUpload(event, book.id)"
                                                class="hidden"
                                            />
                                            <button 
                                                @click="triggerFileInput(book.id)"
                                                class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded hover:bg-blue-100 transition-colors"
                                                :disabled="uploadingImages.has(book.id)"
                                            >
                                                <span v-if="uploadingImages.has(book.id)">Upload...</span>
                                                <span v-else>Changer</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ book.titre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                    <div class="truncate" :title="book.description">
                                        {{ book.description || 'Aucune description' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span v-for="(author, index) in book.authors" :key="author.id">
                                        {{ author.fullName }}<span v-if="index < book.authors.length - 1">, </span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span v-for="(category, index) in book.categories" :key="category.id">
                                        {{ category.name }}<span v-if="index < book.categories.length - 1">, </span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ book.prix }}€</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ book.stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        @click="openEditModal(book)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    >
                                        Modifier
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Auteurs -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">✍️ Auteurs</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div v-if="authorsLoading" class="p-4 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Chargement des auteurs...</p>
                </div>
                <div v-else-if="authorsError" class="p-4 text-center text-red-600">
                    Erreur lors du chargement des auteurs: {{ authorsError }}
                </div>
                <div v-else-if="authors.length === 0" class="p-4 text-center text-gray-500">
                    Aucun auteur trouvé
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom complet</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nationalité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de naissance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="author in authors" :key="author.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ author.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ author.fullName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ author.nationality || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(author.birthDate) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</button>
                                    <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Catégories -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">🏷️ Catégories</h2>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div v-if="categoriesLoading" class="p-4 text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">Chargement des catégories...</p>
                </div>
                <div v-else-if="categoriesError" class="p-4 text-center text-red-600">
                    Erreur lors du chargement des catégories: {{ categoriesError }}
                </div>
                <div v-else-if="categories.length === 0" class="p-4 text-center text-gray-500">
                    Aucune catégorie trouvée
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de création</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ category.id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ category.name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ category.description || 'Aucune description' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(category.createdAt) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</button>
                                    <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de modification -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
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
                            @click="closeEditModal"
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

// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://127.0.0.1:8000/api'

// États réactifs pour les livres
const books = ref<Book[]>([])
const booksLoading = ref(true)
const booksError = ref<string | null>(null)

// États réactifs pour les auteurs
const authors = ref<Author[]>([])
const authorsLoading = ref(true)
const authorsError = ref<string | null>(null)

// États réactifs pour les catégories
const categories = ref<Category[]>([])
const categoriesLoading = ref(true)
const categoriesError = ref<string | null>(null)

// État pour gérer les uploads d'images
const uploadingImages = ref(new Set<number>())

// État pour gérer le modal de modification
const showEditModal = ref(false)
const editingBook = ref<Book | null>(null)
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

// Fonction utilitaire pour formater les dates
const formatDate = (date: string | null | undefined): string => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('fr-FR')
}

// Fonction pour gérer les erreurs d'image
const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement
  img.style.display = 'none'
}

// Fonction pour déclencher le sélecteur de fichier
const triggerFileInput = (bookId: number) => {
  const fileInput = document.getElementById(`fileInput-${bookId}`) as HTMLInputElement
  if (fileInput) {
    fileInput.click()
  }
}

// Fonction pour ouvrir le modal de modification
const openEditModal = (book: Book) => {
  editingBook.value = book
  editForm.value = {
    titre: book.titre,
    description: book.description || '',
    prix: book.prix,
    stock: book.stock,
    isbn: book.isbn || '',
    datePublication: (book.datePublication && typeof book.datePublication === 'string') ? book.datePublication.split('T')[0] : '',
    nombrePages: book.nombrePages || 0,
    authors: book.authors.map(author => author.id),
    categories: book.categories.map(category => category.id)
  }
  showEditModal.value = true
}

// Fonction pour fermer le modal
const closeEditModal = () => {
  showEditModal.value = false
  editingBook.value = null
}

// Fonction pour sauvegarder les modifications
const saveBook = async () => {
  if (!editingBook.value) return
  
  try {
    const updateData = {
      titre: editForm.value.titre,
      description: editForm.value.description,
      prix: editForm.value.prix,
      stock: editForm.value.stock,
      isbn: editForm.value.isbn,
      datePublication: editForm.value.datePublication || null,
      nombrePages: editForm.value.nombrePages || null,
      authors: editForm.value.authors.map(id => `/api/authors/${id}`),
      categories: editForm.value.categories.map(id => `/api/categories/${id}`),
      image: editingBook.value.image 
    }
    
    await $fetch(`${baseURL}/books/${editingBook.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json'
      },
      body: JSON.stringify(updateData)
    })
    
    // Recharger les livres pour afficher les modifications
    await fetchBooks()
    closeEditModal()
    
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

// Fonction pour gérer l'upload d'image
const handleImageUpload = async (event: Event, bookId: number) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (!file) return
  
  console.log('Fichier sélectionné:', file.name, file.type, file.size)
  
  // Vérifier le type de fichier
  if (!file.type.startsWith('image/')) {
    alert('Veuillez sélectionner un fichier image valide')
    return
  }
  
  // Vérifier la taille du fichier (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Le fichier est trop volumineux. Taille maximale : 5MB')
    return
  }
  
  try {
    uploadingImages.value.add(bookId)
    
    // Créer FormData pour l'upload
    const formData = new FormData()
    formData.append('image', file)
    
    console.log('URL d\'upload:', `${baseURL}/books/${bookId}/upload-image`)
    console.log('FormData créé avec le fichier')
    
    // Upload de l'image
    const response = await $fetch<{ imagePath: string }>(`${baseURL}/books/${bookId}/upload-image`, {
      method: 'POST',
      body: formData
    })
    
    console.log('Réponse de l\'API:', response)
    
    // Mettre à jour l'image du livre dans la liste
    const bookIndex = books.value.findIndex(b => b.id === bookId)
    if (bookIndex !== -1 && books.value[bookIndex]) {
      books.value[bookIndex].image = response.imagePath
    }
    
    // Réinitialiser l'input file
    target.value = ''
    
    console.log('Image uploadée avec succès:', response.imagePath)
  } catch (error: any) {
    console.error('Erreur complète:', error)
    console.error('Message d\'erreur:', error.message)
    console.error('Statut:', error.statusCode)
    console.error('Data:', error.data)
    
    let errorMessage = 'Erreur lors de l\'upload de l\'image'
    if (error.data && error.data.error) {
      errorMessage = error.data.error
    } else if (error.message) {
      errorMessage = error.message
    }
    
    alert(errorMessage)
  } finally {
    uploadingImages.value.delete(bookId)
  }
}

// Fonction pour fetcher les livres
const fetchBooks = async () => {
  try {
    booksLoading.value = true
    booksError.value = null
    
    const response = await $fetch<{ member?: Book[], 'hydra:member'?: Book[] }>(`${baseURL}/books`)
    books.value = response.member || response['hydra:member'] || []
    console.log('Books loaded:', books.value.length)
  } catch (error) {
    console.error('Erreur lors du chargement des livres:', error)
    booksError.value = 'Impossible de charger les livres'
  } finally {
    booksLoading.value = false
  }
}

// Fonction pour fetcher les auteurs
const fetchAuthors = async () => {
  try {
    authorsLoading.value = true
    authorsError.value = null
    
    const response = await $fetch<{ member?: Author[], 'hydra:member'?: Author[] }>(`${baseURL}/authors`)
    authors.value = response.member || response['hydra:member'] || []
    console.log('Authors loaded:', authors.value.length)
  } catch (error) {
    console.error('Erreur lors du chargement des auteurs:', error)
    authorsError.value = 'Impossible de charger les auteurs'
  } finally {
    authorsLoading.value = false
  }
}

// Fonction pour fetcher les catégories
const fetchCategories = async () => {
  try {
    categoriesLoading.value = true
    categoriesError.value = null
    
    const response = await $fetch<{ member?: Category[], 'hydra:member'?: Category[] }>(`${baseURL}/categories`)
    categories.value = response.member || response['hydra:member'] || []
    console.log('Categories loaded:', categories.value.length)
  } catch (error) {
    console.error('Erreur lors du chargement des catégories:', error)
    categoriesError.value = 'Impossible de charger les catégories'
  } finally {
    categoriesLoading.value = false
  }
}

// Chargement initial des données au montage du composant
onMounted(async () => {
  await Promise.all([
    fetchBooks(),
    fetchAuthors(),
    fetchCategories()
  ])
})
</script>

