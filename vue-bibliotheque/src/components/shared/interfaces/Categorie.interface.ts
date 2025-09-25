export interface Tag {
  id: number;
  nom: string;
}

export interface Ressource {
  id: number;
  nom: string;
  url: string;
  description: string;
  tags: Tag[];
}

export interface Section {
  id: number;
  nom: string;
  ressources: Ressource[];
}

export interface Categorie {
  id: number;
  nom: string;
  description: string;
  sections: Section[] ;
}
