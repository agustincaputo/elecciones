import { createRouter, createWebHistory } from 'vue-router'
import EleccionesView from '@/views/EleccionesView.vue'
import ListasView from '@/views/ListasView.vue'

const routes = [
  { path: '/', redirect: '/elecciones' },
  { path: '/elecciones', name: 'Elecciones', component: EleccionesView },
  { path: '/listas', name: 'Listas', component: ListasView }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
