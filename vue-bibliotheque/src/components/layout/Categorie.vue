<template>
  <div class="container">
      <div class="card">
        <h2 class="mb-2">Catégorie</h2>
        <select class="input mb-2">
          <option>Sélectionner une catégorie</option>
          <option>Développement</option>
          <option>Design</option>
          <option>Marketing</option>
        </select>
        <div class="tags mb-2">
          <span class="tag">Développement</span>
          <span class="tag">Design</span>
          <span class="tag">Marketing</span>
        </div>
        <div class="flex mt-1">
          <input type="text" class="input" placeholder="Ou créer une nouvelle catégorie..." />
          <button class="btn btn-neutral">+ Ajouter</button>
        </div>
      </div>
  </div>
</template>
