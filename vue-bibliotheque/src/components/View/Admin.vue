<script setup lang="ts">
import { ref, onMounted, computed, reactive } from 'vue'
import { useUser } from '@/components/shared/stores/userStore'
import { useRouter } from 'vue-router'
import Toast from '../UI/Toast.vue'
import Popup from '../UI/Popup.vue'
import type { Categorie, Ressource } from '@/components/shared/interfaces/Categorie.interface'
import {
  getAdminCategories,
  addCategorie,
  addSection,
  addRessource,
  deleteRessource,
  editRessource,
} from '@/components/shared/services/admin.service'

const store = useUser()
const router = useRouter()

function handleLogout() {
  store.logout()
  router.push({ name: 'categories' })
}

const categories = ref<Categorie[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const filtreCategorie = ref<number | null>(null)

const ressourceForm = reactive({
  nom: '',
  url: '',
  description: '',
  tags: '',
  categorieId: 0,
  sectionId: 0,
})

// Toast
const showToast = ref(false)
const toastMessage = ref('')
function afficherToast(message: string) {
  toastMessage.value = message
  showToast.value = true
}

// Catégorie / Section
const newCategorieNom = ref('')
const newCategorieDescription = ref('')
const newSectionNom = ref('')

// Ressource en cours d’édition
const editingRessource = ref<Ressource | null>(null)

onMounted(loadCategories)
async function loadCategories() {
  loading.value = true
  try {
    categories.value = await getAdminCategories()
  } catch (e: any) {
    if (e.response && e.response.status === 401) {
      afficherToast('Session expirée, veuillez vous reconnecter.')
      store.logout()
      router.push({ name: 'login' })
    } else {
      error.value = e.message || 'Erreur lors du chargement des ressources'
    }
  } finally {
    loading.value = false
  }
}

// Reset form
function resetFormRessource() {
  Object.assign(ressourceForm, {
    nom: '',
    url: '',
    description: '',
    tags: '',
    categorieId: 0,
    sectionId: 0,
  })
  editingRessource.value = null
}

// Sections filtrées
const sectionsFiltrees = computed(() => {
  const cat = categories.value.find((c) => c.id === ressourceForm.categorieId)
  return cat?.sections || []
})

// Ajouter catégorie
const ajouterNouvelleCategorie = async () => {
  if (!newCategorieNom.value.trim()) return
  const nouvelleCategorie = await addCategorie({
    nom: newCategorieNom.value,
    description: newCategorieDescription.value,
  })
  categories.value.push(nouvelleCategorie)
  ressourceForm.categorieId = nouvelleCategorie.id
  newCategorieNom.value = ''
  newCategorieDescription.value = ''
}

// Ajouter section
const ajouterNouvelleSection = async () => {
  if (!newSectionNom.value.trim() || !ressourceForm.categorieId) return
  const nouvelleSection = await addSection({
    nom: newSectionNom.value,
    categorie_id: ressourceForm.categorieId,
  })
  const categorie = categories.value.find((c) => c.id === ressourceForm.categorieId)
  if (categorie) {
    if (!categorie.sections) categorie.sections = []
    categorie.sections.push(nouvelleSection)
  }
  newSectionNom.value = ''
}

// Toutes les ressources
const allRessources = computed(() =>
  categories.value.flatMap((c) =>
    (c.sections ?? []).flatMap((s) =>
      (s.ressources ?? []).map((r) => ({
        ...r,
        categorieNom: c.nom,
        sectionNom: s.nom,
      })),
    ),
  ),
)

// Pagination
const currentPage = ref(1)
const itemsPerPage = 7
const ressourcesFiltrees = computed(() =>
  filtreCategorie.value
    ? allRessources.value.filter((r) => {
        const cat = categories.value.find((c) => c.id === filtreCategorie.value)
        return r.categorieNom === cat?.nom
      })
    : allRessources.value,
)
const totalPages = computed(() => Math.ceil(ressourcesFiltrees.value.length / itemsPerPage))
const ressourcesPaginees = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return ressourcesFiltrees.value.slice(start, start + itemsPerPage)
})

// Sauvegarde / ajout fusionnés
async function sauvegarderOuAjouter() {
  if (
    !ressourceForm.nom ||
    !ressourceForm.url ||
    !ressourceForm.description ||
    !ressourceForm.sectionId
  ) {
    alert('Veuillez remplir tous les champs')
    return
  }

  try {
    if (editingRessource.value) {
      await editRessource(editingRessource.value.id, {
        ...ressourceForm,
        section_id: ressourceForm.sectionId,
        tags: ressourceForm.tags
          .split(',')
          .map((t) => t.trim())
          .filter(Boolean),
      })
      afficherToast('✏️ Ressource modifiée avec succès')
    } else {
      await addRessource({
        ...ressourceForm,
        section_id: ressourceForm.sectionId,
        tags: ressourceForm.tags
          .split(',')
          .map((t) => t.trim())
          .filter(Boolean),
      })
      afficherToast('✅ Ressource ajoutée avec succès')
    }

    resetFormRessource()
    await loadCategories()
  } catch (error: any) {
    afficherToast(error.response?.data?.error || '❌ Erreur')
  }
}

// Edition ressource
function commencerEdition(ressource: Ressource) {
  editingRessource.value = ressource
  ressourceForm.nom = ressource.nom
  ressourceForm.url = ressource.url
  ressourceForm.description = ressource.description
  ressourceForm.tags = ressource.tags.map((t) => t.nom).join(', ')
  const section = categories.value
    .flatMap((c) => c.sections?.map((s) => ({ ...s, categorieId: c.id })) || [])
    .find((s) => s.id === ressource.section_id)
  if (section) {
    ressourceForm.categorieId = section.categorieId
    ressourceForm.sectionId = section.id
  } else {
    ressourceForm.categorieId = 0
    ressourceForm.sectionId = 0
    
  }
}

