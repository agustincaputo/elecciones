import { defineStore } from "pinia";
import { reactive } from "vue";
import Lista from "@/models/Lista";

export const useListaStore = defineStore("listas", {
  state: () => ({
    listas: [],
    lista: reactive({
      nombre: "",
      numero: "",
      cantidad_escanios: 0,
      eleccion: null,
      eleccion_id: null,
    }),
  }),

  actions: {
    async fetchListas() {
      this.listas = await Lista.all();
    },

    resetLista() {
      this.lista.nombre = "";
      this.lista.numero = "";
      this.lista.cantidad_escanios = 0;
      this.lista.eleccion_id = null;
    },

    async crearLista() {
      await Lista.create({ ...this.lista });
      this.resetLista();
      await this.fetchListas();
    },

    async actualizarCantidadVotos({ id, cantidad_votos }) {
      await Lista.update(id, { cantidad_votos })
    },
  },
});
