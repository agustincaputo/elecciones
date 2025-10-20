<script setup>
import { RouterLink } from 'vue-router'
</script>
<template>
  <div class="bg-light border-end vh-100" style="width: 220px;">
    <div class="list-group list-group-flush">
      <RouterLink
        to="/elecciones"
        class="list-group-item list-group-item-action"
        active-class="active"
      >
        Elecciones
      </RouterLink>
      <RouterLink
        to="/listas"
        class="list-group-item list-group-item-action"
        active-class="active"
      >
        Listas
      </RouterLink>
    </div>
  </div>
</template>

<style scoped>
.list-group-item.active {
  background-color: #0d6efd;
  color: white;
}
</style>
