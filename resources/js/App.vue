<template>
  <div class="container">
    <nav v-if="is_logged == 'true'" class="navbar navbar-expand-lg navbar-light bg-light">
      <router-link to="/" class="navbar-brand">{{ user.name }}</router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <router-link to="/" class="nav-link">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/products" class="nav-link">Products</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/auth/logout" class="nav-link">Logout</router-link>
          </li>
        </ul>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  mounted() {
  },
  computed: {
    is_logged: function() {
      return localStorage.is_logged;
    },
    user: function() {
      return JSON.parse(localStorage.user);
    }
  }
};
</script>
