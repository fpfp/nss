<template>
  <nav
    class="navbar navbar-expand-md navbar-dark fixed-top bg-dark"
  >
    <a class="navbar-brand" href="/"><span class="oi oi-project"></span></a>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarCollapse"
      aria-controls="navbarCollapse"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <router-link to="/" class="nav-link">Tshirts</router-link>
        </li>
      </ul>
      <div v-if="user">
        <span class="text-light mr-3"
          >Hi, <strong>{{ user.name }}</strong></span
        >
        <a @click.prevent="logout" href="#">Logout</a>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  created() {
    //console.log("Component Nav created.");
  },
  mounted() {
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
    },
  },
};
</script>
