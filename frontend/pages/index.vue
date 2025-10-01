<template>
  <div>
    <div class="parallax">
    </div>
      
      <Carousel />
      <HeroPage />
    
    <div class="parallax"></div>
  </div>
</template>

<script setup lang="ts">
import Carousel from '../components/carousel.vue';
import HeroPage from '../components/HeroPage.vue';
</script>

<style scoped>
body {
  background-image: url('/images/Autumn_Leaves.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}

.parallax {
  height: 200px;
  background-image: url('/images/Autumn_Leaves.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  background-repeat: no-repeat;
}
</style>