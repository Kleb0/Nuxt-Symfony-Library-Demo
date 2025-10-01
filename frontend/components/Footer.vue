<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>Nuxt Symfony Library</h3>
        <p>Une bibliothèque moderne pour vos projets Nuxt & Symfony</p>
      </div>
      
      <div class="footer-section">
        <h4>Navigation</h4>
        <ul>
          <li><NuxtLink to="/" class="">Accueil</NuxtLink></li>
          <li><NuxtLink to="/livres" class="">Livres</NuxtLink></li>
          <li><NuxtLink to="/about" class="">À propos</NuxtLink></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h4>Contact</h4>
        <p>© 2025 Nuxt Symfony Library</p>
        <p>Tous droits réservés</p>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  width: 100%;
  background-color: #2d3748;
  color: white;
  padding: 40px 0 20px 0;
  margin-top: auto;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  gap: 40px;
  padding: 0 20px;
}

.footer-section {
  flex: 1;
}

.footer-section h3 {
  font-size: 24px;
  margin-bottom: 16px;
  color: #DEA54A;
}

.footer-section h4 {
  font-size: 18px;
  margin-bottom: 12px;
  color: #DEA54A;
}

.footer-section p {
  margin-bottom: 8px;
  color: #cbd5e0;
}

.footer-section ul {
  list-style: none;
  padding: 0;
}

.footer-section ul li {
  margin-bottom: 8px;
}

.footer-section ul li a {
  color: #cbd5e0;
  text-decoration: none;
  transition: color 0.2s;
}

.footer-section ul li a:hover {
  color: #DEA54A;
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    gap: 20px;
  }
}
</style>