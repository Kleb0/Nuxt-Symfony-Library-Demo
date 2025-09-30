<template>
  <header class="header-bg">
    <div class="rectangles-wrapper">
      <div class="rectangle rectangle-red-text">
        <span class="header-title">Nuxt Symfony Library</span>
      </div>
      <nav class="menu-bar">
        <ManagementButton />
        <NuxtLink to="/" class="menu-item">Accueil</NuxtLink>
        <NuxtLink to="/livres" class="menu-item">Livres</NuxtLink>
        <NuxtLink to="/about" class="menu-item">À propos</NuxtLink>
        <PanierButton />
      </nav>
    </div>
        <div class="loginstate-wrapper">
      <LoginState />
    </div>
  </header>
</template>

<script setup>
import LoginState from './LoginState.vue'
import PanierButton from './PanierButton.vue'
import ManagementButton from './ManagementButton.vue'
</script>

<style scoped>
 .header-title {
   color: #3b3434;
   font-size: 60px;
   font-weight: bold;
   font-family: 'Roboto', sans-serif !important;
   letter-spacing: 1px;
   text-align: center;
 }
.header-bg {
  background-color: #DEA54A;
  min-height: 380px;
  width: 100%;
  box-shadow: 0 4px 16px 0 rgba(92, 235, 156, 0.15);
  border-bottom: 2px solid #F6E5CB;
  transition: box-shadow 0.3s;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: relative;
  padding: 20px 80px;
  gap: 40px;
}
.rectangles-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex: 1;
  gap: 32px;
  margin-left: 150px;
}
.rectangle {
  border-radius: 24px;
  width: 700px;
  height: 100px;
  transition: box-shadow 0.3s, border-color 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loginstate-wrapper {
  width: 300px;
  height: 280px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.menu-bar {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 700px;
  height: 100px;
  border-radius: 24px;
}
.menu-item {
  color: #3b3434;
  font-size: 30px;
  font-family: 'Roboto', sans-serif;
  font-weight: 500;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 12px;
  transition: background 0.2s, color 0.2s;
}
.menu-item:hover {
  background: #3b3434;
  color: #fff;
}

@media (max-width: 1200px) {
  .header-bg {
    flex-direction: column;
    padding: 20px;
    transform: translateX(-50px);
    width: calc(100% + 50px);
    gap: 30px;
  }
  
  .loginstate-wrapper {
    width: 100%;
    max-width: 300px;
    margin-bottom: 50px;
    margin-left: 150px;
  }
}

@media (max-width: 768px) {
  .header-title {
    font-size: 40px;
  }
  
  .menu-item {
    font-size: 24px;
    padding: 8px 16px;
  }
  
  .rectangle {
    width: 90%;
    max-width: 500px;
  }
  
  .menu-bar {
    width: 90%;
    max-width: 500px;
  }
}

</style>