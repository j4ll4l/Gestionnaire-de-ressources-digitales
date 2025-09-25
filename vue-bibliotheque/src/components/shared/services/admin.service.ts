import type { Categorie } from "@/components/shared/interfaces/Categorie.interface";

const BASE_URL = "http://127.0.0.1:8000/api/categories/admin";

export async function getAdminCategories(): Promise<Categorie[]> {
  try {
    const token = localStorage.getItem("token"); // token JWT
    const response = await fetch(BASE_URL, {
      headers: {
        "Content-Type": "application/json",
        Authorization: token ? `Bearer ${token}` : "",
      },
    });

    if (!response.ok) {
      throw new Error("Erreur lors de la récupération des catégories");
    }

    return response.json();
  } catch (error) {
    throw error;
  }
}

export async function addCategorie(payload: {
  nom: string
  description: string
}): Promise<Categorie> {
  try {
    const token = localStorage.getItem("token")
    const response = await fetch("http://127.0.0.1:8000/api/categorie/add", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: token ? `Bearer ${token}` : "",
      },
      body: JSON.stringify(payload),
    })

    if (!response.ok) {
  const errorText = await response.text();
  console.error("Erreur API:", response.status, errorText);
  throw new Error("Erreur lors de l’ajout de la catégorie");
}

    return response.json()
  } catch (error) {
    throw error
  }
}

export async function addSection(payload: { nom: string; categorie_id: number }) {
  try {
    const token = localStorage.getItem("token");
    const response = await fetch("http://127.0.0.1:8000/api/categorie/add_section", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: token ? `Bearer ${token}` : "",
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      const errorText = await response.text();
      console.error("Erreur API:", response.status, errorText);
      throw new Error("Erreur lors de l’ajout de la section");
    }

    return response.json();
  } catch (error) {
    throw error;
  }
}
