<script setup>
import { onMounted, ref } from 'vue'
import { useListaStore } from '@/stores/listaStore'
import CrearListaModal from '@/views/Modales/CrearListaModal.vue'

const store = useListaStore()
const modalCrearListaRef = ref(null)

onMounted(async () => {
  await store.fetchListas()
})

function abrirModal() {
  modalCrearListaRef.value?.abrirModal()
}

function onCerrar() {
}
</script>

<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Listas</h2>
      <button class="btn btn-primary" @click="abrirModal">Crear Lista</button>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Número</th>
          <th>Elección</th>
          <th>Votos obtenidos</th>
          <th>Escaños obtenidos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(lista, index) in store.listas.data" :key="index">
          <td>{{ lista.nombre }}</td>
          <td>{{ lista.numero }}</td>
          <td>{{ lista.eleccion?.descripcion }}</td>
          <td>{{ lista.cantidad_votos ?? 'En proceso' }}</td>
          <td>{{ lista.cantidad_escanios ?? 'En proceso'}}</td>
        </tr>
        <tr v-if="(store.listas?.data ?? []).length === 0">
          <td colspan="5">No hay listas cargadas</td>
        </tr>
      </tbody>
    </table>

    <CrearListaModal ref="modalCrearListaRef" @cerrar="onCerrar" />
  </div>
</template>
