import type { RouteRecordRaw } from 'vue-router'
import Home from '@/components/View/Home.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'categories',
    component: Home,
  },
  {
    path: '/categorie/:id',
    name: 'categorie',
    component: () => import('@/components/View/Ressource.vue'),
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import('@/components/View/Admin.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/components/View/Login.vue'),
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/components/View/NotFound.vue'),
  },
]
export default routes
