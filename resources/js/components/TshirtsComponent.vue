<template>
  <div>
    <section class="jumbotron text-center">
      <div class="container">
        <h1 class="jumbotron-heading">NSS - Tshirts Designer</h1>
        <p class="lead text-muted">
          Select your t-shirt and start creating your custom design now.
        </p>
      </div>
    </section>
    <!-- begin tshirts grid -->
    <div class="tshirts py-5 bg-light">
      <div class="container">
        <div v-if="!tshirts.length" class="text-center text-muted mt-3 mb-5">
          No tshirts found.
        </div>
        <div v-if="tshirts" class="row">
          <div
            class="tshirt col-md-4"
            v-for="(tshirt, index) in tshirts"
            :key="index"
          >
            <div class="card mb-4 box-shadow">
              <img class="card-img-top" :src="tshirt.thumbnail" />
              <div class="card-body">
                <p class="card-text text-center">{{ tshirt.name }}</p>
                <div class="text-center">
                  <router-link
                    :to="{ name: 'editor', params: { tshirtId: tshirt.id }}"
                    class="btn btn-outline-secondary btn-design"
                    tag="button"
                  >
                    <span class="oi oi-brush"></span> Start Design
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end tshirts grid -->
  </div>
</template>

<script>
export default {
  created() {
    //console.log("Component Tshirts created.");
  },
  mounted() {
    this.loadTshirts();
    this.scroll();
  },
  computed: {
    user: function () {
      return this.$store.state.user;
    },
  },
  data() {
    return {
      page: 1,
      tshirts: [],
    };
  },
  methods: {
    loadTshirts() {
      axios
        .get("/api/tshirts", { params: { page: this.page } })
        .then((response) => {
          //console.log(response);
          if (response.data) {
            if (this.page === 1) {
              this.tshirts = [];
            }

            this.tshirts.push(...response.data.data);

            if (!response.data.next_page_url) {
              window.onscroll = null;
            }
          }
        })
        .catch((error) => console.log(error)); //request fail;
    },
    refresh() {
      this.page = 1;
      this.loadTshirts();
    },
    scroll() {
      window.onscroll = () => {
        let bottomOfWindow =
          document.documentElement.scrollTop + window.innerHeight ===
          document.documentElement.offsetHeight;

        if (bottomOfWindow) {
          this.page++;
          this.loadTshirts();
        }
      };
    },
  },
};
</script>