// Suppression
const showConfirm = ref(false)
const ressourceASupprimer = ref<number | null>(null)
function demanderSuppression(id: number) {
  ressourceASupprimer.value = id
  showConfirm.value = true
}
async function confirmerSuppression() {
  if (!ressourceASupprimer.value) return
  try {
    await deleteRessource(ressourceASupprimer.value)
    afficherToast('🗑️ Ressource supprimée avec succès')
    await loadCategories()
  } catch (error: any) {
    afficherToast(error.response?.data?.error || '❌ Erreur lors de la suppression')
  } finally {
    showConfirm.value = false
    ressourceASupprimer.value = null
  }
}
</script>

<template>
  <div>
    <!-- HEADER -->
    <header class="admin header">
      <div class="container">
        <h1>⚙️ Administration — Ressources</h1>
        <div class="admin-backoffice">📚 Back-office</div>
        <button class="btn" @click="handleLogout">Logout</button>
      </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="container">
      <h2>Gestion des ressources</h2>
      <Toast
      :show="showToast"
      :message="toastMessage"
      @close="showToast = false"
    />

      <div class="admin-grid">
        <!-- FORMULAIRE -->
        <section class="admin-form">
          <h3>Ajouter / Modifier une ressource</h3>

          <label>Nom de la ressource</label>
          <input v-model="ressourceForm.nom" type="text" placeholder="Ex. My Brand New Logo" />

          <label>URL</label>
          <input v-model="ressourceForm.url" type="url" placeholder="https://exemple.com/ressource" />

          <section class="admin-form">
            <h3>Ajouter / Modifier une catégorie</h3>

            <label>Catégorie</label>
            <select v-model="ressourceForm.categorieId">
              <option v-if="editingRessource" :value="ressourceForm.categorieId">{{ editingRessource.categorieNom || 'Sélectionner une Catégorie' }}</option>
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
            <select v-model="ressourceForm.sectionId">
              <option v-if="editingRessource" :value="ressourceForm.sectionId"> {{ editingRessource.sectionNom || 'Sélectionner une section' }}</option>
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
          <input v-model="ressourceForm.tags" type="text" placeholder="Ajouter des tags" />

          <label>Description</label>
          <textarea
            v-model="ressourceForm.description"
            placeholder="Description de la ressource..."
          ></textarea>

          <div class="buttons">
            <button class="save" @click="sauvegarderOuAjouter">
              {{ editingRessource ? 'Modifier' : 'Enregistrer' }}
            </button>
            <button class="cancel" @click="resetFormRessource">
              Annuler
            </button>
          </div>
        </section>

        <!-- TABLEAU DES RESSOURCES -->
        <section class="admin-resources">
          <div class="btn-filters">
            <button
              :class="{ active: filtreCategorie === null }"
              @click="filtreCategorie = null"
              class="add-btn"
            >
              Toutes les catégories
            </button>

            <button
              v-for="cat in categories"
              :key="cat.id"
              :class="{ active: filtreCategorie === cat.id }"
              @click="filtreCategorie = cat.id"
              class="add-btn"
            >
              {{ cat.nom }}
            </button>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Section</th>
                <th>URL</th>
                <th>Tags</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="ressource in ressourcesPaginees" :key="ressource.id">
                <td>{{ ressource.nom }}</td>
                <td>{{ ressource.categorieNom }}</td>
                <td>{{ ressource.sectionNom }}</td>
                <td>{{ ressource.url }}</td>
                <td>
                  <span v-for="tag in ressource.tags" :key="tag.id">#{{ tag.nom }}</span>
                </td>
                <td>
                  <div class="buttons-edit">
                    <button class="edit" @click="commencerEdition(ressource)">✏️</button>
                    <button class="delete" @click="demanderSuppression(ressource.id)">🗑️</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="pagination">
            <button :disabled="currentPage === 1" @click="currentPage--">◀ Précédent</button>

            <span>Page {{ currentPage }} / {{ totalPages }}</span>

            <button :disabled="currentPage === totalPages" @click="currentPage++">
              Suivant ▶
            </button>
          </div>
        </section>
      </div>
      <!-- Popup de confirmation -->
      <Popup
      :show="showConfirm"
      message="⚠️ Voulez-vous vraiment supprimer cette ressource ?"
      @confirm="confirmerSuppression"
      @cancel="showConfirm = false"
    />
    </main>
  </div>

</template>

<style scoped>

.admin-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: var(--admin-grid-gap);
  width: 100%;
  align-items: flex-start;
}
/* --- FORMULAIRES --- */
.admin-form,
.admin-resources,
.popup {
  background: var(--popup-bg);
  padding: 20px;
  border-radius: 12px;
  box-shadow: var(--popup-shadow);
}
.admin-form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.admin-form h3 {
  margin-bottom: 10px;
}
.admin-form input,
.admin-form select,
.admin-form textarea,
.admin-category-new input {
  width: 100%;
  padding: 10px;
  font-size: 0.95rem;
  border: 1px solid var(--input-border);
  border-radius: 8px;
}
.admin-form textarea {
  min-height: 80px;
  resize: none;
}
.admin-category-new {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 10px;
}
.admin-category-new input:focus {
  outline: none;
  border-color: var(--input-focus);
}
.admin-category-new .add-btn {
  background: var(--btn-secondary-bg);
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px 15px;
  font-size: 0.95rem;
  cursor: pointer;
  color: var(--btn-secondary-text);
  transition: background 0.2s;
}
.admin-category-new .add-btn:hover {
  background: #f5f5f5;
}


</style>


