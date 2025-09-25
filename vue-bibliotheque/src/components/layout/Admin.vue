<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useUser } from '@/components/shared/stores/userStore'
import { useRouter } from 'vue-router'
import {
  getAdminCategories,
  addCategorie,
  addSection,
} from '@/components/shared/services/admin.service'
import axios from 'axios'
import type { Categorie, Ressource } from '@/components/shared/interfaces/Categorie.interface'

const store = useUser()
const router = useRouter()

function handleLogout() {
  store.logout()
  router.push({ name: 'categories' })
}

const categories = ref<Categorie[]>([])
const selectedCategorie = ref<number>()
const loading = ref(true)
const error = ref<string | null>(null)

const selectedSectionCategorie = ref<number | null>(null)
const newSectionNom = ref('')

// nouveaux champs
const newCategorieNom = ref('')
const newCategorieDescription = ref('')

// charger les catégories
onMounted(async () => {
  categories.value = await getAdminCategories()
})

// ajouter une catégorie
const ajouterNouvelleCategorie = async () => {
  if (!newCategorieNom.value.trim()) return

  const nouvelleCategorie = await addCategorie({
    nom: newCategorieNom.value,
    description: newCategorieDescription.value,
  })

  categories.value.push(nouvelleCategorie) // on met à jour la liste
  selectedCategorie.value = nouvelleCategorie.id // on sélectionne la nouvelle
  newCategorieNom.value = ''
  newCategorieDescription.value = ''
}

// Pour le formulaire
const editingRessource = ref<Ressource | null>(null)

onMounted(async () => {
  await loadCategories()
})

async function loadCategories() {
  loading.value = true
  try {
    categories.value = await getAdminCategories()
  } catch (e: any) {
    error.value = e.message || 'Erreur lors du chargement des ressources'
  } finally {
    loading.value = false
  }
}

const allRessources = computed(() =>
  categories.value.flatMap((categorie) =>
    (categorie.sections ?? []).flatMap((section) =>
      (section.ressources ?? []).map((ressource) => ({
        ...ressource,
        categorieNom: categorie.nom,
        sectionNom: section.nom,
      })),
    ),
  ),
)

const selectedSection = ref<number | null>(null) // section sélectionnée
// nouvelle section

// sections filtrées selon la catégorie sélectionnée
const sectionsFiltrees = computed(() => {
  if (!selectedCategorie.value) return []
  const categorie = categories.value.find((c) => c.id === selectedCategorie.value)
  return categorie?.sections || []
})

// ajouter une nouvelle section
const ajouterNouvelleSection = async () => {
  if (!newSectionNom.value.trim() || !selectedCategorie.value) return

  try {
    const nouvelleSection = await addSection({
      nom: newSectionNom.value,
      categorie_id: selectedCategorie.value,
    })

    // Mise à jour de la catégorie dans le state
    const categorie = categories.value.find((c) => c.id === selectedCategorie.value)
    if (categorie) {
      if (!categorie.sections) categorie.sections = []
      categorie.sections.push(nouvelleSection)
    }

    newSectionNom.value = ''
  } catch (err) {
    console.error("Erreur lors de l'ajout de la section", err)
  }
}
</script>

<template>
  <div>
    <!-- HEADER -->
    <header class="admin header">
      <div class="admin-container">
        <h1>⚙️ Administration — Ressources</h1>
        <div class="admin-backoffice">📚 <RouterLink to="/">Back-office</RouterLink></div>
        <button class="btn" @click="handleLogout">Logout</button>
      </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="admin-main">
      <h2>Gestion des ressources</h2>

      <div class="admin-grid">
        <!-- FORMULAIRE -->
        <section class="admin-form">
          <h3>Ajouter / Modifier une ressource</h3>

          <label>Nom de la ressource</label>
          <input type="text" placeholder="Ex. My Brand New Logo" />

          <label>URL</label>
          <input type="url" placeholder="https://exemple.com/ressource" />

          <section class="admin-form">
            <h3>Ajouter / Modifier une catégorie</h3>

            <label>Catégorie</label>
            <select v-model="selectedCategorie">
              <option :value="null">Sélectionner une catégorie</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nom }}</option>
            </select>

            <div class="admin-category-new">
              <input v-model="newCategorieNom" placeholder="Nouvelle catégorie" />
              <input v-model="newCategorieDescription" placeholder="Description" />
              <button type="button" class="add-btn" @click="ajouterNouvelleCategorie">
                + Ajouter
              </button>
            </div>

            <label>Section</label>
            <select v-model="selectedSection">
              <option :value="null">Sélectionner une section</option>
              <option v-for="section in sectionsFiltrees" :key="section.id" :value="section.id">
                {{ section.nom }}
              </option>
            </select>

            <div class="admin-category-new">
              <input v-model="newSectionNom" placeholder="Nouvelle section" />
              <button type="button" class="add-btn" @click="ajouterNouvelleSection">
                + Ajouter
              </button>
            </div>
          </section>

          <label>Tags</label>
          <input type="text" placeholder="Ajouter des tags" />

          <label>Description</label>
          <textarea placeholder="Description de la ressource..."></textarea>

          <div class="admin-buttons">
            <button class="save">Enregistrer</button>
          </div>
        </section>

        <!-- TABLEAU DES RESSOURCES -->
        <section class="admin-resources">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>URL</th>
                <th>Tags</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="ressource in allRessources" :key="ressource.id">
                <td>{{ ressource.nom }}</td>
                <td>{{ ressource.categorieNom }}</td>
                <td>{{ ressource.url }}</td>
                <td>
                  <span v-for="tag in ressource.tags" :key="tag.id">#{{ tag.nom }}</span>
                </td>
                <td>
                  <div class="buttons-edit">
                    <button class="edit">✏️</button>
                    <button class="delete">🗑️</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </section>
      </div>
    </main>
  </div>
  <!--FOOTER-->
  <footer class="footer">
    <div class="content has-text-centered">
      <p>
        <a href="http://www.e-potion.fr/"><strong>e-Potion</strong></a> by
        <a href="https://www.linkedin.com/in/christianbourgeoisdev" target="_blank"
          >Christian Bourgeois</a
        >
        <br />
        Tous droits réservés -
        <strong>
          <!-- <script>document.write(new Date().getFullYear())</script> -->
        </strong>
      </p>
      <p class="mention">made with <a href="https://bulma.io/">Bulma</a></p>
    </div>
  </footer>
