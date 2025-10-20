<script setup>
import { defineEmits, ref, watch, onMounted, defineProps, defineExpose } from "vue";
import { Modal } from "bootstrap";
import { useListaStore } from "@/stores/listaStore";

const emit = defineEmits(["cerrar", "guardado"]);
const listaStore = useListaStore();

const modalEl = ref(null);
let bootstrapModal = null;

const props = defineProps({
  eleccion: Object,
});

const listasConVotos = ref([]);

watch(
  () => props.eleccion,
  (newEleccion) => {
    if (newEleccion?.listas) {
      listasConVotos.value = newEleccion.listas.map((lista) => ({
        id: lista.id,
        nombre: lista.nombre,
        cantidad_escanios: lista.cantidad_escanios,
        cantidad_votos: lista.cantidad_votos ?? 0,
      }));
    } else {
      listasConVotos.value = [];
    }
  },
  { immediate: true }
);

function abrirModal() {
  if (bootstrapModal) bootstrapModal.show();
}

function cerrarModal() {
  if (bootstrapModal) {
    bootstrapModal.hide();
    emit("cerrar");
  }
}

async function guardarVotos() {
  try {
    for (const lista of listasConVotos.value) {
      await listaStore.actualizarCantidadVotos({
        id: lista.id,
        cantidad_votos: lista.cantidad_votos,
      });
    }
    alert("Votos actualizados correctamente.");
    cerrarModal();
    emit("guardado");
  } catch (error) {
    alert("Error al guardar los votos.");
    console.error(error);
  }
}

onMounted(() => {
  if (modalEl.value) {
    bootstrapModal = new Modal(modalEl.value, {
      backdrop: "static",
      keyboard: false,
    });
  }
});

defineExpose({ abrirModal });
</script>

<template>
  <div
    class="modal fade"
    tabindex="-1"
    ref="modalEl"
    aria-labelledby="cargarVotosModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form @submit.prevent="guardarVotos">
          <div class="modal-header">
            <h5 class="modal-title" id="cargarVotosModalLabel">
              Cargar votos para la elección "{{ eleccion?.descripcion }}"
            </h5>
            <button
              type="button"
              class="btn-close"
              @click="cerrarModal"
            ></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Nombre Lista</th>
                  <th>Cantidad de votos</th>
                  <th>Escaños</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="lista in listasConVotos" :key="lista.id">
                  <td>{{ lista.nombre }}</td>
                  <td>
                    <template v-if="eleccion?.estado_eleccion !== 'Finalizada'">
                      <input
                        type="number"
                        min="0"
                        class="form-control"
                        v-model.number="lista.cantidad_votos"
                      />
                    </template>
                    <template v-else>
                      {{ lista.cantidad_votos }}
                    </template>
                  </td>
                  <td>{{ lista.cantidad_escanios }}</td>
                </tr>
                <tr v-if="listasConVotos.length === 0">
                  <td colspan="3" class="text-center">
                    No hay listas para esta elección
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="btn btn-primary"
              v-if="eleccion?.estado_eleccion !== 'Finalizada'"
            >
              Guardar votos
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
