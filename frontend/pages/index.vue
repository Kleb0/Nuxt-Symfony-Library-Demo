<template>
  <div class="min-h-screen bg-gray-50">
    <NuxtRouteAnnouncer />
    
    <Carousel />
    <HeroPage />
  </div>
</template>

<script setup lang="ts">
import Carousel from '../components/carousel.vue';
import HeroPage from '../components/HeroPage.vue';

</script>

<style>
@import "tailwindcss/preflight";
@import "tailwindcss/utilities";
</style>