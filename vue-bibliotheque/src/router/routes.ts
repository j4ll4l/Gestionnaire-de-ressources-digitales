import type { RouteRecordRaw } from 'vue-router'
import Home from '@/components/layout/Home.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/categorie',
    name: 'categories',
    component: Home,
  },
  {
    path: '/categorie/:id',
    name: 'categorie',
    component: () => import('@/components/layout/Ressource.vue'),
  },
  {
    path: '/admin',
    name: 'admin',
    component: () => import('@/components/layout/Admin.vue'),
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/components/layout/Login.vue'),
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/components/layout/NotFound.vue'),
  },
]
export default routes
