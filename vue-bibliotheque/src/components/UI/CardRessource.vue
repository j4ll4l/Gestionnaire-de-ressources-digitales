<script setup lang="ts">
defineProps({

  nom: String,
  description: String,
  url: String,
  id: Number,
 tags: Array as () => { id: number; nom: string }[],
})
</script>

<template>
  <div class="media-card">
    <h4>{{nom}}</h4>
    <p>{{description}}</p>
    <div class="media-tags"><span 
        v-for="tag in tags" 
        :key="tag.id"
      >
        #{{ tag.nom }}
      </span></div>
    <a :href="url" class="media-btn">Consulter</a>
  </div>
</template>

<style scoped>

.media-cards {
  display: flex;
  /* flex-wrap: wrap; */
  gap: 10px;
  justify-content: space-between;
}

.media-card {
  background-color: white;
  border-radius: 12px;
  padding: 20px;
  width: 300px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.media-card h4 {
  font-size: 1.1rem;
  color: #333;
}

.media-card p {
  font-size: 0.9rem;
  color: #666;
}

/* Tags */
.media-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.media-tags span {
  background-color: #333;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
}

/* Bouton */
.media-btn {
  align-self: flex-start;
  background-color: #ff4500;
  color: white;
  padding: 8px 15px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  margin-top: auto;
  align-self: flex-end;
}

.media-btn:hover {
  background-color: #ff6622;
}

</style>
