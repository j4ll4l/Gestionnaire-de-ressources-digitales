<script setup>
import CardCategory from "@/components/UI/CardCategory.vue"
import { ref, onMounted } from "vue"
import logo from '@/assets/img/favico/favicon-32x32.png'

const currentYear = ref(new Date().getFullYear());

const categories = ref([])

onMounted(async () => {
  try {
    const response = await fetch("http://127.0.0.1:8000/api/categorie")
    if (!response.ok) {
      throw new Error("Erreur API : " + response.status)
    }
    const data = await response.json()
    categories.value = data
  } catch (error) {
    console.error("Erreur lors de la récupération des catégories :", error)
  }
})

</script>

<template>
  <div>
    <!-- HEADER -->
    <header class="header">
      <div class="container">
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

    <!-- MAIN -->
    <main>
      <section class="categories">
        <h2>Explorez les catégories principales</h2>
        <p class="subtitle">Accédez aux ressources multimédia, design et développement web.</p>

        <div class="cards">
          <CardCategory
          v-for="categorie in categories"
          :key="categorie.id"
            :nom="categorie.nom"
            :description="categorie.description"
            :id="`${categorie.id}`"
          />
        </div>
      </section>
    </main>

    <!-- FOOTER -->
      <footer class="footer">
        <div class="content has-text-centered">
          <p>
            <a href="http://www.e-potion.fr/"><strong>e-Potion</strong></a>
            by <a href="https://www.linkedin.com/in/christianbourgeoisdev" target="_blank">
              Christian Bourgeois
            </a>
            <br />
            Tous droits réservés -
            <strong>{{ currentYear }}</strong>
          </p>
          <p class="mention">made with <a href="https://bulma.io/">Bulma</a></p>
        </div>
      </footer>
  </div>
</template>



<style scoped>




</style>
