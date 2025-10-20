import axios from "@/services/axios";

export default {
  async all() {
    const res = await axios.get("/elecciones");
    return res.data.elecciones;
  },
  async create(data) {
    const res = await axios.post("/elecciones", data);
    return res.data.eleccion;
  },
  async iniciarEleccion(id) {
    const res = await axios.post(`/elecciones/${id}/iniciar`);
    return res.data.eleccion;
  },
  async cerrarEleccion(id) {
    const res = await axios.post(`/elecciones/${id}/cerrar`);
    return res.data.eleccion;
  },
  async asignarEscanios(id) {
    const res = await axios.post(`/elecciones/${id}/asignar-escanios`)
    return res.data.eleccion
  },
};
