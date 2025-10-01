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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ book.titre }}</td>
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
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</button>
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
</template>

<script setup lang="ts">
import type { Book, Author, Category } from '~/types/index'

// Configuration de la base URL de l'API
const config = useRuntimeConfig()
const baseURL = config.public.apiBase || 'http://localhost:8000/api'

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

// Fonction utilitaire pour formater les dates
const formatDate = (date: string | null | undefined): string => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('fr-FR')
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

