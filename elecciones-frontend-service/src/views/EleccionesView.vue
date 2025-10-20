<script setup>
import { ref, onMounted } from "vue";
import { useEleccionStore } from "@/stores/eleccionStore";
import CrearEleccionModal from "@/views/Modales/CrearEleccionModal.vue";
import CargarVotosModal from "@/views/Modales/CargarVotosModal.vue";

const store = useEleccionStore();

const modalCreacionEleccionRef = ref(null);
const modalVotosRef = ref(null);

const eleccionSeleccionada = ref(null);

onMounted(async () => {
  await store.fetchElecciones();
});

function abrirModal() {
  modalCreacionEleccionRef.value?.abrirModal();
}

async function iniciarEleccion(id) {
  try {
    await store.iniciarEleccion(id);
    await store.fetchElecciones();
  } catch (error) {
    const message =
      error.response?.data?.message ||
      "Error desconocido al iniciar la elección.";
    alert(message);
  }
}

async function cerrarEleccion(id) {
  try {
    await store.cerrarEleccion(id);
    await store.fetchElecciones();
  } catch (error) {
    const message =
      error.response?.data?.message ||
      "Error desconocido al cerrar la elección.";
    alert(message);
  }
}

async function asignarEscanios(eleccionId) {
  try {
    const result = await store.asignarEscanios(eleccionId);
    alert("Escaños asignados correctamente.");
  } catch (error) {
    const message =
      error.response?.data?.message || "Error al asignar escaños.";
    alert(message);
  }
}

function abrirModalVotos(eleccion) {
  eleccionSeleccionada.value = eleccion;
  modalVotosRef.value?.abrirModal();
}

async function onVotosGuardados() {
  await store.fetchElecciones();
}

function onCerrar() {}
</script>

<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Elecciones</h2>
      <button class="btn btn-primary" @click="abrirModal">
        Crear Elección
      </button>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Descripción</th>
          <th>Región</th>
          <th>Escaños a elegir</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="eleccion in store.elecciones?.data ?? []" :key="eleccion.id">
          <td>#{{ eleccion.id }}</td>
          <td>{{ eleccion.descripcion }}</td>
          <td>{{ eleccion.region }}</td>
          <td>{{ eleccion.cantidad_escanios }}</td>
          <td>{{ eleccion.fecha }}</td>
          <td>{{ eleccion.estado_eleccion }}</td>
          <td>
            <div class="btn-group btn-group-sm" role="group">
              <button
                class="btn btn-success"
                v-if="eleccion.estado_eleccion === 'Creada'"
                @click="iniciarEleccion(eleccion.id)"
              >
                <i class="bi bi-play-fill"></i> Iniciar
              </button>

              <button
                class="btn btn-danger"
                v-if="eleccion.estado_eleccion === 'Iniciada'"
                @click="cerrarEleccion(eleccion.id)"
              >
                <i class="bi bi-stop-fill"></i> Cerrar
              </button>

              <button
                class="btn btn-warning"
                v-if="eleccion.estado_eleccion === 'Cerrada'"
                @click="abrirModalVotos(eleccion)"
              >
                <i class="bi bi-pencil-square"></i> Cargar votos
              </button>

              <button
                class="btn btn-info"
                v-if="eleccion.estado_eleccion === 'Cerrada'"
                @click="asignarEscanios(eleccion.id)"
              >
                <i class="bi bi-diagram-3-fill"></i> Asignar escaños
              </button>

              <button
                class="btn btn-secondary"
                v-if="eleccion.estado_eleccion === 'Finalizada'"
                @click="abrirModalVotos(eleccion)"
              >
                <i class="bi bi-eye"></i> Ver votos
              </button>
            </div>
          </td>
        </tr>

        <tr v-if="(store.elecciones?.data ?? []).length === 0">
          <td colspan="7" class="text-center">No hay elecciones cargadas</td>
        </tr>
      </tbody>
    </table>

    <CrearEleccionModal ref="modalCreacionEleccionRef" @cerrar="onCerrar" />

    <CargarVotosModal
      ref="modalVotosRef"
      :eleccion="eleccionSeleccionada"
      @guardado="onVotosGuardados"
      @cerrar="() => (eleccionSeleccionada.value = null)"
    />
  </div>
</template>
