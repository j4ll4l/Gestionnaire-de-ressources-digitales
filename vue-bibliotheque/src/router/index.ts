import { createRouter, createWebHistory } from 'vue-router'
import routes from '@/router/routes'
import { useUser } from '@/components/shared/stores/userStore';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const store = useUser();

  // Routes nécessitant l'auth
  if (to.meta.requiresAuth && !store.isAuthenticated) {
    return next({ name: 'login' });
  }

  // Redirection si l'utilisateur connecté tente d'aller sur login
  if (to.name === 'login' && store.isAuthenticated) {
    return next({ name: 'admin' });
  }

  next();
});

export default router
