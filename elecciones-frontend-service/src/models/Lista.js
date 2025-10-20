import axios from '@/services/axios'

export default {
  async all() {
    const res = await axios.get('/listas')
    return res.data.listas
  },

  async create(data) {
    const res = await axios.post('/listas', data)
    return res.data.lista
  },

  async update(id, data) {
    const res = await axios.put(`/listas/${id}`, data)
    return res.data.lista
  }
}
