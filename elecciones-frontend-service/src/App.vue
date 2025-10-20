<script setup>
import AppNavbar from '@/components/AppNavbar.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import { RouterView } from 'vue-router'
</script>

<template>
  <div id="app">
    <AppNavbar />

    <div class="d-flex">
      <AppSidebar />

      <div class="flex-grow-1 p-4">
        <RouterView />
      </div>
    </div>
  </div>
</template>

<style>
body {
  margin: 0;
}
</style>
