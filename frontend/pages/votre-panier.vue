<template>
  <div class="panier-page">
    <main class="main-content">
      <div class="panier-container">
        <h1>Votre panier</h1>
        
        <div v-if="cartItems.length === 0" class="empty-cart">
          <p>Votre panier est vide</p>
          <NuxtLink to="/" class="continue-shopping">Continuer vos achats</NuxtLink>
        </div>
        
        <div v-else class="cart-content">
          <div class="cart-items">
            <div v-for="item in cartItems" :key="item.id" class="cart-item">
              <div class="item-image">
                <img v-if="item.image" :src="item.image" :alt="item.titre" />
                <div v-else class="no-image">Pas d'image</div>
              </div>
              
              <div class="item-details">
                <h3>{{ item.titre }}</h3>
                <p class="item-author">{{ item.auteur }}</p>
                <p class="item-price">{{ item.unitPrice }}€</p>
              </div>
              
              <div class="item-quantity">
                <button @click="decreaseQuantity(item.id)" class="qty-btn">-</button>
                <span class="quantity">{{ item.quantity }}</span>
                <button @click="increaseQuantity(item.id)" class="qty-btn">+</button>
              </div>
              
              <div class="item-total">
                {{ (item.unitPrice * item.quantity).toFixed(2) }}€
              </div>
              
              <button @click="removeItem(item.id)" class="remove-btn">×</button>
            </div>
          </div>
          
          <div class="cart-summary">
            <div class="total-items">
              {{ totalItems }} article{{ totalItems > 1 ? 's' : '' }}
            </div>
            <div class="total-price">
              <strong>Total: {{ totalPrice.toFixed(2) }}€</strong>
            </div>
            <button class="checkout-btn">Passer commande</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

definePageMeta({
  middleware: 'auth'
})

const cartItems = ref([])

const loadCart = async () => {
  try {
    const userId = localStorage.getItem('userId')
    if (!userId) {
      cartItems.value = []
      return
    }
    
    const response = await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}`)
    cartItems.value = response || []
  } catch (error) {
    console.error('Erreur lors du chargement du panier:', error)
    cartItems.value = []
  }
}

const increaseQuantity = async (itemId) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (!item) return

  try {
    const userId = localStorage.getItem('userId')
    if (!userId) return

    await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}/update-quantity`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        bookId: itemId,
        quantity: item.quantity + 1
      })
    })

    await loadCart()
  } catch (error) {
    console.error('Erreur lors de la mise à jour de la quantité:', error)
  }
}

const decreaseQuantity = async (itemId) => {
  const item = cartItems.value.find(item => item.id === itemId)
  if (!item) return

  try {
    const userId = localStorage.getItem('userId')
    if (!userId) return

    if (item.quantity === 1) {
      await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}/remove`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          bookId: itemId
        })
      })
    } else {
      await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}/update-quantity`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          bookId: itemId,
          quantity: item.quantity - 1
        })
      })
    }

    await loadCart()
  } catch (error) {
    console.error('Erreur lors de la mise à jour de la quantité:', error)
  }
}

const removeItem = async (itemId) => {
  try {
    const userId = localStorage.getItem('userId')
    if (!userId) return

    await $fetch(`http://127.0.0.1:8000/api/cart/user/${userId}/remove`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        bookId: itemId
      })
    })

    await loadCart()
  } catch (error) {
    console.error('Erreur lors de la suppression de l\'article:', error)
  }
}

const totalItems = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0)
})

const totalPrice = computed(() => {
  return cartItems.value.reduce((total, item) => total + (item.unitPrice * item.quantity), 0)
})

onMounted(() => {
  loadCart()
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

.empty-cart {
  text-align: center;
  padding: 60px 20px;
}

.empty-cart p {
  font-size: 1.5rem;
  color: #666;
  margin-bottom: 30px;
  font-family: 'Roboto', sans-serif;
}

.continue-shopping {
  background: #DEA54A;
  color: #3b3434;
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-family: 'Roboto', sans-serif;
  transition: background 0.2s;
}

.continue-shopping:hover {
  background: #c8934a;
}

.cart-content {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
  border: 1px solid #e5e5e5;
  border-radius: 12px;
  background: #fafafa;
}

.item-image {
  width: 80px;
  height: 80px;
  flex-shrink: 0;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.no-image {
  width: 100%;
  height: 100%;
  background: #e5e5e5;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  font-size: 12px;
  color: #666;
}

.item-details {
  flex: 1;
  min-width: 0;
}

.item-details h3 {
  margin: 0 0 8px 0;
  font-size: 1.2rem;
  color: #3b3434;
  font-family: 'Roboto', sans-serif;
}

.item-author {
  margin: 0 0 8px 0;
  color: #666;
  font-size: 0.9rem;
}

.item-price {
  margin: 0;
  color: #3b3434;
  font-weight: 600;
}

.item-quantity {
  display: flex;
  align-items: center;
  gap: 15px;
}

.qty-btn {
  width: 32px;
  height: 32px;
  border: 1px solid #DEA54A;
  background: white;
  color: #DEA54A;
  border-radius: 6px;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.qty-btn:hover {
  background: #3b3434;
  color: white;
}

.quantity {
  font-size: 1.1rem;
  font-weight: 600;
  min-width: 30px;
  text-align: center;
}

.item-total {
  font-size: 1.2rem;
  font-weight: 700;
  color: #3b3434;
  min-width: 80px;
  text-align: right;
}

.remove-btn {
  width: 32px;
  height: 32px;
  border: none;
  background: #dc2626;
  color: white;
  border-radius: 6px;
  font-size: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
}

.remove-btn:hover {
  background: #b91c1c;
}

.cart-summary {
  border-top: 2px solid #e5e5e5;
  padding-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 15px;
}

.total-items {
  font-size: 1.1rem;
  color: #666;
}

.total-price {
  font-size: 1.5rem;
  color: #3b3434;
  font-family: 'Roboto', sans-serif;
}

.checkout-btn {
  background: #DEA54A;
  color: #3b3434;
  border: none;
  padding: 15px 40px;
  border-radius: 8px;
  font-size: 1.2rem;
  font-family: 'Roboto', sans-serif;
  cursor: pointer;
  transition: background 0.2s;
}

.checkout-btn:hover {
  background: #3b3434;
  color: #fff;
}

@media (max-width: 768px) {
  .cart-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .item-quantity {
    align-self: center;
  }
  
  .item-total {
    align-self: center;
    font-size: 1.3rem;
  }
  
  .remove-btn {
    align-self: center;
  }
  
  .cart-summary {
    align-items: center;
    text-align: center;
  }
}
</style>
