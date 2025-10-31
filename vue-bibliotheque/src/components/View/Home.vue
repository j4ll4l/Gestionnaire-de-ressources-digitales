<script setup>
import CardCategory from "@/components/UI/CardCategory.vue"
import { ref, onMounted } from "vue"
import logo from '@/assets/img/logo_epotion.png'
import Footer from "@/components/layout/Footer.vue";

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
        <a href="https://www.e-potion.fr/">
          <img class="tampon" :src="logo" alt="Logo e-Potion">
        </a>

        
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

   <Footer />
      
  </div>
</template>



<style scoped>

.footer{
  position: fixed;
  bottom: 0;
}
.categories>p{
  margin-left: 1.5rem;
  margin-top: 0.5rem;
}
.categories>h2{
  margin: 2rem 3rem;
}

.ressources{
  flex-direction: column;
}


</style>
