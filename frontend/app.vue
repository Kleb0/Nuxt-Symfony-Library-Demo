<template>
  <NuxtLayout>
    <div class="app-layout">
      <Header />
      <main class="main-content">
        <NuxtPage />
      </main>
      <Footer />
    </div>
  </NuxtLayout>
</template>

<script setup>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
</script>

<style>
.app-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

</style>