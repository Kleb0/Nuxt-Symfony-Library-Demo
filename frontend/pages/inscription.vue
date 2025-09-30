<template>
  <div class="inscription-form">
    <h2>Inscription</h2>
    <form @submit.prevent="submitForm">
      <div style="display: flex; gap: 10px;">
        <input v-model="nom" type="text" placeholder="Nom" required class="input" style="flex:1;" />
        <input v-model="prenom" type="text" placeholder="Prénom" required class="input" style="flex:1;" />
      </div>
      <div style="display: flex; gap: 10px; margin-top: 10px;">
        <input v-model="mail" type="email" placeholder="Email" required class="input" style="flex:1;" />
        <input v-model="telephone" type="text" placeholder="Téléphone (ex: 0123456789)" required class="input" pattern="^(\+33|0)[1-9](\d{8})$" title="Format: 0123456789 ou +33123456789" style="flex:1;" />
        <input v-model="adresse" type="text" placeholder="Adresse" required class="input" style="flex:1;" />
      </div>
      <input v-model="pseudo" type="text" placeholder="Pseudo" required class="input" />
  <div style="position: relative; width: 100%;">
    <input v-model="motDePasse" type="password" placeholder="Mot de passe (min 8 caractères)" required class="input" minlength="8" />
    <span v-if="motDePasse.length > 0 && passwordStrength !== 'strong'" class="password-warning">Le mot de passe doit contenir 8 caractères, chiffres, majuscules, minuscules, symboles</span>
  </div>
      <div class="password-strength-row">
        <div
          class="strength-box strength-weak"
          :style="{ opacity: motDePasse.length > 0 ? 1 : 0, transition: 'opacity 0.3s' }"
        ></div>
        <div
          class="strength-box strength-medium"
          :style="{ opacity: passwordStrength === 'medium' || passwordStrength === 'strong' ? 1 : 0, transition: 'opacity 0.3s' }"
        ></div>
        <div
          class="strength-box strength-strong"
          :style="{ opacity: passwordStrength === 'strong' ? 1 : 0, transition: 'opacity 0.3s' }"
        ></div>
      </div>
  <div style="position: relative; width: 100%;">
    <input v-model="motDePasseConfirm" type="password" placeholder="Confirmez le mot de passe" required class="input" minlength="8" />
    <span v-if="motDePasseConfirm.length > 0" :class="confirmClass" class="password-confirm-indicator">
      {{ motDePasseConfirm === motDePasse ? '✔️ Les mots de passe correspondent' : '❌ Les mots de passe ne correspondent pas' }}
    </span>
  </div>
      <div class="image-upload-container">
        <label for="imageUpload" class="image-upload-label">
          Image de profil (optionnelle)
        </label>
        <input 
          id="imageUpload"
          type="file" 
          accept="image/*" 
          @change="handleImageUpload" 
          class="image-input"
        />
        <div v-if="imagePreview" class="image-preview">
          <img :src="imagePreview" alt="Aperçu" class="preview-img" />
          <button type="button" @click="removeImage" class="remove-image-btn">✕</button>
        </div>
      </div>
      <div style="display: flex; justify-content: center; margin-top: 16px;">
        <button type="submit" class="submit-btn">S'inscrire</button>
      </div>
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'

const nom = ref('')
const prenom = ref('')
const pseudo = ref('')
const mail = ref('')
const telephone = ref('')
const adresse = ref('')
const motDePasse = ref('')
const motDePasseConfirm = ref('')
const imageFile = ref(null)
const imagePreview = ref('')

const confirmClass = computed(() => {
  if (motDePasseConfirm.value.length === 0) return ''
  return motDePasseConfirm.value === motDePasse.value ? 'match' : 'no-match'
})

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    imageFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      const result = e.target.result
      imagePreview.value = result
    }
    reader.readAsDataURL(file)
  }
}

function removeImage() {
  imageFile.value = null
  imagePreview.value = ''
  document.getElementById('imageUpload').value = ''
}
const passwordStrength = ref('weak')

function checkPasswordStrength(pw) {
  // Weak: only lowercase letters and/or numbers
  if (/^[a-z0-9]+$/.test(pw) && !/[A-Z]/.test(pw) && !/[^a-zA-Z0-9]/.test(pw)) {
    return 'weak'
  }
  // Strong: at least one lowercase, one uppercase, and one symbol
  if (/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).+$/.test(pw)) {
    return 'strong'
  }
  // Medium: at least one lowercase and one uppercase, may include numbers, no symbol
  if (pw.length > 0 && /[a-z]/.test(pw) && /[A-Z]/.test(pw) && !/[^a-zA-Z0-9]/.test(pw)) {
    return 'medium'
  }
  return 'weak'
}

watch(motDePasse, (val) => {
  passwordStrength.value = checkPasswordStrength(val)
})
const imageProfil = ref('')
const errorMessage = ref('')
const successMessage = ref('')

