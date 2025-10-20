import { defineStore } from "pinia";
import { reactive } from "vue";
import Eleccion from "../models/Eleccion";

const eleccionDefault = {
  descripcion: "",
  region: "",
  puesto_elegido: "",
  fecha: "",
  cantidad_votantes: null,
  cantidad_escanios: 10,
};

export const useEleccionStore = defineStore("elecciones", {
  state: () => ({
    elecciones: [],
    eleccion: reactive({ ...eleccionDefault }),
  }),

  actions: {
    async fetchElecciones() {
      this.elecciones = await Eleccion.all();
    },

    async crearEleccion() {
      await Eleccion.create({ ...this.eleccion });
      this.resetEleeccion();
      this.fetchElecciones();
    },

    resetEleeccion() {
      Object.assign(this.eleccion, eleccionDefault);
    },

    async iniciarEleccion(id) {
      await Eleccion.iniciarEleccion(id);
      await this.fetchElecciones();
    },

    async cerrarEleccion(id) {
      await Eleccion.cerrarEleccion(id);
      await this.fetchElecciones();
    },
    
    async asignarEscanios(id) {
      const eleccionActualizada = await Eleccion.asignarEscanios(id)
      await this.fetchElecciones()
      return eleccionActualizada
    }
  },
});
