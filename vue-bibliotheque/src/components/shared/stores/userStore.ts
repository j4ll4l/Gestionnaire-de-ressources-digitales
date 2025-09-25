import { defineStore } from 'pinia'
import type { LoginForm, User } from '../interfaces'
import { login  } from "../services/user.service";

interface UserState {
  currentUser: User | null
  token: string | null
  error: any | null
}

export const useUser = defineStore("user", {
  state: (): UserState => ({
    currentUser: null,
    token: localStorage.getItem("token"), // recharge le token si déjà stocké
    error: null,
  }),

  getters: {
    /**
     * Vérifie si l'utilisateur est connecté
     */
    isAuthenticated: (state): boolean => !!state.token,
  },

  actions: {
    /**
     * Login utilisateur
     */
    async login(loginForm: LoginForm) {
      try {
        const response = await login(loginForm); // { token: "..." }
        this.token = response.token;

        // Stockage du token pour persistance
        localStorage.setItem("token", response.token);

        this.error = null;

        

      } catch (e) {
        this.error = e;
        this.token = null;
        localStorage.removeItem("token");
      }
    },

    /**
     * Déconnexion utilisateur
     */
    logout() {
      this.currentUser = null;
      this.token = null;
      this.error = null;
      localStorage.removeItem("token");
    },
  },
});