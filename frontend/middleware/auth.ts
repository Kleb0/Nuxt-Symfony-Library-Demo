export default defineNuxtRouteMiddleware((to, from) => {
  if (typeof window !== 'undefined') {
    const isLoggedIn = localStorage.getItem('isLoggedIn')
    const userId = localStorage.getItem('userId')
    
    if (!isLoggedIn || isLoggedIn !== 'true' || !userId) {
      return navigateTo('/')
    }
  }
})