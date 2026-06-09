<script setup lang="ts">
import { ref } from 'vue';

const currentYear = ref(new Date().getFullYear())

</script>

<template>
    <!-- FOOTER -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p>
        <a href="http://www.e-potion.fr/"><strong>e-Potion</strong></a>
        by
        <a href="https://www.linkedin.com/in/christianbourgeoisdev" target="_blank">
          Christian Bourgeois
        </a>
        <br />
        Tous droits réservés -
        <strong>{{ currentYear }}</strong>
      </p>
      <p class="mention">made with <a href="https://bulma.io/">Bulma</a></p>
    </div>
  </footer>
</template>

<style scoped>
/* --- FOOTER --- */
.footer {
  width: 100%;
  background-color: var(--footer-background-color);
  font-size: 0.8rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1.5rem 0;
  margin-top: 2rem;
}
.footer .content {
  width: 100%;
  text-align: center;
}
.footer a {
  color: var(--link);
}
.mention {
  font-size: 0.7rem;
}
</style>