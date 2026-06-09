<script setup lang="ts">
import { ref, watchEffect } from 'vue'

const props = defineProps<{
  message: string
  show: boolean
  duration?: number
}>()

const emit = defineEmits(['close'])

const visible = ref(false)

watchEffect(() => {
  if (props.show) {
    visible.value = true
    setTimeout(() => {
      visible.value = false
      emit('close')
    }, props.duration ?? 3000)
  }
})
</script>

<template>
  <transition name="fade">
    <div v-if="visible" class="toast">
      {{ message }}
    </div>
  </transition>
</template>

<style scoped>
/* --- TOAST --- */
.toast {
  position: fixed;
  top: 20px;
  right: 20px;
  background: var(--toast-bg);
  color: var(--toast-text);
  padding: 12px 20px;
  border-radius: 8px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  animation: fadeInOut 3s ease forwards;
}
@keyframes fadeInOut {
  0% { opacity: 0; transform: translateY(-10px); }
  10%, 90% { opacity: 1; transform: translateY(0); }
  100% { opacity: 0; transform: translateY(-10px); }
}
</style>
