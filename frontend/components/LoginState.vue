<template>
  <div class="login-state">
    <template v-if="isLoggedIn && currentUser">
      <div class="user-info">
        <span class="welcome">
          <UserIcon class="icon" />
          Bonjour {{ currentUser.prenom }} {{ currentUser.nom }}, vous êtes connecté
        </span>
        <div class="user-actions">
          <button class="account-btn" @click="goToAccount">
            <UserCircleIcon class="icon" />
            Mon compte
          </button>
          <button class="logout-btn" @click="logout">
            <ArrowRightOnRectangleIcon class="icon" />
            Déconnexion
          </button>
          <span v-if="currentUser && currentUser.statuses && currentUser.statuses[0]?.statusName === 'Admin'" class="admin-text">Vous êtes administrateur</span>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="login-form">
        <span class="not-logged">
          <UserIcon class="icon" />
          Vous n'êtes pas connecté.
        </span>
        <input
          v-model="loginInput"
          type="text"
          placeholder="Email ou Pseudo"
          class="login-input"
        />
        <input
          v-model="passwordInput"
          type="password"
          placeholder="Mot de passe"
          class="login-input"
        />
        <button class="login-btn" @click="login" :disabled="isLoading">
          <ArrowRightOnRectangleIcon class="icon" />
          {{ isLoading ? 'Connexion...' : 'Connexion' }}
        </button>
        <button class="signup-btn" @click="goToInscription">
          <UserCircleIcon class="icon" />
          S'inscrire
        </button>
        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { UserIcon, ArrowRightOnRectangleIcon, UserCircleIcon } from '@heroicons/vue/24/outline'

const isLoggedIn = ref(false)
const currentUser = ref(null)
const loginInput = ref('')
const passwordInput = ref('')
const isLoading = ref(false)
const errorMessage = ref('')

async function fetchUserData(userId) {
  try {
    const response = await $fetch(`http://localhost:8000/api/users/${userId}`)
    return response
  } catch (error) {
    console.error('Erreur lors de la récupération des données utilisateur:', error)
    return null
  }
}

async function login() {
  if (!loginInput.value || !passwordInput.value) {
    errorMessage.value = 'Veuillez remplir tous les champs'
    return
  }

  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await $fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: loginInput.value,
        password: passwordInput.value
      })
    })

    if (response.success && response.user) {
      const userData = await fetchUserData(response.user.id)
      
      if (userData) {
        currentUser.value = userData
        isLoggedIn.value = true
        
        localStorage.setItem('isLoggedIn', 'true')
        localStorage.setItem('userId', response.user.id.toString())
        localStorage.setItem('userToken', response.token || '')
        
        loginInput.value = ''
        passwordInput.value = ''
      }
    } else {
      errorMessage.value = 'Identifiants incorrects'
    }
  } catch (error) {
    console.error('Erreur de connexion:', error)
    errorMessage.value = 'Erreur de connexion au serveur'
  } finally {
    isLoading.value = false
  }
}

function logout() {
  isLoggedIn.value = false
  currentUser.value = null
  loginInput.value = ''
  passwordInput.value = ''
  errorMessage.value = ''  
  
  localStorage.removeItem('isLoggedIn')
  localStorage.removeItem('userId')
  localStorage.removeItem('userToken')
}


function goToAccount() {
  const router = useRouter()
  router.push('/mon-compte')
}

function goToInscription() {
  const router = useRouter()
  router.push('/inscription')
}

onMounted(async () => {
  const savedLoginState = localStorage.getItem('isLoggedIn')
  const savedUserId = localStorage.getItem('userId')
  
  if (savedLoginState === 'true' && savedUserId) {
    const userData = await fetchUserData(parseInt(savedUserId))
    if (userData) {
      currentUser.value = userData
      isLoggedIn.value = true
    } else {
      logout()
    }
  }
})
</script>

<style scoped>
.login-state {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  font-family: 'Roboto', sans-serif;
}

.user-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  border: 2px solid #388e3c;
  border-radius: 12px;
  padding: 18px;
  background-color: #f1f8e9;
  max-width: 280px;
}

.user-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: center;
  width: 100%;
}

.welcome {
  color: #388e3c;
  font-size: 16px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.not-logged {
  color: #d32f2f;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  text-align: center;
}

.login-form {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  max-width: 280px;
  gap: 15px;
  border-radius: 12px;
  padding: 18px;
  background-color: #fafafa;
}

.login-input {
  width: 200px;
  padding: 10px 14px;
  border-radius: 8px;
  border: 2px solid #ccc;
  font-size: 14px;
  margin-top: 4px;
  margin-bottom: 4px;
  background: #fff;
  color: #222;
}

.login-input:focus {
  border-color: #1976D2;
  outline: none;
}



.login-btn, .logout-btn, .account-btn {
  background: #4caf50;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  margin-top: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 180px;
}


.signup-btn {
  background: #1976D2;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
  margin-top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 180px;
}


.login-btn:hover, .logout-btn:hover, .account-btn:hover, .signup-btn:hover {
  background: #3b3434;
  color: #fff;
}

.login-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.account-btn {
  background: #1976D2;
  color: #fff;
}

.logout-btn {
  background: #d32f2f;
  color: #fff;
}

.error-message {
  color: #d32f2f;
  font-size: 14px;
  text-align: center;
  margin-top: 10px;
}

.icon {
  width: 20px;
  height: 20px;
  display: block;
  margin: 0 auto 4px auto;
}

.admin-text {
  color: #388e3c;
  font-size: 15px;
  margin-top: 8px;
  text-align: center;
}
</style>