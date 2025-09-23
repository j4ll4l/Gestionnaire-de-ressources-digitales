<script setup lang="ts">
defineProps({

  nom: String,
  description: String,
id: String,
})
</script>

<template>
  <div class="card btn_hover">
    <h3>{{ nom }}</h3>
    <p>{{ description }}</p>
    <RouterLink :to="`/categorie/${id}`">Voir la collection →</RouterLink>
  </div>
</template>



<style scoped>
.card {
  background-color: white;
  border-radius: 12px;
  padding: 6rem 5rem;
  width: 300px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.05);
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.card.btn_hover:hover {
  border: 2px solid #999;
}

.card .icon {
  font-size: 1.5rem;
}

.card h3 {
  font-size: 1.2rem;
  margin-bottom: 5px;
}

.card p {
  font-size: 0.95rem;
  color: #666;
}

.card a {
  color: #ff6b00;
  position: relative;
  text-decoration: none;
  transition: color 0.3s;
  font-weight: bold;
  margin-top: auto;
}

.card a::after {
  content: '';
  display: block;
  position: absolute;
  left: 0;
  bottom: -2px;
  width: 0;
  height: 2px;
  background: #ff6b00;
  transition: width 0.3s;
}

.card a:hover::after {
  width: 100%;
}
</style>
