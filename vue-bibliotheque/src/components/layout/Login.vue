<script setup lang="ts">
import { z } from 'zod';
import { toTypedSchema } from '@vee-validate/zod';
import { useField, useForm } from 'vee-validate';
import type { LoginForm } from '@/components/shared/interfaces/User.interface';
import {  useUser } from '@/components/shared/stores/userStore'; 
import { useRouter } from 'vue-router';

const store = useUser(); 
const router = useRouter();

if (store.isAuthenticated) {
  router.push({ name: "dashboard" });
}

const validationSchema = toTypedSchema(
  z.object({
    username: z.string().nonempty("Champ incorrect"),
    password: z.string().nonempty("Champ incorrect")
  })
);

const { handleSubmit } = useForm({ validationSchema });

const submit = handleSubmit(async (formValue: LoginForm) => {
  await store.login(formValue);

  if (store.isAuthenticated) {
    router.push({ name: 'admin' });
  } else {
    console.error("Erreur de connexion :", store.error);
  }
});


const { value: nameValue, errorMessage: nameError} = useField('username');
const { value: passwordValue, errorMessage: passwordError} = useField('password');

</script>

<template>
  <div>
    <header class="login-header">
      <div class="login-container">
        <h1>Bibliothèque Admin</h1>
      </div>
    </header>

    <main class="login-main">
      <div class="login-box">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
            class="bi bi-door-open" viewBox="0 0 16 16">
            <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
            <path
              d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117M11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5M4 1.934V15h6V1.077z" />
          </svg>
        </div>
        <h2>Connexion</h2>
        <form @submit.prevent="submit">
          <div class="login-field">
            <label for="email">Email</label>
            <input v-model="nameValue" type="text" id="username" placeholder="exemple@mail.com" required>
            <p v-if="nameError" class="form-error">{{ nameError }}</p>
          </div>

          <div class="login-field">
            <label for="password">Mot de passe</label>
            <input v-model="passwordValue" type="password" id="password" placeholder="••••••••" required>
            <p v-if="passwordError" class="form-error">{{ passwordError }}</p>
          </div>

          <p v-if="store.error" class="form-error">
            Identifiants incorrects
          </p>

          <button type="submit" class="login-btn">Se connecter</button>
        </form>
      </div>
    </main>
  </div>
</template>



<style scoped>
.login-header {
  background-color: #595856;
  padding: 15px 0;
  color: white;
  text-align: center;
}

.login-main {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 80vh;
}

.login-box {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  width: 350px;
  text-align: center;
}

.login-box h2 {
  margin: 20px 0;
  color: #333;
}

.login-field {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin-bottom: 15px;
}

.login-field label {
  font-size: 0.9rem;
  margin-bottom: 5px;
  color: #555;
}

.login-field input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 0.95rem;
}

.login-field input:focus {
  outline: none;
  border-color: #ff6b00;
}

.login-btn {
  width: 100%;
  padding: 10px;
  background: #ff6b00;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  margin-top: 10px;
}

.login-btn:hover {
  background: #ff8533;
}
</style>
