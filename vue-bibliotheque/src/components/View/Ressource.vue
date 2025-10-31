<script setup lang="ts">
import CardRessource from '@/components/UI/CardRessource.vue'
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useRoute } from 'vue-router'
import type { Section, Ressource, Tag } from '@/components/shared/interfaces/Categorie.interface'
import logo from '@/assets/img/logo_epotion.png'
import Footer from '@/components/layout/Footer.vue'

const route = useRoute()
const filtreCategorie = ref<number | null>(null)
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
    // console.log(data)

    sections.value = data.map((s: any) => ({
      id: s.section_id,
      nom: s.section_nom,
      ressources: s.ressources,
    }))
  } catch (error) {
    console.error('Erreur lors de la récupération des ressources :', error)
  }
})
const filteredSections = computed(() => {
  const tag = searchTag.value.trim().toLowerCase()
  if (!tag) return sections.value
  return sections.value
    .map((section) => ({
      id: section.id,
      nom: section.nom,
      ressources: section.ressources.filter((r: Ressource) =>
        r.tags.some((t: Tag) => t.nom.toLowerCase().includes(tag)),
      ),
    }))
    .filter((section) => section.ressources.length)
})

const ressourcesFiltrees = computed(() =>
  filtreCategorie.value
    ? filteredSections.value.filter((r) => {
        const cat = sections.value.find((c) => c.id === filtreCategorie.value)
        return r.nom === cat?.nom
      })
    : filteredSections.value,
)
const scrollToSection = (id: number) => {
  const element = document.getElementById(`section-${id}`)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

</script>

<template>
  <!-- HEADER -->
  <header class="header">
    <div class="container admin-container">
      <a href="https://www.e-potion.fr/">
        <img class="tampon" :src="logo" alt="Logo e-Potion" />
      </a>

      <RouterLink to="/" class="title-link">
        <h1>Gestionnaire de ressources digitales</h1>
      </RouterLink>

      <nav>
        <ul>
          <li><RouterLink to="/admin" class="btn">Admin</RouterLink></li>
        </ul>
      </nav>
    </div>
  </header>
  <main>
    <!-- Barre de recherche -->

    <div class="container filters">
      <div class="btn-filters">
        <button
          v-for="res in sections"
          :key="res.id"
          :class="{ active: filtreCategorie === res.id }"
          @click="
            () => {
              filtreCategorie = res.id
              scrollToSection(res.id)
            }
          "
          class=""
        >
          {{ res.nom }}
        </button>
      </div>
      <div class="media-search">
        <input type="text" placeholder="Rechercher par tag" v-model="searchTag" />
        <small>Astuce : entrez un mot-clé pour lancer la recherche.</small>
      </div>
    </div>
    <section
      v-for="section in filteredSections"
      :key="section.id"
      :id="'section-' + section.id"
      class="cards ressources"
    >
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

  <Footer />
</template>

<style scoped>
/*  SEARCH BAR  */
.media-search {
  margin: 40px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.media-search input {
  width: 400px;
  padding: 0.8rem 1.5rem;
  border-radius: 25px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

.media-search small {
  display: block;
  margin-top: 8px;
  padding: 2px;
  font-size: 0.85rem;
  color: #777;
}
.ressources{
  flex-direction: column;
}
</style>
