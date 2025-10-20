<script setup>
import { defineEmits, onMounted, defineExpose } from "vue";
import { useListaStore } from "@/stores/listaStore";
import { useEleccionStore } from "@/stores/eleccionStore";
import { Modal } from "bootstrap";

const emit = defineEmits(["cerrar"]);
const store = useListaStore();
const eleccionStore = useEleccionStore();

let bootstrapModal = null;

function abrirModal() {
  store.resetLista();
  if (bootstrapModal) bootstrapModal.show();
}

function cerrarModal() {
  if (bootstrapModal) {
    bootstrapModal.hide();
    emit("cerrar");
  }
}

async function crear() {
  const { nombre, numero, eleccion_id } = store.lista;

  if (!nombre || !numero || !eleccion_id) {
    alert("Por favor, complete todos los campos antes de guardar.");
    return;
  }

  await store.crearLista();
  cerrarModal();
}

onMounted(async () => {
  const modalEl = document.getElementById("crearListaModal");
  if (modalEl) {
    bootstrapModal = new Modal(modalEl, {
      backdrop: "static",
      keyboard: false,
    });
  }

  await eleccionStore.fetchElecciones();
});

defineExpose({ abrirModal });
</script>

<template>
  <div
    class="modal fade"
    id="crearListaModal"
    tabindex="-1"
    aria-labelledby="crearListaModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <form @submit.prevent="crear">
          <div class="modal-header">
            <h5 class="modal-title" id="crearListaModalLabel">Crear Lista</h5>
            <button
              type="button"
              class="btn-close"
              @click="cerrarModal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <label for="nombre" class="form-label">Nombre</label>
              <input
                id="nombre"
                v-model="store.lista.nombre"
                class="form-control"
                placeholder="Nombre"
              />
            </div>

            <div class="mb-2">
              <label for="numero" class="form-label">Número de lista</label>
              <input
                id="numero"
                v-model="store.lista.numero"
                class="form-control"
                placeholder="Número"
              />
            </div>

            <div class="mb-2">
              <label for="eleccion" class="form-label">Elección</label>
              <select
                id="eleccion"
                v-model="store.lista.eleccion_id"
                class="form-select"
              >
                <option :value="null" disabled>Seleccione una elección</option>
                <option
                  v-for="eleccion in (
                    eleccionStore.elecciones?.data || []
                  ).filter((e) => e.estado_eleccion === 'Creada')"
                  :key="eleccion.id"
                  :value="eleccion.id"
                >
                  {{ eleccion.descripcion }}
                </option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="cerrarModal"
            >
              Cancelar
            </button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
