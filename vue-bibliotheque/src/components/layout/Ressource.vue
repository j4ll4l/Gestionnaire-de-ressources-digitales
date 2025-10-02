<script setup lang="ts">
import CardRessource from '@/components/UI/CardRessource.vue'
import { ref, onMounted, computed} from 'vue'
import { useRoute } from 'vue-router'
import type { Section, Ressource, Tag } from '@/components/shared/interfaces/Categorie.interface'
import logo from '@/assets/img/favico/favicon-32x32.png'

const currentYear = ref(new Date().getFullYear())
const route = useRoute()

const sections = ref<Section[]>([]) // stockage des sections + ressources
const searchTag = ref('')

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
const filteredSections = computed(() => {
  const tag = searchTag.value.trim().toLowerCase()
  if (!tag) return sections.value
  return sections.value
    .map(section => ({
      id: section.id,
      nom: section.nom,
      ressources: section.ressources.filter((r: Ressource) =>
        r.tags.some((t: Tag) => t.nom.toLowerCase().includes(tag))
      )
    }))
    .filter(section => section.ressources.length)
})
</script>

<template>
  <!-- HEADER -->
  <header class="header">
    <div class="container admin-container">
      <RouterLink to="https://www.e-potion.fr/">
          <img class="tampon" :src="logo" alt="Logo e-Potion">
        </RouterLink>

      <h1>Gestionnaire de ressources digitales</h1>

      <nav>
        <ul>
          <li><RouterLink to="/admin" class="btn">Admin</RouterLink></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <!-- Barre de recherche -->
        <div class="media-search">
            <input type="text" placeholder="Rechercher par tag" v-model="searchTag">
            <small>Astuce : entrez un mot-clé pour lancer la recherche.</small>
        </div>
    <section v-for="section in filteredSections" :key="section.id" class="cards ressources">
      <h2>{{ section.nom }}</h2>

      <div class="cards">
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