</template>

<script setup></script>

<style scoped>
/* .admin-container {
  margin: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-backoffice {
  font-weight: bold;
}

.admin-main {
  max-width: 1200px;
  margin: 30px auto;
}

.admin-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
}

.admin-form {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.admin-form textarea {
  resize: none;
  min-height: 80px;
}

.admin-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.admin-buttons .save {
  background-color: #ff4500;
  color: white;
  border: none;
  padding: 8px 14px;
}

.admin-resources {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.08);
}

.admin-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 15px;
}

.admin-table th, .admin-table td {
  text-align: left;
  padding: 12px;
  border-bottom: 1px solid #eee;
}

.admin-table td span {
  background-color: #333;
  color: white;
  padding: 3px 8px;
  border-radius: 10px;
  font-size: 0.8rem;
  margin-right: 5px;
}

.buttons-edit {
  display: flex;
}
.edit {
  background-color: #f1f1f1;
  border: none;
  padding: 6px 10px;
}
.delete {
  background-color: #ff4500;
  color: white;
  border: none;
  padding: 6px 10px;
} */
.admin-container {
  /* width: 90%;
  max-width: 1200px; */
  margin: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-backoffice {
  font-weight: bold;
}

/* ----- MAIN ----- */
.admin-main {
  /* width: 90%; */
  max-width: 1200px;
  margin: 30px auto;
}

.admin-main h2 {
  margin-bottom: 20px;
}

/* ----- GRID ----- */
.admin-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
}

/* ----- FORM ----- */
.admin-form {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.admin-form h3 {
  margin-bottom: 10px;
}

.admin-form input,
.admin-form textarea,
.admin-form select {
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 0.95rem;
  width: 100%;
}

.admin-form textarea {
  min-height: 80px;
}

.admin-categories,
.admin-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.admin-categories span,
.admin-tags span {
  background-color: #333;
  color: white;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.8rem;
}
.admin-form textarea {
  resize: none;
}

.admin-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.admin-buttons .save {
  background-color: #ff4500;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
}

.admin-buttons .reset {
  background-color: white;
  border: 1px solid #ccc;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
}

/* ----- TABLE ----- */
.admin-resources {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
}

/* .admin-searchbar {
  margin-bottom: 15px;
}

.admin-searchbar input {
  width: 100%;
  padding: 10px;
  border-radius: 25px;
  border: 1px solid #ccc;
} */

/* .admin-filters {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.admin-filters button {
  border: 1px solid #ccc;
  background-color: white;
  padding: 6px 12px;
  border-radius: 20px;
  cursor: pointer;
}

.admin-filters button.active {
  background-color: #eee;
} */

.admin-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 15px;
}

.admin-table th,
.admin-table td {
  text-align: left;
  padding: 12px;
  border-bottom: 1px solid #eee;
  vertical-align: top;
}

.admin-table td span {
  background-color: #333;
  color: white;
  padding: 3px 8px;
  border-radius: 10px;
  font-size: 0.8rem;
  margin-right: 5px;
}
.buttons-edit {
  display: flex;
  flex-wrap: nowrap;
}
/* -------- Nouvelle catégorie -------- */
.admin-category-new {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.admin-category-new input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 0.95rem;
}

.admin-category-new input:focus {
  outline: none;
  border-color: #ff6b00;
}

.admin-category-new .add-btn {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px 15px;
  font-size: 0.95rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  color: #333;
  transition: background 0.2s;
}

.admin-category-new .add-btn:hover {
  background: #f5f5f5;
}

/* Actions */
.admin-table .edit {
  background-color: #f1f1f1;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  cursor: pointer;
  margin-right: 5px;
  min-height: 50px;
  min-width: 50px;
}

.admin-table .delete {
  background-color: #ff4500;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  cursor: pointer;
  min-height: 50px;
  min-width: 50px;
}

.admin-hint {
  font-size: 0.85rem;
  color: #777;
  border: 1px dashed #ddd;
  border-radius: 10px;
  padding: 10px;
  text-align: center;
}
</style>
