

// Middleware de protection admin
// La vérification réelle du statut admin se fait dans Book-Management.vue via inject('currentUser')
// Ce middleware sert de placeholder pour les routes protégées

// Ce middleware s'execute pendant le cycle de vie de son composant, ici la page Book-Management.vue
// Les middleware de Nuxt ont accès au contexte de la requête côté serveur, inject, ref, watch.
// L'execution est reactive, il se re-déclenche si une donnée change. 

//1- Navigation vers BookManagent 
//2 - La page se charge
// 3 Le composant se monte 
// 4 inect('currentUser') est appelé dans BookManagement.vue
// 5 watch détecte le current user et check si admin
export default defineNuxtRouteMiddleware((to, from) => {
  if (import.meta.client) {
    return
  }
})