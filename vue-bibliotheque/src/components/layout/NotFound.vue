<template>
  <div>
    <header class="header">
      <div class="container">
        <div class="logo">Bibliothèque de ressources</div>
        <RouterLink to="/admin" class="admin-link">Gestionnaire de ressources digitales</RouterLink>
      </div>
    </header>

    <main class="main-404">
      <div class="error-box">
        <div class="icon">😅</div>
        <h1>404 - Page non trouvée</h1>
        <p>Oups ! La page que vous cherchez n’existe pas.</p>
        <div class="buttons">
          <RouterLink to="/" class="btn-home">← Retour à l’accueil</RouterLink>
        </div>
        <div class="error-code">Code d'erreur: 404 • Vérifiez l'URL ou revenez à l'accueil.</div>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>

<style scoped>
.header {
  background-color: #595856;
  color: #fff;
  padding: 12px 0;
}

.header .container {
  width: 90%;
  max-width: 1200px;
  margin: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header .logo {
  font-weight: bold;
}

.admin-link {
  color: #fff;
}

.main-404 {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 50px 20px;
  min-height: 80vh;
}

.error-box {
  background-color: #fff;
  padding: 40px 30px;
  border-radius: 12px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.08);
  text-align: center;
  max-width: 500px;
  width: 100%;
}

.error-box .icon {
  font-size: 50px;
  margin-bottom: 20px;
}

.error-box h1 {
  font-size: 24px;
  margin-bottom: 10px;
  color: #333;
}

.error-box p {
  color: #777;
  margin-bottom: 25px;
}

.error-box .buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 15px;
  flex-wrap: wrap;
}

.error-box .btn-home {
  background-color: #595856;
  color: #fff;
  padding: 10px 20px;
  border-radius: 6px;
}

:hover.btn-home{
  background-color: #ff4500;
}

.error-code {
  font-size: 12px;
  color: #777;
}
</style>
