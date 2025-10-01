export default defineNuxtRouteMiddleware((to, from) => {
  if (typeof window !== 'undefined') {
    // Vérifier d'abord le token d'authentification
    const authToken = localStorage.getItem('auth_token')
    
    // Fallback sur l'ancien système si le token n'existe pas
    const isLoggedIn = localStorage.getItem('isLoggedIn')
    const userId = localStorage.getItem('userId')
    
    // Utilisateur authentifié si il a un token OU les anciennes clés
    const isAuthenticated = authToken || (isLoggedIn === 'true' && userId)
    
    if (!isAuthenticated) {
      return navigateTo('/')
    }
  }
})