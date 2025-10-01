export interface Account {
  id: number
  nom: string
  prenom: string
  nomComplet: string
  createdAt: string
  updatedAt: string | null
}

export interface Author {
  id: number
  firstName: string
  lastName: string
  fullName: string
  biography?: string
  birthDate?: string
  nationality?: string
  createdAt: string
  updatedAt?: string
}

export interface Category {
  id: number
  name: string
  description?: string
  createdAt: string
  updatedAt?: string
}

export interface Book {
  id: number
  titre: string
  description?: string
  prix: string
  image?: string
  stock: number
  isbn?: string
  datePublication?: string
  nombrePages?: number
  authors: Author[]
  categories: Category[]
  createdAt: string
  updatedAt?: string
}

export interface ApiResponse<T> {
  success: boolean
  data: T
  total?: number
  message?: string
  errors?: string[]
}