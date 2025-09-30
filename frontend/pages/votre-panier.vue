<template>
  <div class="panier-page">
    <main class="main-content">
      <div class="panier-container">
        <h1>Votre panier</h1>
        <div class="panier-content">
          Votre panier
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
})
</script>

<style scoped>
.panier-page {
  min-height: 100vh;
  background-color: #f5f5f5;
}

.main-content {
  padding: 40px 20px;
  display: flex;
  justify-content: center;
}

.panier-container {
  max-width: 1200px;
  width: 100%;
  background: white;
  border-radius: 12px;
  padding: 40px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.panier-container h1 {
  font-size: 2.5rem;
  color: #3b3434;
  margin-bottom: 30px;
  text-align: center;
  font-family: 'Roboto', sans-serif;
}

.panier-content {
  font-size: 1.5rem;
  color: #666;
  text-align: center;
  padding: 60px 20px;
  font-family: 'Roboto', sans-serif;
}
</style>
