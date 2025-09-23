<script setup lang="ts">
import CardRessource from '@/components/UI/CardRessource.vue'
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const currentYear = ref(new Date().getFullYear())
const route = useRoute()

const sections = ref<any[]>([]) // stockage des sections + ressources

onMounted(async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/categorie/${route.params.id}/sections-ressources`,
    )
    if (!response.ok) {
      throw new Error('Erreur API : ' + response.status)
    }
    const data = await response.json()
    sections.value = data
  } catch (error) {
    console.error('Erreur lors de la récupération des ressources :', error)
  }
})
</script>

<template>
  <!-- HEADER -->
  <header class="header">
    <div class="container">
      <!-- <RouterLink to="https://www.e-potion.fr/">
          <img class="tampon" src="img/logo_epotion.png" alt="Logo e-Potion">
        </RouterLink> -->

      <h1>Gestionnaire de ressources digitales</h1>

      <nav>
        <ul>
          <li><RouterLink to="/admin" class="admin-buttons button ">Admin</RouterLink></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <section v-for="section in sections" :key="section.section_id">
      <h2>{{ section.section_nom }}</h2>

      <div class="media-cards">
        <CardRessource
          v-for="ressource in section.ressources"
          :key="ressource.id"
          :id="ressource.id"
          :nom="ressource.nom"
          :description="ressource.description"
          :url="ressource.url"
          :tags="ressource.tags"
        />
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p>
        <a href="http://www.e-potion.fr/"><strong>e-Potion</strong></a>
        by
        <a href="https://www.linkedin.com/in/christianbourgeoisdev" target="_blank">
          Christian Bourgeois
        </a>
        <br />
        Tous droits réservés -
        <strong>{{ currentYear }}</strong>
      </p>
      <p class="mention">made with <a href="https://bulma.io/">Bulma</a></p>
    </div>
  </footer>
</template>
