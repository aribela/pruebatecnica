<template>
  <div id="app">
    <nav class="navbar navbar-expand navbar-dark bg-dark">
      <router-link to="/" class="navbar-brand">GendraVue</router-link>
      <div class="navbar-nav mr-auto">
        <li class="nav-item">
          <router-link to="/evidencias" class="nav-link">Evidencias</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/add" class="nav-link">Agregar</router-link>
        </li>
      </div>
    </nav>

    <!-- <div class="container mt-3"> -->
    <div class="row">
      <router-view />
    </div>
  </div>
</template>

<script>
export default {
  name: "app"
};
</script>
