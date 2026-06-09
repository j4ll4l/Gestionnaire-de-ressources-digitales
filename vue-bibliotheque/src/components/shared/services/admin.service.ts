import type { Categorie, Ressource } from '@/components/shared/interfaces/Categorie.interface'
import api from './api'

//  Récupération des catégories (admin)
export async function getAdminCategories(): Promise<Categorie[]> {
  const response = await api.get('/categories/admin')
  return response.data
}

//  Ajout de catégorie
export async function addCategorie(payload: { nom: string; description: string }): Promise<Categorie> {
  const response = await api.post('/categorie/add', payload)
  return response.data
}

//  Ajout de section
export async function addSection(payload: { nom: string; categorie_id: number }) {
  const response = await api.post('/categorie/add_section', payload)
  return response.data
}

//  Ajout ressource
export async function addRessource(data: {
  nom: string
  url: string
  description: string
  section_id: number
  tags?: string[]
}): Promise<Ressource> {
  const response = await api.post('/ressource', data)
  return response.data
}

//  Modification ressource
export async function editRessource(id: number, data: any): Promise<Ressource> {
  const response = await api.put(`/ressource/${id}`, data)
  return response.data
}

//  Suppression ressource
export async function deleteRessource(id: number) {
  const response = await api.delete(`/ressource/${id}`)
  return response.data
}
