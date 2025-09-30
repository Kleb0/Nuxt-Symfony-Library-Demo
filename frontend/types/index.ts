export interface Account {
  id: number
  nom: string
  prenom: string
  nomComplet: string
  createdAt: string
  updatedAt: string | null
}

export interface ApiResponse<T> {
  success: boolean
  data: T
  total?: number
  message?: string
  errors?: string[]
}