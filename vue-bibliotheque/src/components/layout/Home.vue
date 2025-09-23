<script setup>
import CardCategory from "@/components/UI/CardCategory.vue"
import { ref, onMounted } from "vue"

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
        <!-- <RouterLink to="https://www.e-potion.fr/">
          <img class="tampon" src="img/logo_epotion.png" alt="Logo e-Potion">
        </RouterLink> -->

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
    </main>
  </div>
</template>



<style scoped>
/* header {
  background-color: #999;
  padding: 15px 0;
  color: white;
} */

header .container {
  width: 90%;
  max-width: 1200px;
  margin: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn {
  background-color: #ff6b00;
  border-radius: 5px;
  padding: 0.5rem 1.5rem;
  color: white;
}

nav ul {
  list-style: none;
  display: flex;
  gap: 15px;
  align-items: center;
}

nav a {
  text-decoration: none;
  color: white;
  padding: 5px 10px;
}

main {
  width: 90%;
  max-width: 1200px;
  margin: 40px auto;
}

.categories h2 {
  font-size: 1.8rem;
  margin-bottom: 5px;
}

.categories .subtitle {
  color: #777;
  margin-bottom: 30px;
}

.cards {
  display: flex;
  gap: 5px;
  flex-wrap: wrap;
  justify-content: space-evenly;
}


</style>
