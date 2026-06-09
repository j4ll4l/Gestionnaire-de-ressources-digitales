<script setup lang="ts">
const props = defineProps<{
  show: boolean
  message?: string
}>()

const emit = defineEmits(['confirm', 'cancel'])
</script>

<template>
  <div v-if="show" class="popup-overlay">
    <div class="popup">
      <p>{{ message || '⚠️ Voulez-vous vraiment supprimer cet élément ?' }}</p>
      <div class="buttons">
        <button class="confirm" @click="$emit('confirm')">Oui</button>
        <button class="cancel" @click="$emit('cancel')">Annuler</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* --- POPUP --- */
.popup-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}
.popup {
  text-align: center;
  max-width: 400px;
  width: 100%;
  background: var(--popup-bg);
  padding: 20px;
  border-radius: 12px;
  box-shadow: var(--popup-shadow);
}
</style>
