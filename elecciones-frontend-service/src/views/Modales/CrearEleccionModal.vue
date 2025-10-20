<script setup>
import { defineEmits, onMounted, defineExpose } from 'vue'
import { useEleccionStore } from '@/stores/eleccionStore'
import { Modal } from 'bootstrap'

let bootstrapModal = null

const store = useEleccionStore()
const emit = defineEmits(['cerrar'])

function abrirModal() {
  store.resetEleeccion()
  if (bootstrapModal) {
    bootstrapModal.show()
  }
}

function cerrarModal() {
  if (bootstrapModal) {
    bootstrapModal.hide()
    emit('cerrar')
  }
}

async function crear() {
  await store.crearEleccion()
  cerrarModal()
}

onMounted(() => {
  const modalEl = document.getElementById('crearEleccionModal')
  if (modalEl) {
    bootstrapModal = new Modal(modalEl, {
      backdrop: 'static',
      keyboard: false,
    })
  }
})

defineExpose({ abrirModal })
</script>

<template>
  <div
    class="modal fade"
    id="crearEleccionModal"
    tabindex="-1"
    aria-labelledby="crearEleccionModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <form @submit.prevent="crear">
          <div class="modal-header">
            <h5 class="modal-title" id="crearEleccionModalLabel">Elección</h5>
            <button type="button" class="btn-close" @click="cerrarModal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <label for="descripcion" class="form-label">Descripción</label>
              <input
                id="descripcion"
                v-model="store.eleccion.descripcion"
                class="form-control"
                placeholder="Descripción"
              />
            </div>
            <div class="mb-2">
              <label for="region" class="form-label">Región</label>
              <input
                id="region"
                v-model="store.eleccion.region"
                class="form-control"
                placeholder="Región"
              />
            </div>
            <div class="mb-2">
              <label for="puesto_elegido" class="form-label">Puesto</label>
              <input
                id="puesto_elegido"
                v-model="store.eleccion.puesto_elegido"
                class="form-control"
                placeholder="Puesto"
              />
            </div>
            <div class="mb-2">
              <label for="fecha" class="form-label">Fecha</label>
              <input
                id="fecha"
                type="date"
                v-model="store.eleccion.fecha"
                class="form-control"
              />
            </div>
            <div class="mb-2">
              <label for="cantidad_escanios" class="form-label">Escaños</label>
              <input
                id="cantidad_escanios"
                type="number"
                v-model="store.eleccion.cantidad_escanios"
                class="form-control"
                placeholder="Escaños"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