async function submitForm() {
  errorMessage.value = ''
  successMessage.value = ''
  if (!nom.value || !prenom.value || !pseudo.value || !mail.value || !telephone.value || !adresse.value || !motDePasse.value || !motDePasseConfirm.value) {
    errorMessage.value = 'Veuillez remplir tous les champs requis.'
    return
  }
  if (motDePasse.value !== motDePasseConfirm.value) {
    errorMessage.value = 'Les mots de passe ne correspondent pas.'
    return
  }
  try {
    let imageBase64 = null
    if (imageFile.value) {
      imageBase64 = await convertImageToJpeg(imageFile.value)
    }

    const response = await $fetch('http://localhost:8000/api/users', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/ld+json',
        'Accept': 'application/ld+json',
      },
      body: JSON.stringify({
        nom: nom.value,
        prenom: prenom.value,
        pseudo: pseudo.value,
        mail: mail.value,
        telephone: telephone.value,
        adresse: adresse.value,
        motDePasse: motDePasse.value,
        imageProfil: imageBase64,
        statusId: 2 
      })
    })
    if (response.id) {
      successMessage.value = 'Inscription réussie ! Vous pouvez maintenant vous connecter.'
      nom.value = ''
      prenom.value = ''
      pseudo.value = ''
      mail.value = ''
      telephone.value = ''
      adresse.value = ''
      motDePasse.value = ''
      motDePasseConfirm.value = ''
      imageFile.value = null
      imagePreview.value = ''
      document.getElementById('imageUpload').value = ''
    } else {
      errorMessage.value = "Erreur lors de l'inscription."
    }
  } catch (error) {
    console.error('Erreur détaillée:', error)
    if (error.data && error.data.violations) {
      errorMessage.value = error.data.violations.map(v => v.message).join(', ')
    } else if (error.data && error.data.detail) {
      errorMessage.value = error.data.detail
    } else {
      errorMessage.value = 'Erreur serveur ou données invalides.'
    }
  }
}

function convertImageToJpeg(file) {
  return new Promise((resolve, reject) => {
    const canvas = document.createElement('canvas')
    const ctx = canvas.getContext('2d')
    const img = new Image()
    
    img.onload = () => {
      const { width, height } = img
      
      canvas.width = width
      canvas.height = height
      
      ctx.fillStyle = 'white'
      ctx.fillRect(0, 0, width, height)
      ctx.drawImage(img, 0, 0, width, height)
      
      const jpegBase64 = canvas.toDataURL('image/jpeg', 0.95) 
      resolve(jpegBase64)
    }
    
    img.onerror = reject
    img.src = URL.createObjectURL(file)
  })
}
</script>

<style scoped>
.password-confirm-indicator {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 13px;
  opacity: 0.8;
  pointer-events: none;
  z-index: 2;
  transition: color 0.2s;
}
.password-confirm-indicator.match {
  color: #388e3c;
}
.password-confirm-indicator.no-match {
  color: #d32f2f;
}

.image-upload-container {
  width: 100%;
  margin-bottom: 16px;
}

.image-upload-label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #333;
  font-weight: 500;
}

.image-input {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 16px;
  background: #fff;
  cursor: pointer;
}

.image-input::-webkit-file-upload-button {
  background: #1976D2;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  margin-right: 12px;
  cursor: pointer;
}

.image-input::-webkit-file-upload-button:hover {
  background: #1565C0;
}

.image-preview {
  position: relative;
  margin-top: 12px;
  display: inline-block;
}

.preview-img {
  max-width: 150px;
  max-height: 150px;
  border-radius: 8px;
  border: 2px solid #ddd;
  object-fit: cover;
}

.remove-image-btn {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #d32f2f;
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.remove-image-btn:hover {
  background: #b71c1c;
}
.inscription-form {
  max-width: 800px;
  margin: 40px auto;
  padding: 32px;
  border-radius: 16px;
  background: #fff;
  box-shadow: 0 2px 16px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  align-items: center;
}
.input {
  width: 100%;
  padding: 12px;
  margin-bottom: 16px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 16px;
   box-sizing: border-box;
 }
.password-warning {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #d32f2f;
  opacity: 0.7;
  font-size: 13px;
  pointer-events: none;
  background: transparent;
  z-index: 2;
}

.password-strength-row {
  display: flex;
  gap: 8px;
  margin-bottom: 16px;
  width: 100%;
}
.strength-box {
  flex: 1;
  height: 10px;
  border-radius: 16px;
  margin: 0 2px;
  background: #eee;
  transition: background 0.2s;
}
.strength-weak.active {
  background: #d32f2f;
}
.strength-medium.active {
  background: #ff9800;
}
.strength-strong.active {
  background: #4caf50;
}
.strength-weak {
  background: #d32f2f;
}
.strength-medium {
  background: #ff9800;
}
.strength-strong {
  background: #4caf50;
}

.submit-btn {
  background: #1976D2;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 12px 24px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 8px;
}
.submit-btn:hover {
  background: #3b3434;
  color: #fff;
}
.error-message {
  color: #d32f2f;
  margin-top: 12px;
  font-size: 15px;
  text-align: center;
}
.success-message {
  color: #388e3c;
  margin-top: 12px;
  font-size: 15px;
  text-align: center;
}
</style>
